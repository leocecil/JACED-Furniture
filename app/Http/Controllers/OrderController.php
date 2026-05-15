<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
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

        $provinces = [
            ['code' => '35', 'name' => 'Jawa Timur'],
            ['code' => '31', 'name' => 'DKI Jakarta'],
            ['code' => '32', 'name' => 'Jawa Barat'],
        ];

        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = 150.0;
        $tax = $subtotal * 0.0836;
        $total = $subtotal + $shipping + $tax;

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