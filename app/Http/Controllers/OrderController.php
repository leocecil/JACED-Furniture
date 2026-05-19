<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function showCheckout()
    {
        // 1. Ambil data Provinsi dari Database (Laravolt)
        $provinces = Province::orderBy('name', 'asc')->get();
        $cart = session('cart', []); // Ambil data cart dari session, default ke array kosong jika belum ada

        // 2. Ambil data produk (Untuk sementara kalau belum ada tabel Cart/Keranjang, biarkan array ini dulu)
        // Kalau nanti sudah ada table cart, tinggal ganti: $items = Cart::where('user_id', auth()->id())->get();
        $items = [
            [
                'name'    => 'The Nora Lounge Chair',
                'variant' => 'Cream Boucle / Walnut',
                'qty'     => 1,
                'price'   => 1250.0,
                'image'   => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=200&h=200&fit=crop',
            ],
            [
                'name'    => 'Oka Dining Table',
                'variant' => 'Light Oak / 72in',
                'qty'     => 1,
                'price'   => 2100.0,
                'image'   => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=200&h=200&fit=crop',
            ],
            [
                'name'    => 'Luna Floor Lamp',
                'variant' => 'Brass / White Shade',
                'qty'     => 2,
                'price'   => 350.0,
                'image'   => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=200&h=200&fit=crop',
            ]
        ];

        // 3. Kalkulasi harga secara dinamis berdasarkan data item
        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = 150.0; // Nanti bisa dibikin dinamis juga berdasarkan kota tujuan
        $tax      = $subtotal * 0.0836;
        $total    = $subtotal + $shipping + $tax;

        // 4. Data statis yang memang tidak berubah (opsional tetap di-hardcode di sini atau dipindah ke config)
        $paymentMethods = [
            ['value' => 'qris', 'label' => 'QRIS'],
            ['value' => 'virtual_account', 'label' => 'Virtual Account'],
            ['value' => 'credit_card', 'label' => 'Kartu Kredit / Debit'],
            ['value' => 'ovo', 'label' => 'OVO'],
            ['value' => 'dana', 'label' => 'DANA'],
        ];

        $banks = [
            ['value' => 'bca', 'name' => 'BCA'],
            ['value' => 'mandiri', 'name' => 'Mandiri'],
            ['value' => 'bni', 'name' => 'BNI'],
            ['value' => 'bri', 'name' => 'BRI'],
        ];

        return view('store.checkout', compact(
            'items',
            'paymentMethods',
            'banks',
            'provinces',
            'subtotal',
            'shipping',
            'tax',
            'total'
        ));
    }

    public function processCheckout(Request $request){
        // dd($request->all());
        // =================================================================
        // FIX: SUNTIK DATA SEMENTARA KE SESSION BIAR TIDAK DIANGGAP KOSONG
        // =================================================================
        if (!session()->has('cart') || empty(session('cart'))) {
            session()->put('cart', [
                'prod_101' => [
                    'name'     => 'The Nora Lounge Chair',
                    'price'    => 1250.0,
                    'quantity' => 1,
                    'image'    => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=200&h=200&fit=crop'
                ],
                'prod_102' => [
                    'name'     => 'Oka Dining Table',
                    'price'    => 2100.0,
                    'quantity' => 1,
                    'image'    => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=200&h=200&fit=crop'
                ]
            ]);
        }
        // =================================================================

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        // Ambil data input metode pembayaran dari form checkout Jaced
        $paymentMethod = $request->input('payment_method'); // Contoh: 'gopay', 'bank_transfer', dll.
        $chosenBank = $request->input('bank'); // Contoh: 'bca', 'bni', 'all'

        DB::beginTransaction();
        try {
            // FIX 2: MATIKAN FOREIGN KEY CHECK SEMENTARA (KARENA TABEL RELASI KOSONG)
            // =================================================================
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $firstName  = $request->input('first_name');
            $lastName   = $request->input('last_name');
            $street     = $request->input('street');
            $provinceId = $request->input('province'); // Berisi kode provinsi (contoh: "35")
            $cityId     = $request->input('city');     // Berisi kode kota (contoh: "3578")
            $zip        = $request->input('zip');

            // 2. Cari nama asli Provinsi & Kota dari DB Laravolt berdasarkan kodenya
            $provinceName = Province::where('code', $provinceId)->first()?->name ?? '';
            $cityName     = City::where('code', $cityId)->first()?->name ?? '';

            $subtotalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            $deliveryFee = 150.0;
            $serviceTax = $subtotalPrice * 0.0836;
            $totalPrice = $subtotalPrice + $deliveryFee + $serviceTax;

            $order = Order::create([
                'user_id'             => Auth::id(),
                'payment_id'          => 1, // Sementara di-hardcode ID 1 (pastikan di table payment_methods sudah ada data)
                'voucher_id'          => null,
                'shipping_address_id' => 1, // Sementara di-hardcode ID 1 (nanti harusnya relasi ke table shipping_address)
                'delivery_fee'        => $deliveryFee,
                'service_tax'         => $serviceTax,
                'discount_amount'     => 0,
                'total_price'         => $totalPrice,
                'status'              => 'pending',
            ]);

            // 2. INSERT KE TABEL ORDER DETAILS (Sesuaikan dengan migration temenmu)
            foreach ($cart as $product_id => $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => 1, // Sementara di-hardcode ID 1 (sesuaikan dengan ID product yg ada di DB kamu)
                    'quantity'   => $item['quantity'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);
            }

            // Midtrans payment integration
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Prepare item details for Midtrans
            $item_details = [];
            foreach ($cart as $product_id => $item) {
                $item_details[] = [
                    'id'       => $product_id,
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                    'name'     => substr($item['name'], 0, 50)
                ];
            }

            // LOGIKA STRATEGI OTOMATISASI METODE PEMBAYARAN MIDTRANS
            $enabledPayments = [];
            if ($paymentMethod === 'virtual_account' && $chosenBank !== 'all' && !empty($chosenBank)) {
                // Jika memilih Virtual Account bank spesifik (bca, bni, bri)
                // Khusus Mandiri, Midtrans menggunakan nama kunci 'echannel'
                if ($chosenBank === 'mandiri') {
                    $enabledPayments[] = 'echannel';
                } else {
                    $enabledPayments[] = $chosenBank . '_va'; // menghasilkan bca_va, bni_va, dll.
                }
            } elseif (!empty($paymentMethod)) {
                // Jika memilih gopay, shopeepay, atau credit_card
                $enabledPayments[] = $paymentMethod;
            }

            // Create Midtrans Transaction
            $params = [
                    'transaction_details' => [
                        'order_id'     => 'JACED-ORD-' . $order->id . '-' . time(),
                        'gross_amount' => $totalPrice,
                    ],
                    'item_details' => $item_details,
                    // --- SESUAIKAN BAGIAN INI ---
                    'customer_details' => [
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'email' => Auth::user()->email,
                        // Tambahkan array shipping_address di bawah ini agar alamat masuk ke Midtrans
                        'shipping_address' => [
                            'first_name'   => $firstName,
                            'last_name'    => $lastName,
                            'address'      => $street,
                            'city'         => $cityName,
                            'postal_code'  => $zip,
                            'country_code' => 'IDN'
                        ]
                    ],
                    // ----------------------------
                    'callbacks' => [
                        'finish' => route('payment_return', $order->id), 
                    ]
                ];
            // Jika array enabledPayments tidak kosong, suntikkan ke parameter Midtrans
            if (!empty($enabledPayments)) {
                $params['enabled_payments'] = $enabledPayments;
            }

            $snapToken = Snap::getSnapToken($params);
            // $order->payment_url = $snapToken; // Simpan token di database
            // $order->save();
            
            DB::commit();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            session()->forget('cart'); // Bersihkan keranjang
            
            
            return view('store.payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::rollBack();
            // return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        
            // // TAMBAHKAN BARIS INI UNTUK MENAMPILKAN ERROR ASLINYA:
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
            $statusResponse = \Midtrans\Transaction::status($order->invoice_number);
            $transactionStatus = $statusResponse->transaction_status;

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $order->status = 'paid';
                if (!$order->paid_at) {
                    $order->paid_at = now();
                }
            } elseif ($transactionStatus == 'pending') {
                $order->status = 'pending';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->status = 'failed';
            }
            $order->save();

        } catch (\Exception $e) {
            // Transaction not found or other Midtrans API error
 			// Transaction not found usually means user closed the popup before selecting a payment method
            $order->status = 'failed';
            // $order->payment_url = null; // Invalidate the token so they cannot use it anymore
            $order->save();
			return redirect()->route('store.transactionhistory')->with('error', 'Unable to retrieve payment status: ' . $e->getMessage());
        }

        if ($order->status == 'paid') {
            return redirect()->route('store.transactionhistory')->with('success', 'Payment successful!');
        } elseif ($order->status == 'pending') {
            return redirect()->route('store.transactionhistory')->with('error', 'Payment is pending. Please complete it.');
        } else {
            return redirect()->route('store.transactionhistory')->with('error', 'Payment failed or expired.');
        }
    }

    public function payment_return($order_id){
    return redirect()->route('payment_status', $order_id);
    }

    // 5. Tambahkan Method Baru ini untuk melayani request AJAX dari Blade kamu
    public function getCities(Request $request)
    {
        $cities = City::where('province_code', $request->province_code)
                      ->orderBy('name', 'asc')
                      ->get(['code', 'name']); // mengambil code dan name saja biar load-nya cepat

        return response()->json($cities);
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
// ======================== TRANSACTION HISTORY & DETAIL ========================
    public function index(Request $request)
    {
        $filters = [
            'All',
            'Unpaid',
            'Processing',
            'Shipped',
            'Completed',
            'Returns',
            'Cancelled'
        ];

        $activeFilter = $request->get('status', 'All');

        $artisanUpdates = [
            [
                'time'  => 'Today, 09.14 AM',
                'title' => 'Package Shipped',
                'desc'  => 'Your order has been picked up by the courier and is on its way.',
            ],
            [
                'time'  => 'Yesterday, 03.45 PM',
                'title' => 'QC Inspection Passed',
                'desc'  => 'Our master craftsmen have verified the finish on your lounge chair.',
            ],
        ];

        return view('store.transaction_history', [
            'filters' => $filters,
            'activeFilter' => $activeFilter,
            'artisanUpdates' => $artisanUpdates,
        ]);
    }

    public function show($id)
    {
        return view('store.transactionhistory_detail', [
            'id' => $id
        ]);
    }

    
}