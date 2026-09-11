<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    public function getUsdToMxnRate()
    {
        $cacheKey = 'usd_mxn_fastforex_v1';

        return Cache::remember($cacheKey, now()->addHours(1), function () {
            
            $apiKey = env('EXCHANGE_RATE_API_KEY');
            Log::info("Consultando FastForex API...");
            
            try {
                // Petición HTTP adaptada a FastForex con el Header X-API-Key
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'X-API-Key' => $apiKey,
                        'Accept' => 'application/json',
                    ])
                    ->get('https://api.fastforex.io/fetch-one', [
                        'from' => 'USD',
                        'to' => 'MXN',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // FastForex devuelve el tipo de cambio típicamente en $data['result']['MXN'] o $data['rate']
                    $rate = $data['result']['MXN'] ?? $data['rate'] ?? null;

                    if ($rate) {
                        Log::info("¡Tipo de cambio obtenido con éxito de FastForex!: {$rate}");
                        return (float) $rate;
                    } else {
                        Log::error('FastForex respondió pero la estructura no contiene el valor esperado: ' . json_encode($data));
                    }
                } else {
                    Log::error('Error HTTP de FastForex. Código: ' . $response->status() . ' | Respuesta: ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Excepción de conexión con FastForex: ' . $e->getMessage());
            }

            Log::error('Valor de respaldo: 20');
            return 20.00;
        });
    }
}