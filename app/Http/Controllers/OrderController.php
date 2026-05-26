<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\ShippingAddress;
use App\Models\VaBank;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
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
        $tax      = $subtotal * 0.0836;
        $total    = $subtotal + $shipping + $tax;

        $paymentMethods = PaymentMethod::all()
            ->map(fn($p) => [
                'value' => $p->name,
                'label' => match($p->name) {
                    'qris'            => 'QRIS',
                    'virtual_account' => 'Virtual Account',
                    'credit_card'     => 'Kartu Kredit / Debit',
                    'ovo'             => 'OVO',
                    'dana'            => 'DANA',
                    default           => ucfirst($p->name),
                }
            ]);

        $banks = VaBank::all()
            ->map(fn($b) => [
                'value' => $b->name,
                'name'  => strtoupper($b->name),
            ]);

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

        return view('store.checkout', compact(
            'items', 'paymentMethods', 'banks', 'pendingVoucher',
            'provinces', 'savedAddresses', 'totalWeight', 'subtotal', 'shipping', 'myVouchers', 'tax', 'total'
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

        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $deliveryFee = (float) $request->input('delivery_fee', 0);
            $addressId   = $request->input('address_id');

            $firstName = '';
            $lastName  = '';
            $street    = '';
            $cityName  = '';
            $zip       = '';

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
                    'city_code'      => '',
                    'city_name'      => $cityName,
                    'district_code'  => '',
                    'district_name'  => '',
                    'village_code'   => '',
                    'village_name'   => $request->input('village_name'),
                    'postal_code'    => $zip,
                ]);
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

            $subtotalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $serviceTax    = $subtotalPrice * 0.1;
            $voucherId      = $request->input('applied_voucher_id');
            $discountAmount = 0;
            $appliedVoucher = null;
            $paymentId = PaymentMethod::where('name', $paymentMethod)->first()?->id ?? 1;

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

                    if ($appliedVoucher->used_for === 'shipping') {
                        $discountAmount = min($deliveryFee, $maxDiscount);
                    } else {
                        $discountAmount = min($subtotalPrice, $maxDiscount);
                    }
                }
            }

            $totalPrice = $subtotalPrice + $deliveryFee + $serviceTax - $discountAmount;

            $order = Order::create([
                'user_id'             => Auth::id(),
                'payment_id'          => $paymentId,
                'voucher_id'          => $appliedVoucher?->id ?? null,
                'shipping_address_id' => $shippingAddress->id,
                'delivery_fee'        => $deliveryFee,
                'service_tax'         => $serviceTax,
                'discount_amount'     => $discountAmount,
                'total_price'         => $totalPrice,
                'status'              => 'unpaid',
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
                    'credit_card'     => 'credit_card',
                    'ovo'             => 'ovo',
                    'dana'            => 'dana',
                    default           => $paymentMethod,
                };
            }

            $params = [
                'transaction_details' => [
                    'order_id'     => 'JACED-ORD-' . $order->id . '-' . time(),
                    'gross_amount' => $totalPrice,
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

            DB::commit();
            if ($appliedVoucher) {
                DB::table('vouchers')
                    ->where('id', $appliedVoucher->id)
                    ->update([
                        'redeemed_at' => now(),
                        'is_active'   => false,
                    ]);
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $userId = Auth::id();
            Cart::where('user_id', $userId)->delete();
            session()->forget('cart');

            return view('store.payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::rollBack();
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function payment_status($order_id){
        $order = Order::findOrFail($order_id);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        
        try {
            /** @var object $statusResponse */
            // Menggunakan ID Order karena invoice_number tidak ada di database kalian
            $statusResponse = \Midtrans\Transaction::status($order->id);
            $transactionStatus = $statusResponse->transaction_status;

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $order->status = 'packed'; 
            } elseif ($transactionStatus == 'pending') {
                $order->status = 'unpaid';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->status = 'cancelled';
            }
            $order->save();

        } catch (\Exception $e) {
            $order->save();
        }

        if ($order->status == 'packed') {
            $totalBelanja = $order->total_price; 
            $poinBaru = floor($totalBelanja / 10000); 

            if ($poinBaru > 0 && Auth::check()) {
                $user = Auth::user();
                
                $user->increment('current_points', $poinBaru);
                $user->increment('accumulated_points', $poinBaru);

                DB::table('point_histories')->insert([
                    'user_id'    => $user->id,
                    'points'     => $poinBaru,
                    'type'       => 'earned',
                    'source'     => 'purchase',
                    'order_id'   => $order->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Arahkan ke riwayat transaksi dengan flash message jumlah poin yang didapat
            return redirect()->route('store.orderhistory')
                             ->with('success', 'Payment successful! You earned ' . $poinBaru . ' points.');
                             
        } elseif ($order->status == 'pending') {
            return redirect()->route('store.orderhistory')->with('error', 'Payment is pending. Please complete it.');
        } else {
            return redirect()->route('store.orderhistory')->with('error', 'Payment failed or expired.');
        }
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
                        ->get(['code', 'name']);

        return response()->json($villages);
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

        return response()->json(array_values($allCosts));
    }


    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
// ======================== TRANSACTION HISTORY & DETAIL ========================
    public function index(Request $request)
    {
        $filters = ['All', 'Unpaid', 'Packed', 'Delivered', 'Arrived', 'Cancelled'];
        $activeFilter = $request->get('filter', 'All');

        // Mapping filter tab → status di DB
        $statusMap = [
            'Unpaid'     => 'unpaid',
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