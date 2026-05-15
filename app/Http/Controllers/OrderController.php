<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
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
    }
}
