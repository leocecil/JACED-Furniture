<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Budi Santoso',       'email' => 'budi.santoso@gmail.com',       'phone_number' => '081312345601'],
            ['name' => 'Sari Rahayu',         'email' => 'sari.rahayu@gmail.com',         'phone_number' => '081312345602'],
            ['name' => 'Andi Prasetyo',       'email' => 'andi.prasetyo@gmail.com',       'phone_number' => '081312345603'],
            ['name' => 'Dewi Kusuma',         'email' => 'dewi.kusuma@yahoo.com',         'phone_number' => '081312345604'],
            ['name' => 'Riko Firmansyah',     'email' => 'riko.firmansyah@gmail.com',     'phone_number' => '081312345605'],
            ['name' => 'Putri Maharani',      'email' => 'putri.maharani@gmail.com',      'phone_number' => '081312345606'],
            ['name' => 'Hendra Wijaya',       'email' => 'hendra.wijaya@gmail.com',       'phone_number' => '081312345607'],
            ['name' => 'Lestari Anggraini',   'email' => 'lestari.anggraini@gmail.com',   'phone_number' => '081312345608'],
            ['name' => 'Fajar Nugroho',       'email' => 'fajar.nugroho@gmail.com',       'phone_number' => '081312345609'],
            ['name' => 'Maya Suhartini',      'email' => 'maya.suhartini@yahoo.com',      'phone_number' => '081312345610'],
            ['name' => 'Rizky Hidayat',       'email' => 'rizky.hidayat@gmail.com',       'phone_number' => '081312345611'],
            ['name' => 'Nisa Permata',        'email' => 'nisa.permata@gmail.com',        'phone_number' => '081312345612'],
            ['name' => 'Agus Setiawan',       'email' => 'agus.setiawan@gmail.com',       'phone_number' => '081312345613'],
            ['name' => 'Indah Lestari',       'email' => 'indah.lestari@gmail.com',       'phone_number' => '081312345614'],
            ['name' => 'Dani Irawan',         'email' => 'dani.irawan@gmail.com',         'phone_number' => '081312345615'],
            ['name' => 'Fitri Handayani',     'email' => 'fitri.handayani@gmail.com',     'phone_number' => '081312345616'],
            ['name' => 'Wahyu Purnomo',       'email' => 'wahyu.purnomo@gmail.com',       'phone_number' => '081312345617'],
            ['name' => 'Ayu Wulandari',       'email' => 'ayu.wulandari@yahoo.com',       'phone_number' => '081312345618'],
            ['name' => 'Bayu Saputra',        'email' => 'bayu.saputra@gmail.com',        'phone_number' => '081312345619'],
            ['name' => 'Rini Sulistyowati',   'email' => 'rini.sulistyowati@gmail.com',   'phone_number' => '081312345620'],
            ['name' => 'Teguh Santoso',       'email' => 'teguh.santoso@gmail.com',       'phone_number' => '081312345621'],
            ['name' => 'Citra Permatasari',   'email' => 'citra.permatasari@gmail.com',   'phone_number' => '081312345622'],
            ['name' => 'Imam Syafii',         'email' => 'imam.syafii@gmail.com',         'phone_number' => '081312345623'],
            ['name' => 'Eka Suryani',         'email' => 'eka.suryani@gmail.com',         'phone_number' => '081312345624'],
            ['name' => 'Yusuf Hakim',         'email' => 'yusuf.hakim@gmail.com',         'phone_number' => '081312345625'],
            ['name' => 'Ratna Dewi',          'email' => 'ratna.dewi@yahoo.com',          'phone_number' => '081312345626'],
            ['name' => 'Surya Dinata',        'email' => 'surya.dinata@gmail.com',        'phone_number' => '081312345627'],
            ['name' => 'Wulan Sari',          'email' => 'wulan.sari@gmail.com',          'phone_number' => '081312345628'],
            ['name' => 'Arief Budiman',       'email' => 'arief.budiman@gmail.com',       'phone_number' => '081312345629'],
            ['name' => 'Novi Andriani',       'email' => 'novi.andriani@gmail.com',       'phone_number' => '081312345630'],
            ['name' => 'Rudi Hartono',        'email' => 'rudi.hartono@gmail.com',        'phone_number' => '081312345631'],
            ['name' => 'Lia Amelia',          'email' => 'lia.amelia@gmail.com',          'phone_number' => '081312345632'],
            ['name' => 'Dedi Kurniawan',      'email' => 'dedi.kurniawan@gmail.com',      'phone_number' => '081312345633'],
            ['name' => 'Sinta Rahmawati',     'email' => 'sinta.rahmawati@yahoo.com',     'phone_number' => '081312345634'],
            ['name' => 'Fandi Ahmad',         'email' => 'fandi.ahmad@gmail.com',         'phone_number' => '081312345635'],
            ['name' => 'Nurul Hidayah',       'email' => 'nurul.hidayah@gmail.com',       'phone_number' => '081312345636'],
            ['name' => 'Gilang Ramadan',      'email' => 'gilang.ramadan@gmail.com',      'phone_number' => '081312345637'],
            ['name' => 'Tika Oktavia',        'email' => 'tika.oktavia@gmail.com',        'phone_number' => '081312345638'],
            ['name' => 'Hendro Susilo',       'email' => 'hendro.susilo@gmail.com',       'phone_number' => '081312345639'],
            ['name' => 'Mega Puspita',        'email' => 'mega.puspita@gmail.com',        'phone_number' => '081312345640'],
            ['name' => 'Iwan Setiadi',        'email' => 'iwan.setiadi@gmail.com',        'phone_number' => '081312345641'],
            ['name' => 'Yeni Marlina',        'email' => 'yeni.marlina@yahoo.com',        'phone_number' => '081312345642'],
            ['name' => 'Dimas Prabowo',       'email' => 'dimas.prabowo@gmail.com',       'phone_number' => '081312345643'],
            ['name' => 'Hani Safitri',        'email' => 'hani.safitri@gmail.com',        'phone_number' => '081312345644'],
            ['name' => 'Erwin Prasetya',      'email' => 'erwin.prasetya@gmail.com',      'phone_number' => '081312345645'],
            ['name' => 'Dina Marlina',        'email' => 'dina.marlina@gmail.com',        'phone_number' => '081312345646'],
            ['name' => 'Yoga Pratama',        'email' => 'yoga.pratama@gmail.com',        'phone_number' => '081312345647'],
            ['name' => 'Sri Mulyani',         'email' => 'sri.mulyani@gmail.com',         'phone_number' => '081312345648'],
            ['name' => 'Kevin Christanto',    'email' => 'kevin.christanto@gmail.com',    'phone_number' => '081312345649'],
            ['name' => 'Jessica Tanaka',      'email' => 'jessica.tanaka@gmail.com',      'phone_number' => '081312345650'],
        ];

        $users = [];
        foreach ($customers as $customer) {
            $users[] = [
                'name'              => $customer['name'],
                'email'             => $customer['email'],
                'phone_number'      => $customer['phone_number'],
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
                'is_admin'          => false,
                'avatar'            => 'image/avatars/default_avatar.png', // default avatar
                'current_points'    => rand(0, 500),
                'accumulated_points'=> rand(0, 2000),
                'created_at'        => now()->subDays(rand(30, 365)),
                'updated_at'        => now(),
            ];
        }

        DB::table('users')->insert($users);

        // Insert customer roles for IDs 7–56
        $roles = [];
        for ($id = 7; $id <= 56; $id++) {
            $roles[] = [
                'user_id'    => $id,
                'role'       => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('user_roles')->insert($roles);
    }
}