<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $cacheKey = 'destination_' . md5(strtolower(trim($villageName)) . '|' . strtolower(trim($cityName)));
        
        $result = Cache::remember($cacheKey, 60 * 60 * 24, function() use ($villageName, $cityName) {
            try {
                $keyword  = $villageName . ' ' . $cityName;
                $response = Http::timeout(5)->withHeaders(['key' => $this->apiKey])
                                ->get("{$this->baseUrl}/destination/domestic-destination", [
                                    'search' => $keyword,
                                    'limit'  => 10,
                                    'offset' => 0,
                                ]);
                if (!$response->successful()) return 'NOT_FOUND';
                $data = $response->json('data', []);
                if (empty($data)) return 'NOT_FOUND';

                $cityNameClean = strtoupper(str_replace(['KOTA ', 'KABUPATEN '], '', trim($cityName)));
                foreach ($data as $item) {
                    $itemCity = strtoupper($item['city_name']);
                    if (str_contains($itemCity, $cityNameClean) || str_contains($cityNameClean, $itemCity)) {
                        return $item['id'];
                    }
                }
                return $data[0]['id'];
            } catch (\Exception $e) {
                Log::warning('RajaOngkir searchDestination failed', ['error' => $e->getMessage()]);
                return 'NOT_FOUND';
            }
        });

        // Kembalikan null ke caller, tapi 'NOT_FOUND' tetap ke-cache
        return $result === 'NOT_FOUND' ? null : $result;
    }

    // Step 2: Hitung ongkir
    public function calculateCost(int $destinationId, int $weightGram, string $courier = 'jne'): array
    {
        $originId = config('services.rajaongkir.origin_id');
        $cacheKey = 'rajaongkir:cost:' . md5("{$originId}|{$destinationId}|{$weightGram}|{$courier}");

        $result = Cache::remember($cacheKey, 60 * 60 * 6, function() use ($originId, $destinationId, $weightGram, $courier) {
            try {
                $response = Http::timeout(5)->withHeaders(['key' => $this->apiKey])
                                ->asForm()
                                ->post("{$this->baseUrl}/calculate/domestic-cost", [
                                    'origin'      => $originId,
                                    'destination' => $destinationId,
                                    'weight'      => $weightGram,
                                    'courier'     => $courier,
                                    'price'       => 'lowest',
                                ]);
                if (!$response->successful()) return 'API_ERROR';
                $data = $response->json('data', []);
                return empty($data) ? 'API_ERROR' : $data;
            } catch (\Exception $e) {
                Log::warning('RajaOngkir calculateCost failed', [
                    'courier' => $courier,
                    'error'   => $e->getMessage()
                ]);
                return 'API_ERROR';
            }
        });

        return $result === 'API_ERROR' ? [] : $result;
    }
}