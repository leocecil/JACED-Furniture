<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
    public function index()
    {
        // Data dummy yang disesuaikan dengan isi Screenshot 2026-05-14 200229.jpg
        $inventoryItems = [
            [
                'name' => 'Sculptural Lounge',
                'price' => '$4,250',
                'category' => 'SEATING',
                'material' => 'PREMIUM WALNUT',
                'available_units' => '02',
                'status' => 'LOW STOCK',
                'status_bg' => '#f8d7da',
                'status_color' => '#721c24',
                'image' => 'https://via.placeholder.com/400x300' // Ganti dengan path image asli nanti
            ],
            [
                'name' => 'Solid Oak Plenum',
                'price' => '$3,800',
                'category' => 'TABLES',
                'material' => 'EUROPEAN OAK',
                'available_units' => '12',
                'status' => 'IN DELIVERY',
                'status_bg' => '#cfe2ff',
                'status_color' => '#084298',
                'image' => 'https://via.placeholder.com/400x300'
            ],
            [
                'name' => 'Linear Credenza',
                'price' => '$2,400',
                'category' => 'STORAGE',
                'material' => 'STEEL & MAHOGANY',
                'available_units' => '08',
                'status' => 'AVAILABLE',
                'status_bg' => '#fff3cd',
                'status_color' => '#856404',
                'image' => 'https://via.placeholder.com/400x300'
            ],
            [
                'name' => 'Carrara Pivot',
                'price' => '$1,850',
                'category' => 'TABLES',
                'material' => 'ITALIAN MARBLE',
                'available_units' => '03',
                'status' => 'LOW STOCK',
                'status_bg' => '#f8d7da',
                'status_color' => '#721c24',
                'image' => 'https://via.placeholder.com/400x300'
            ],
            [
                'name' => 'Velvet Horizon',
                'price' => '$5,600',
                'category' => 'SEATING',
                'material' => 'MOHAIR VELVET',
                'available_units' => '05',
                'status' => 'AVAILABLE',
                'status_bg' => '#fff3cd',
                'status_color' => '#856404',
                'image' => 'https://via.placeholder.com/400x300'
            ],
            [
                'name' => 'Smoked Oak Dresser',
                'price' => '$3,200',
                'category' => 'STORAGE',
                'material' => 'SMOKED OAK',
                'available_units' => '15',
                'status' => 'IN DELIVERY',
                'status_bg' => '#cfe2ff',
                'status_color' => '#084298',
                'image' => 'https://via.placeholder.com/400x300'
            ],
        ];

        // Mengirimkan data ke view pages/inventory/index.blade.php
        return view('pages.inventory.index', compact('inventoryItems'));
    }
}
