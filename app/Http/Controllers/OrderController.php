<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
<<<<<<< HEAD
    //
    public function index()
    {
        // Data dummy untuk simulasi tampilan di Screenshot 2026-05-14 151311.jpg
        $orders = [
            [
                'id' => '#ORD-8821',
                'customer' => 'Eleanor Hemingway',
                'email' => 'eleanor@studio.com',
                'date' => 'Oct 14, 2023',
                'status' => 'PROCESSING',
                'amount' => '$4,850.00'
            ],
            [
                'id' => '#ORD-8819',
                'customer' => 'Arthur Miller',
                'email' => 'arthur.m@designhouse.co',
                'date' => 'Oct 12, 2023',
                'status' => 'DELIVERED',
                'amount' => '$12,200.00'
            ],
            // Kamu bisa menambah data lainnya di sini
        ];

        return view('pages.orders.index', compact('orders'));
=======
    public function checkout() {
        // ========================================
        // DARI DATABASE (uncomment kalau DB sudah siap)
        // ========================================
        // $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        // $items = $cartItems->map(fn($c) => [
        //     'name'    => $c->product->name,
        //     'variant' => $c->product->variant,
        //     'qty'     => $c->quantity,
        //     'price'   => $c->product->price,
        //     'image'   => $c->product->image_url,
        // ])->toArray();

        // ========================================
        // HARDCODE / DUMMY (comment kalau DB sudah siap)
        // ========================================
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

        $paymentMethods = [
            ['value' => 'qris',            'label' => 'QRIS'],
            ['value' => 'virtual_account', 'label' => 'Virtual Account'],
            ['value' => 'credit_card',     'label' => 'Kartu Kredit / Debit'],
            ['value' => 'ovo',             'label' => 'OVO'],
            ['value' => 'dana',            'label' => 'DANA'],
        ];

        $banks = [
            ['value' => 'bca',     'name' => 'BCA'],
            ['value' => 'mandiri', 'name' => 'Mandiri'],
            ['value' => 'bni',     'name' => 'BNI'],
            ['value' => 'bri',     'name' => 'BRI'],
            ['value' => 'permata', 'name' => 'Permata Bank'],
            ['value' => 'cimb',    'name' => 'CIMB Niaga'],
            ['value' => 'seabank', 'name' => 'SeaBank'],
            ['value' => 'bsi',     'name' => 'BSI'],
            ['value' => 'danamon', 'name' => 'Danamon'],
            ['value' => 'saqu',    'name' => 'Bank Saqu'],
        ];

        $provinces = [
            ['code' => '11', 'name' => 'Aceh'],
            ['code' => '12', 'name' => 'Sumatera Utara'],
            ['code' => '13', 'name' => 'Sumatera Barat'],
            ['code' => '14', 'name' => 'Riau'],
            ['code' => '15', 'name' => 'Jambi'],
            ['code' => '16', 'name' => 'Sumatera Selatan'],
            ['code' => '17', 'name' => 'Bengkulu'],
            ['code' => '18', 'name' => 'Lampung'],
            ['code' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['code' => '21', 'name' => 'Kepulauan Riau'],
            ['code' => '31', 'name' => 'DKI Jakarta'],
            ['code' => '32', 'name' => 'Jawa Barat'],
            ['code' => '33', 'name' => 'Jawa Tengah'],
            ['code' => '34', 'name' => 'DI Yogyakarta'],
            ['code' => '35', 'name' => 'Jawa Timur'],
            ['code' => '36', 'name' => 'Banten'],
            ['code' => '51', 'name' => 'Bali'],
            ['code' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['code' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['code' => '61', 'name' => 'Kalimantan Barat'],
            ['code' => '62', 'name' => 'Kalimantan Tengah'],
            ['code' => '63', 'name' => 'Kalimantan Selatan'],
            ['code' => '64', 'name' => 'Kalimantan Timur'],
            ['code' => '65', 'name' => 'Kalimantan Utara'],
            ['code' => '71', 'name' => 'Sulawesi Utara'],
            ['code' => '72', 'name' => 'Sulawesi Tengah'],
            ['code' => '73', 'name' => 'Sulawesi Selatan'],
            ['code' => '74', 'name' => 'Sulawesi Tenggara'],
            ['code' => '75', 'name' => 'Gorontalo'],
            ['code' => '76', 'name' => 'Sulawesi Barat'],
            ['code' => '81', 'name' => 'Maluku'],
            ['code' => '82', 'name' => 'Maluku Utara'],
            ['code' => '91', 'name' => 'Papua Barat'],
            ['code' => '92', 'name' => 'Papua'],
        ];



        // ========================================
        // KALKULASI (tidak perlu diubah, works untuk keduanya)
        // ========================================
        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = 150.0;
        $tax      = $subtotal * 0.0836;
        $total    = $subtotal + $shipping + $tax;

        return view('store.checkout', compact('items', 'paymentMethods', 'banks', 'provinces', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function index(Request $request)
    {
        $filters = ['All', 'Unpaid', 'Processing', 'Shipped', 'Completed', 'Returns', 'Cancelled'];
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
            [
                'time'  => 'Oct 22, 10.00 AM',
                'title' => 'Sustainable Packaging Applied',
                'desc'  => 'Your order is secured using 100% recycled structural fiber panels.',
            ],
        ];


        return view('store.transaction_history', [
            'filters'      => $filters,
            'activeFilter' => $activeFilter,
            'artisanUpdates' => $artisanUpdates,
        ]);
    }

    public function show($id)
    {
        return view('store.transactionhistory_detail', ['id' => $id]);
>>>>>>> 5ed2eaa3fa822212430402ca80b56ca18e7b04f3
    }
}
