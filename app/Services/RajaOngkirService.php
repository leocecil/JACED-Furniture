<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    private string $apiKey;
    private string $baseUrl = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
    }

    // Step 1: Cari destination ID berdasarkan village + city name
    public function searchDestination(string $villageName, string $cityName): ?int
    {
        $keyword = $villageName . ' ' . $cityName;

        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->get("{$this->baseUrl}/destination/domestic-destination", [
            'search' => $keyword,
            'limit'  => 10,
            'offset' => 0,
        ]);

        if (!$response->successful()) return null;

        $data = $response->json('data', []);

        if (empty($data)) return null;

        // Ambil hasil yang city_name-nya cocok
        $cityNameClean = strtoupper(trim($cityName));
        // Hapus prefix KOTA / KABUPATEN kalau ada
        $cityNameClean = str_replace(['KOTA ', 'KABUPATEN '], '', $cityNameClean);

        foreach ($data as $item) {
            $itemCity = strtoupper($item['city_name']);
            if (str_contains($itemCity, $cityNameClean) || str_contains($cityNameClean, $itemCity)) {
                return $item['id'];
            }
        }

        // Kalau tidak ada yang cocok, ambil hasil pertama
        return $data[0]['id'];
    }

    // Step 2: Hitung ongkir
    public function calculateCost(int $destinationId, int $weightGram, string $courier = 'jne'): array
    {
        $originId = config('services.rajaongkir.origin_id');

        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->asForm()->post("{$this->baseUrl}/calculate/domestic-cost", [
            'origin'      => $originId,
            'destination' => $destinationId,
            'weight'      => $weightGram,
            'courier'     => $courier,
            'price'       => 'lowest',
        ]);

        if (!$response->successful()) return [];

        return $response->json('data', []);
    }
}