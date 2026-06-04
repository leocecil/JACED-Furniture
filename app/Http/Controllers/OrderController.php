<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\PointHistory;
use App\Models\ShippingAddress;
use App\Models\VaBank;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function showCheckout()
    {
        $provinces = DB::table('indonesia_provinces')->orderBy('name', 'asc')->get(); 
        $savedAddresses = DB::table('shipping_address')
                        ->where('user_id', auth()->user()->id)
                        ->get();

        $cartItems = \App\Models\Cart::with('product.images')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kamu kosong!');
        }

        $myVouchers = DB::table('vouchers')
                    ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
                    ->where('vouchers.user_id', Auth::id())
                    ->where('vouchers.is_active', true)
                    ->where('vouchers.expiry_date', '>', now())
                    ->select('vouchers.*', 'voucher_types.name', 'voucher_types.description', 'voucher_types.used_for', 'voucher_types.discount_percentage', 'voucher_types.max_discount')
                    ->get();

        $items = $cartItems->map(fn($cart) => [
            'name'    => $cart->product->name,
            'variant' => $cart->product->label ?? '-',
            'qty'     => $cart->quantity,
            'price'   => $cart->product->price,
            'image'   => $cart->product->images->where('is_main', 1)->first()?->image_path
                        ?? $cart->product->images->first()?->image_path
                        ?? 'https://placehold.co/200x200',
        ]);

        $totalWeight = $cartItems->sum(function($cart) {
            $p = $cart->product;
            if (!$p || !$p->length || !$p->width || !$p->height) {
                return 1000; // fallback kalau dimensi belum diisi
            }
            $volumetric = ($p->length * $p->width * $p->height) / 6;
            return $volumetric * $cart->quantity;
        });
        $totalWeight = max(1000, (int) $totalWeight);

        $subtotal = $items->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = 0;
        $tax = (int) round($subtotal * 0.05);

        $pendingVoucherId = session('pending_voucher_id');
        $pendingVoucher = null; 

        if ($pendingVoucherId) {
            $pendingVoucher = DB::table('vouchers')
                ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
                ->where('vouchers.id', $pendingVoucherId)
                ->where('vouchers.user_id', Auth::id())
                ->where('vouchers.is_active', true)
                ->whereNull('vouchers.redeemed_at')
                ->select('vouchers.*', 'voucher_types.name', 'voucher_types.used_for', 'voucher_types.max_discount', 'voucher_types.discount_percentage')
                ->first();
        }
        $discountAmount = 0;
        if ($pendingVoucher) {
            $maxDiscount = (float) $pendingVoucher->max_discount;
            if ($pendingVoucher->used_for === 'delivery') {
                $discountAmount = min($shipping, $maxDiscount);
            } else {
                $calculatedDiscount = $subtotal * ($pendingVoucher->discount_percentage / 100);
                $discountAmount = min($calculatedDiscount, $maxDiscount);
            }
        }
        // Ambil tier user
        $userStage = DB::table('stages')
            ->where('min_points_accumulative', '<=', Auth::user()->accumulated_points ?? 0)
            ->orderBy('min_points_accumulative', 'desc')
            ->first();

        $tierDiscountAmount = 0;
        if ($userStage && $userStage->discount_percentage > 0) {
            $tierDiscountAmount = round($subtotal * ($userStage->discount_percentage / 100));
        }

        $total = $subtotal + $shipping + $tax - $discountAmount - $tierDiscountAmount;

        $paymentMethods = PaymentMethod::whereIn('name', ['qris', 'virtual_account'])->get()
            ->map(fn($p) => [
                'value' => $p->name,
                'label' => match($p->name) {
                    'qris'            => 'QRIS',
                    'virtual_account' => 'Virtual Account',
                }
            ]);

        $banks = VaBank::all()
            ->map(fn($b) => [
                'value' => $b->name,
                'name'  => strtoupper($b->name),
            ]);

        $defaultAddress = DB::table('shipping_address')
            ->where('user_id', Auth::id())
            ->where('is_default', true)
            ->first();

        return view('store.checkout', compact(
            'items', 'paymentMethods', 'banks', 'pendingVoucher', 'defaultAddress', 
            'provinces', 'savedAddresses', 'totalWeight', 'subtotal', 'shipping', 'myVouchers', 'tax', 'total', 'discountAmount', 'userStage', 'tierDiscountAmount'
        ));
    }

    public function processCheckout(Request $request)
    {
        $cartItems = \App\Models\Cart::with('product')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $paymentMethod = $request->input('payment_method');
        $chosenBank    = $request->input('bank');

        $subtotalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        
        if (empty($paymentMethod)) {
            return redirect()->back()->with('error', 'Silakan pilih metode pembayaran.');
        }

        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $deliveryFee   = (float) $request->input('delivery_fee', 0);
            $addressId     = $request->input('address_id');
            $addressAction = $request->input('address_action');

            $firstName = '';
            $lastName  = '';
            $street    = '';
            $cityName  = '';
            $zip       = '';

            // ── CASE 1: User edit alamat lama via modal sebelum checkout ──
            if ($addressAction === 'update' && $request->input('edit_address_id')) {
                $editId = $request->input('edit_address_id');
                $addr   = ShippingAddress::where('id', $editId)
                            ->where('user_id', Auth::id())
                            ->first();

                if ($addr) {
                    $provinceCode = $request->input('province_code');
                    $provinceName = DB::table('indonesia_provinces')
                                        ->where('code', $provinceCode)->first()?->name ?? '';

                    $addr->update([
                        'receiver_name'  => $request->input('receiver_name'),
                        'receiver_phone' => $request->input('receiver_phone'),
                        'address_line1'  => $request->input('address_line1'),
                        'province_code'  => $provinceCode,
                        'province_name'  => $provinceName,
                        'city_code'      => $request->input('city_code', ''),
                        'city_name'      => $request->input('city_name'),
                        'district_code'  => $request->input('district_code', ''),
                        'district_name'  => $request->input('district_name', ''),
                        'village_code'   => $request->input('village_code', ''),
                        'village_name'   => $request->input('village_name'),
                        'postal_code'    => $request->input('postal_code'),
                    ]);

                    $shippingAddress = $addr->fresh();
                    $nameParts = explode(' ', $shippingAddress->receiver_name, 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName  = $nameParts[1] ?? '';
                    $street    = $shippingAddress->address_line1;
                    $cityName  = $shippingAddress->city_name;
                    $zip       = $shippingAddress->postal_code;
                }
            }

            // ── CASE 2: Pakai alamat lama tanpa edit, atau buat alamat baru ──
            if (!isset($shippingAddress)) {
                if ($addressId && $addressId !== 'new') {
                    $shippingAddress = ShippingAddress::find($addressId);

                    if (!$shippingAddress || $shippingAddress->user_id !== Auth::id()) {
                        return redirect()->back()->with('error', 'Alamat tidak valid.');
                    }

                    $nameParts = explode(' ', $shippingAddress->receiver_name, 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName  = $nameParts[1] ?? '';
                    $street    = $shippingAddress->address_line1;
                    $cityName  = $shippingAddress->city_name;
                    $zip       = $shippingAddress->postal_code;

                } else {
                    $receiverName = $request->input('receiver_name');
                    $nameParts    = explode(' ', $receiverName, 2);
                    $firstName    = $nameParts[0] ?? '';
                    $lastName     = $nameParts[1] ?? '';
                    $street       = $request->input('address_line1');
                    $cityName     = $request->input('city_name');
                    $zip          = $request->input('postal_code');
                    $provinceCode = $request->input('province_code');
                    $provinceName = DB::table('indonesia_provinces')->where('code', $provinceCode)->first()?->name ?? '';

                    $shippingAddress = ShippingAddress::create([
                        'user_id'        => Auth::id(),
                        'receiver_name'  => $receiverName,
                        'receiver_phone' => $request->input('receiver_phone'),
                        'address_line1'  => $street,
                        'province_code'  => $provinceCode,
                        'province_name'  => $provinceName,
                        'city_code'      => $request->input('city_code', ''),
                        'city_name'      => $cityName,
                        'district_code'  => $request->input('district_code', ''),
                        'district_name'  => $request->input('district_name', ''),
                        'village_code'   => $request->input('village_code', ''),
                        'village_name'   => $request->input('village_name'),
                        'postal_code'    => $zip,
                    ]);

                    $user = Auth::user();
                    $isFirstAddress = ShippingAddress::where('user_id', Auth::id())->count() === 1;
                    
                    if ($isFirstAddress && !$user->address_rewarded) {
                        $user->current_points     += 50;
                        $user->accumulated_points += 50;
                        $user->address_rewarded    = true;
                        $user->save();

                        PointHistory::create([
                            'user_id'    => $user->id,
                            'points'     => 50,
                            'type'       => 'earned',
                            'source'     => 'Profile Completion - Address',
                            'expired_at' => now()->addYear(),
                        ]);
                    }
                }
            }

            $totalWeight = 0;
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                if ($product) {
                    $volumetric   = ($product->length * $product->width * $product->height) / 6;
                    $totalWeight += $volumetric * $cartItem->quantity;
                }
            }
            $totalWeight = max(1000, (int) $totalWeight);

            $subtotalPrice  = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $serviceTax     = (int) round($subtotalPrice * 0.05);
            $voucherId      = $request->input('applied_voucher_id');
            $discountAmount = 0;
            $appliedVoucher = null;
            $paymentId = PaymentMethod::where('name', $paymentMethod)->first()?->id ?? 1;

            $vaBankId = null;
            if ($paymentMethod === 'virtual_account' && !empty($chosenBank)) {
                $vaBankId = DB::table('va_banks')->where('name', $chosenBank)->first()?->id;
            }

            if ($voucherId) {
                $appliedVoucher = DB::table('vouchers')
                    ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
                    ->where('vouchers.id', $voucherId)
                    ->where('vouchers.user_id', Auth::id())
                    ->where('vouchers.is_active', true)
                    ->where('vouchers.expiry_date', '>', now())
                    ->whereNull('vouchers.redeemed_at')
                    ->select('vouchers.*', 'voucher_types.used_for', 'voucher_types.max_discount', 'voucher_types.discount_percentage')
                    ->first();

                if ($appliedVoucher) {
                    $maxDiscount = (float) $appliedVoucher->max_discount;

                    if ($appliedVoucher->used_for === 'delivery') {
                        $discountAmount = min($deliveryFee, $maxDiscount);
                    } else {
                        $calculatedDiscount = $subtotalPrice * ($appliedVoucher->discount_percentage / 100);
                        $discountAmount = min($calculatedDiscount, $maxDiscount);
                    }
                }
            }

            // Setelah bagian voucher discount, tambah ini:
            $tierDiscountAmount = 0;
            $stageId = null;

            $userStage = DB::table('stages')
                ->where('min_points_accumulative', '<=', Auth::user()->accumulated_points ?? 0)
                ->orderBy('min_points_accumulative', 'desc')
                ->first();

            if ($userStage && $userStage->discount_percentage > 0) {
                $tierDiscountAmount = round($subtotalPrice * ($userStage->discount_percentage / 100));
                $stageId = $userStage->id;
            }

            $totalPrice = $subtotalPrice + $deliveryFee + $serviceTax - $discountAmount - $tierDiscountAmount;

            $order = Order::create([
                'user_id'             => Auth::id(),
                'payment_id'          => $paymentId,
                'va_bank_id'          => $vaBankId,
                'voucher_id'          => $appliedVoucher?->id ?? null,
                'shipping_address_id' => $shippingAddress->id,
                'delivery_fee'        => $deliveryFee,
                'service_tax'         => $serviceTax,
                'discount_amount'     => $discountAmount,
                'total_price'         => $totalPrice,
                'status'              => 'unpaid',
                'tier_discount_amount'=> $tierDiscountAmount,
                'stage_id'            => $stageId,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'subtotal'   => $cartItem->product->price * $cartItem->quantity,
                ]);
            }

            // Midtrans
            \Midtrans\Config::$serverKey    = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $item_details = [];
            foreach ($cartItems as $cartItem) {
                $item_details[] = [
                    'id'       => 'PROD-' . $cartItem->product_id,
                    'price'    => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'name'     => $cartItem->product->name,
                ];
            }

            if ($deliveryFee > 0) {
                $item_details[] = [
                    'id'       => 'DELIVERY',
                    'price'    => $deliveryFee,
                    'quantity' => 1,
                    'name'     => 'Ongkos Kirim',
                ];
            }

            if ($serviceTax > 0) {
                $item_details[] = [
                    'id'       => 'TAX',
                    'price'    => round($serviceTax),
                    'quantity' => 1,
                    'name'     => 'Pajak Layanan',
                ];
            }

            if ($discountAmount > 0) {
                $item_details[] = [
                    'id'       => 'DISCOUNT',
                    'price'    => -round($discountAmount),
                    'quantity' => 1,
                    'name'     => 'Diskon Voucher',
                ];
            }

            if ($tierDiscountAmount > 0) {
                $item_details[] = [
                    'id'       => 'TIER_DISCOUNT',
                    'price'    => -round($tierDiscountAmount),
                    'quantity' => 1,
                    'name'     => 'Member Tier Discount (' . $userStage->discount_percentage . '%)',
                ];
            }

            // Hitung gross_amount dari item_details agar selalu balance ke Midtrans
            $grossAmount = (int) collect($item_details)->sum(fn($i) => $i['price'] * $i['quantity']);
            $enabledPayments = [];
            if ($paymentMethod === 'virtual_account' && $chosenBank !== 'all' && !empty($chosenBank)) {
                if ($chosenBank === 'mandiri') {
                    $enabledPayments[] = 'echannel';
                } else {
                    $enabledPayments[] = $chosenBank . '_va';
                }
            } elseif (!empty($paymentMethod)) {
                $enabledPayments[] = match($paymentMethod) {
                    'qris'            => 'other_qris',
                    default           => $paymentMethod,
                };
            }

            $midtransOrderId = 'JACED-ORD-' . $order->id . '-' . time();
            $order->update(['midtrans_order_id' => $midtransOrderId]);

            $params = [
                'transaction_details' => [
                    'order_id'     => $midtransOrderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $item_details,
                'customer_details' => [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => Auth::user()->email,
                    'shipping_address' => [
                        'first_name'   => $firstName,
                        'last_name'    => $lastName,
                        'address'      => $street,
                        'city'         => $cityName,
                        'postal_code'  => $zip,
                        'country_code' => 'IDN',
                    ],
                ],
                'callbacks' => [
                    'finish' => route('payment_return', $order->id),
                ],
            ];

            if (!empty($enabledPayments)) {
                $params['enabled_payments'] = $enabledPayments;
            }

            $snapToken = Snap::getSnapToken($params);
 
            $order->update(['total_price' => $grossAmount]);
            if ($appliedVoucher) {
                DB::table('vouchers')
                    ->where('id', $appliedVoucher->id)
                    ->update([
                        'redeemed_at' => now(),
                        'is_active'   => false,
                    ]);
            }
            DB::commit();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $userId = Auth::id();
            Cart::where('user_id', $userId)->delete();
            session()->forget('cart');

            return view('store.payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function payment_status($order_id)
    {
        $order = Order::findOrFail($order_id);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        
        try {
            if (!$order->midtrans_order_id) {
                return redirect()->route('store.orderhistory')->with('error', 'Data transaksi tidak ditemukan.');
            }

            $statusResponse    = \Midtrans\Transaction::status($order->midtrans_order_id);
            $transactionStatus = $statusResponse->transaction_status;

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $order->status       = 'on_process';
                $order->on_process_at = now();
            } elseif ($transactionStatus == 'pending') {
                $order->status = 'unpaid';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->status       = 'cancelled';
                $order->cancelled_at = now();
            }
            $order->save();

        } catch (\Exception $e) {
            Log::error('Midtrans status error order #' . $order->id . ': ' . $e->getMessage());
            return redirect()->route('store.orderhistory')->with('error', 'Fail to get Midtrans status.');
        }

        if ($order->status == 'on_process') {
            Mail::to($order->user->email)->send(new \App\Mail\OrderConfirmationMail($order));
            return redirect()->route('store.orderhistory')
                ->with('success', 'Payment successful! Pesanan kamu sedang diproses.');
        } elseif ($order->status == 'unpaid') {
            return redirect()->route('store.orderhistory')->with('error', 'Payment is pending. Please complete it.');
        } else {
            return redirect()->route('store.orderhistory')->with('error', 'Payment failed or expired.');
        }
    }

    public function handleNotification(Request $request)
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        $notification      = new \Midtrans\Notification();
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;

        $order = Order::where('midtrans_order_id', $notification->order_id)->firstOrFail();

        if ($transactionStatus == 'capture') {
            $order->status = $fraudStatus == 'accept' ? 'on_process' : 'cancelled';
            if ($order->status == 'on_process') $order->on_process_at = now();
            if ($order->status == 'cancelled')  $order->cancelled_at  = now();

        } elseif ($transactionStatus == 'settlement') {
            $order->status       = 'on_process';
            $order->on_process_at = now();

        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->status      = 'cancelled';
            $order->cancelled_at = now();

        } elseif ($transactionStatus == 'pending') {
            $order->status = 'unpaid';
        }

        $order->save();
        return response()->json(['status' => 'ok']);
    }

    public function payment_return($order_id){
    return redirect()->route('payment_status', $order_id);
    }

    // 5. Tambahkan Method Baru ini untuk melayani request AJAX dari Blade kamu
    public function getCities(Request $request)
    {
        $cities = DB::table('indonesia_cities')
                    ->where('province_code', $request->province_code)
                    ->orderBy('name', 'asc') // Opsional: sekalian diurutkan biar rapi
                    ->get(); // mengambil code dan name saja biar load-nya cepat

        return response()->json($cities);
    }

    public function getDistricts(Request $request)
    {
        $districts = DB::table('indonesia_districts')
                        ->where('city_code', $request->city_code)
                        ->orderBy('name', 'asc')
                        ->get(['code', 'name']);

        return response()->json($districts);
    }

    public function getVillages(Request $request)
    {
        $villages = DB::table('indonesia_villages')
                        ->where('district_code', $request->district_code)
                        ->orderBy('name', 'asc')
                        ->get(['id', 'code', 'name']);

        return response()->json($villages);
    }

    public function getPostalCode(Request $request)
    {
        $postalCodes = DB::table('postal_codes')
                        ->where('village_id', $request->village_id)
                        ->pluck('postal_code');

        return response()->json($postalCodes);
    }

    public function getShippingCost(Request $request)
    {
        $villageName = $request->input('village_name');
        $cityName    = $request->input('city_name');
        $weight      = $request->input('weight', 1000);

        $rajaOngkir    = new RajaOngkirService();
        $destinationId = $rajaOngkir->searchDestination($villageName, $cityName);

        if (!$destinationId) {
            return response()->json(['error' => 'Alamat tidak ditemukan'], 404);
        }


        $couriers = ['jne', 'jnt', 'sicepat', 'lion'];
        $allCosts = [];

        foreach ($couriers as $courier) {
            $costs = $rajaOngkir->calculateCost($destinationId, $weight, $courier);
            $filtered = match($courier) {
            'jne' => array_filter($costs, function($c) use ($weight) {
                $svc = strtoupper($c['service']);
                if (!str_starts_with($svc, 'JTR')) return false;

                $weightKg = $weight / 1000;

                if (str_contains($svc, '>200')) return $weightKg >= 200;
                if (str_contains($svc, '>130')) return $weightKg >= 130 && $weightKg < 200;
                if (str_contains($svc, '<130')) return $weightKg >= 10  && $weightKg < 130;

                // JTR polos = untuk < 10kg
                return $weightKg < 10;
            }),

            // JNT: skip aja, ga ada cargo service-nya
            'jnt' => [],

            // SiCepat: GOKIL itu cargo, REG yang dibuang
            'sicepat' => array_filter($costs, function($c) {
                $svc = strtoupper($c['service']);
                if (str_contains($svc, 'REG')) return false;
                return true; // ambil GOKIL dan service lain non-REG
            }),

            // Lion: BIGPACK only
            'lion' => array_filter($costs, function($c) {
                return strtoupper($c['service']) === 'BIGPACK';
            }),

            default => [],
            };
            $allCosts = array_merge($allCosts, array_values($filtered));
        }
        usort($allCosts, fn($a, $b) => $a['cost'] <=> $b['cost']);
        return response()->json(array_values($allCosts));
    }

    public function saveAddressFromCheckout(Request $request)
    {
        $user    = Auth::user();
        $isFirst = \App\Models\ShippingAddress::where('user_id', $user->id)->count() === 0;

        $address = \App\Models\ShippingAddress::create([
            'user_id'        => $user->id,
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'address_line1'  => $request->address_line1,
            'province_code'  => $request->province_code,
            'province_name'  => $request->province_name,
            'city_code'      => $request->city_code,
            'city_name'      => $request->city_name,
            'district_code'  => $request->district_code ?? '',
            'district_name'  => $request->district_name ?? '',
            'village_code'   => $request->village_code ?? '',
            'village_name'   => $request->village_name,
            'postal_code'    => $request->postal_code,
            'is_default'     => $isFirst,
        ]);

        $pointsEarned = 0;
        if ($isFirst && !$user->address_rewarded) {
            $user->current_points     += 50;
            $user->accumulated_points += 50;
            $user->address_rewarded    = true;
            $user->save();

            \App\Models\PointHistory::create([
                'user_id'    => $user->id,
                'points'     => 50,
                'type'       => 'earned',
                'source'     => 'Profile Completion - Address',
                'expired_at' => now()->addYear(),
            ]);

            $pointsEarned = 50;
        }

        return response()->json([
            'success'       => true,
            'address_id'    => $address->id,
            'points_earned' => $pointsEarned,
        ]);
    }


    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
// ======================== TRANSACTION HISTORY & DETAIL ========================
    public function index(Request $request)
    {
        $filters = ['All', 'On Process', 'Unpaid', 'Packed', 'Delivered', 'Arrived', 'Cancelled'];
        $activeFilter = $request->get('filter', 'All');

        // Mapping filter tab → status di DB
        $statusMap = [
            'Unpaid'     => 'unpaid',
            'On Process' => 'on_process',
            'Packed'     => 'packed',
            'Delivered'  => 'delivered',
            'Arrived'    => 'arrived',
            'Cancelled'  => 'cancelled',
        ];

        $query = \App\Models\Order::with(['orderDetails.product.images'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($activeFilter !== 'All' && isset($statusMap[$activeFilter])) {
            $query->where('status', $statusMap[$activeFilter]);
        }

        $orders = $query->get();

        return view('store.order-history-detail', [
            'filters'      => $filters,
            'activeFilter' => $activeFilter,
            'orders'       => $orders,
        ]);
    }
}