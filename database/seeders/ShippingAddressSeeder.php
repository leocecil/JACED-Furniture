<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingAddressSeeder extends Seeder
{
    public function run(): void
    {
        // Real address pools per city, pulled directly from your indonesia_* tables
        // Structure: [province_code, province_name, city_code, city_name, district_code, district_name, village_code, village_name, postal_code]
        $addressPool = [
            // --- SURABAYA ---
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357804', 'WONOKROMO',  '3578041001', 'WONOKROMO',     '60243'],
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357804', 'WONOKROMO',  '3578041002', 'JAGIR',         '60244'],
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357804', 'WONOKROMO',  '3578041003', 'NGAGEL',        '60246'],
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357803', 'RUNGKUT',    '3578031001', 'KALIRUNGKUT',   '60293'],
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357803', 'RUNGKUT',    '3578031002', 'RUNGKUT KIDUL', '60293'],
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357803', 'RUNGKUT',    '3578031003', 'KEDUNG BARUK',  '60298'],
            ['35', 'JAWA TIMUR', '3578', 'KOTA SURABAYA', '357805', 'TEGALSARI',  '3578051001', 'TEGALSARI',     '60262'],
            // --- MALANG ---
            ['35', 'JAWA TIMUR', '3573', 'KOTA MALANG',   '357302', 'KLOJEN',          '3573021001', 'KLOJEN',         '65111'],
            ['35', 'JAWA TIMUR', '3573', 'KOTA MALANG',   '357302', 'KLOJEN',          '3573021002', 'RAMPALCELAKET',  '65111'],
            ['35', 'JAWA TIMUR', '3573', 'KOTA MALANG',   '357302', 'KLOJEN',          '3573021003', 'SAMAAN',         '65112'],
            ['35', 'JAWA TIMUR', '3573', 'KOTA MALANG',   '357305', 'LOWOKWARU',       '3573051001', 'LOWOKWARU',      '65141'],
            // --- BANDUNG ---
            ['32', 'JAWA BARAT', '3273', 'KOTA BANDUNG',  '327302', 'COBLONG',         '3273021001', 'CIPAGANTI',      '40131'],
            ['32', 'JAWA BARAT', '3273', 'KOTA BANDUNG',  '327302', 'COBLONG',         '3273021002', 'LEBAK GEDE',     '40132'],
            ['32', 'JAWA BARAT', '3273', 'KOTA BANDUNG',  '327302', 'COBLONG',         '3273021004', 'DAGO',           '40135'],
            ['32', 'JAWA BARAT', '3273', 'KOTA BANDUNG',  '327301', 'SUKASARI',        '3273011001', 'SUKAWARNA',      '40152'],
            // --- JAKARTA SELATAN ---
            ['31', 'DAERAH KHUSUS IBUKOTA JAKARTA', '3174', 'KOTA ADMINISTRASI JAKARTA SELATAN', '317401', 'TEBET',    '3174011001', 'TEBET TIMUR',   '12820'],
            ['31', 'DAERAH KHUSUS IBUKOTA JAKARTA', '3174', 'KOTA ADMINISTRASI JAKARTA SELATAN', '317401', 'TEBET',    '3174011002', 'TEBET BARAT',   '12810'],
            ['31', 'DAERAH KHUSUS IBUKOTA JAKARTA', '3174', 'KOTA ADMINISTRASI JAKARTA SELATAN', '317401', 'TEBET',    '3174011003', 'MENTENG DALAM', '12870'],
            ['31', 'DAERAH KHUSUS IBUKOTA JAKARTA', '3174', 'KOTA ADMINISTRASI JAKARTA SELATAN', '317402', 'SETIABUDI','3174021001', 'KARET',         '12920'],
            // --- SEMARANG ---
            ['33', 'JAWA TENGAH', '3374', 'KOTA SEMARANG', '337401', 'SEMARANG TENGAH', '3374011001', 'MIROTO',       '50134'],
            ['33', 'JAWA TENGAH', '3374', 'KOTA SEMARANG', '337401', 'SEMARANG TENGAH', '3374011002', 'BRUMBUNGAN',   '50135'],
            ['33', 'JAWA TENGAH', '3374', 'KOTA SEMARANG', '337401', 'SEMARANG TENGAH', '3374011003', 'JAGALAN',      '50613'],
            ['33', 'JAWA TENGAH', '3374', 'KOTA SEMARANG', '337402', 'SEMARANG UTARA',  '3374021001', 'BULU LOR',     '50176'],
            // --- MEDAN ---
            ['12', 'SUMATERA UTARA', '1271', 'KOTA MEDAN', '127101', 'MEDAN KOTA',     '1271011001', 'PASAR BARU',   '20212'],
            ['12', 'SUMATERA UTARA', '1271', 'KOTA MEDAN', '127101', 'MEDAN KOTA',     '1271011002', 'PUSAT PASAR',  '20212'],
            ['12', 'SUMATERA UTARA', '1271', 'KOTA MEDAN', '127103', 'MEDAN HELVETIA', '1271031001', 'HELVETIA',     '20124'],
            // --- MAKASSAR ---
            ['73', 'SULAWESI SELATAN', '7371', 'KOTA MAKASSAR', '737101', 'MARISO',      '7371011001', 'BONTORANNU',  '90126'],
            ['73', 'SULAWESI SELATAN', '7371', 'KOTA MAKASSAR', '737101', 'MARISO',      '7371011002', 'MATTOANGIN',  '90121'],
            ['73', 'SULAWESI SELATAN', '7371', 'KOTA MAKASSAR', '737103', 'MAKASSAR',    '7371031001', 'MARICAYA',    '90141'],
            // --- DENPASAR ---
            ['51', 'BALI', '5171', 'KOTA DENPASAR',  '517101', 'DENPASAR SELATAN', '5171011002', 'PEDUNGAN',    '80222'],
            ['51', 'BALI', '5171', 'KOTA DENPASAR',  '517101', 'DENPASAR SELATAN', '5171011003', 'SESETAN',     '80223'],
            ['51', 'BALI', '5171', 'KOTA DENPASAR',  '517102', 'DENPASAR TIMUR',   '5171021001', 'KESIMAN',     '80237'],
        ];

        $streetPrefixes = [
            'Jl. Mawar', 'Jl. Melati', 'Jl. Kenanga', 'Jl. Anggrek',
            'Jl. Flamboyan', 'Jl. Raya', 'Jl. Pahlawan', 'Jl. Merdeka',
            'Jl. Sudirman', 'Jl. Diponegoro', 'Jl. Gatot Subroto',
            'Jl. Ahmad Yani', 'Jl. Imam Bonjol', 'Jl. Veteran',
            'Jl. Pemuda', 'Jl. Soekarno Hatta', 'Gang Buntu',
            'Jl. Delima', 'Jl. Cempaka', 'Jl. Dahlia',
        ];

        // user_id 7–56 are our 50 customers
        // ~30 customers get 2 addresses, ~20 get 1 → total ~80 addresses
        $doubleAddressUsers = array_slice(range(7, 56), 0, 30); // first 30 get 2 addresses

        $addresses = [];
        $poolSize  = count($addressPool);
        $poolIndex = 0;

        for ($userId = 7; $userId <= 56; $userId++) {
            $hasTwo = in_array($userId, $doubleAddressUsers);
            $count  = $hasTwo ? 2 : 1;

            for ($i = 0; $i < $count; $i++) {
                $addr   = $addressPool[$poolIndex % $poolSize];
                $street = $streetPrefixes[array_rand($streetPrefixes)];
                $no     = rand(1, 150);
                $rt     = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
                $rw     = str_pad(rand(1, 6), 2, '0', STR_PAD_LEFT);

                $addresses[] = [
                    'user_id'        => $userId,
                    'receiver_name'  => $this->getCustomerName($userId),
                    'receiver_phone' => '08' . rand(100000000, 999999999),
                    'address_line1'  => "{$street} No. {$no} RT {$rt}/RW {$rw}",
                    'province_code'  => $addr[0],
                    'province_name'  => $addr[1],
                    'city_code'      => $addr[2],
                    'city_name'      => $addr[3],
                    'district_code'  => $addr[4],
                    'district_name'  => $addr[5],
                    'village_code'   => $addr[6],
                    'village_name'   => $addr[7],
                    'postal_code'    => $addr[8],
                    'created_at'     => now()->subDays(rand(10, 300)),
                    'updated_at'     => now(),
                ];

                $poolIndex++;
            }
        }

        // Insert in chunks to avoid hitting query size limits
        foreach (array_chunk($addresses, 20) as $chunk) {
            DB::table('shipping_address')->insert($chunk);
        }
    }

    private function getCustomerName(int $userId): string
    {
        $names = [
            7  => 'Budi Santoso',       8  => 'Sari Rahayu',
            9  => 'Andi Prasetyo',      10 => 'Dewi Kusuma',
            11 => 'Riko Firmansyah',    12 => 'Putri Maharani',
            13 => 'Hendra Wijaya',      14 => 'Lestari Anggraini',
            15 => 'Fajar Nugroho',      16 => 'Maya Suhartini',
            17 => 'Rizky Hidayat',      18 => 'Nisa Permata',
            19 => 'Agus Setiawan',      20 => 'Indah Lestari',
            21 => 'Dani Irawan',        22 => 'Fitri Handayani',
            23 => 'Wahyu Purnomo',      24 => 'Ayu Wulandari',
            25 => 'Bayu Saputra',       26 => 'Rini Sulistyowati',
            27 => 'Teguh Santoso',      28 => 'Citra Permatasari',
            29 => 'Imam Syafii',        30 => 'Eka Suryani',
            31 => 'Yusuf Hakim',        32 => 'Ratna Dewi',
            33 => 'Surya Dinata',       34 => 'Wulan Sari',
            35 => 'Arief Budiman',      36 => 'Novi Andriani',
            37 => 'Rudi Hartono',       38 => 'Lia Amelia',
            39 => 'Dedi Kurniawan',     40 => 'Sinta Rahmawati',
            41 => 'Fandi Ahmad',        42 => 'Nurul Hidayah',
            43 => 'Gilang Ramadan',     44 => 'Tika Oktavia',
            45 => 'Hendro Susilo',      46 => 'Mega Puspita',
            47 => 'Iwan Setiadi',       48 => 'Yeni Marlina',
            49 => 'Dimas Prabowo',      50 => 'Hani Safitri',
            51 => 'Erwin Prasetya',     52 => 'Dina Marlina',
            53 => 'Yoga Pratama',       54 => 'Sri Mulyani',
            55 => 'Kevin Christanto',   56 => 'Jessica Tanaka',
        ];

        return $names[$userId] ?? 'Customer';
    }
}