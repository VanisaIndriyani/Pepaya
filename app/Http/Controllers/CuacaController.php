<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CuacaController extends Controller
{
    public function index(Request $request)
    {
        $lat = (float) $request->query('lat', env('WEATHER_LAT', -6.2));
        $lon = (float) $request->query('lon', env('WEATHER_LON', 106.8));
        $timezone = (string) $request->query('tz', env('WEATHER_TIMEZONE', 'Asia/Jakarta'));
        $days = (int) $request->query('days', env('WEATHER_DAYS', 3));
        $days = max(1, min(7, $days));

        $cacheKey = 'cuaca:openmeteo:'.$lat.':'.$lon.':'.$timezone.':'.$days;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($lat, $lon, $timezone, $days) {
            $res = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lon,
                'timezone' => $timezone,
                'forecast_days' => $days,
                'current' => 'temperature_2m,relative_humidity_2m,precipitation,rain,weather_code',
                'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_sum,rain_sum,weather_code',
            ]);

            return [
                'ok' => $res->ok(),
                'status' => $res->status(),
                'json' => $res->json(),
                'error' => $res->ok() ? null : ($res->json()['reason'] ?? $res->body()),
            ];
        });

        $current = (array) ($data['json']['current'] ?? []);
        $daily = (array) ($data['json']['daily'] ?? []);

        $forecast = [];
        $dates = (array) ($daily['time'] ?? []);
        $max = (array) ($daily['temperature_2m_max'] ?? []);
        $min = (array) ($daily['temperature_2m_min'] ?? []);
        $precip = (array) ($daily['precipitation_sum'] ?? []);
        $rain = (array) ($daily['rain_sum'] ?? []);
        $codes = (array) ($daily['weather_code'] ?? []);

        $count = min(count($dates), count($max), count($min), count($precip), count($rain), count($codes));
        for ($i = 0; $i < $count; $i++) {
            $forecast[] = [
                'date' => $dates[$i],
                'temp_max' => $max[$i],
                'temp_min' => $min[$i],
                'precip_mm' => $precip[$i],
                'rain_mm' => $rain[$i],
                'code' => $codes[$i],
                'desc' => $this->weatherDescription((int) $codes[$i]),
            ];
        }

        $recommendations = $this->recommendations($forecast, (float) ($current['precipitation'] ?? 0));

        return view('cuaca.index', [
            'lat' => $lat,
            'lon' => $lon,
            'timezone' => $timezone,
            'days' => $days,
            'ok' => (bool) ($data['ok'] ?? false),
            'error' => $data['error'] ?? null,
            'current' => [
                'temp_c' => $current['temperature_2m'] ?? null,
                'humidity' => $current['relative_humidity_2m'] ?? null,
                'precip_mm' => $current['precipitation'] ?? null,
                'rain_mm' => $current['rain'] ?? null,
                'code' => $current['weather_code'] ?? null,
                'desc' => $this->weatherDescription((int) ($current['weather_code'] ?? 0)),
            ],
            'forecast' => $forecast,
            'recommendations' => $recommendations,
        ]);
    }

    private function weatherDescription(int $code): string
    {
        return match (true) {
            $code === 0 => 'Cerah',
            $code === 1 => 'Sebagian cerah',
            $code === 2 => 'Berawan',
            $code === 3 => 'Mendung',
            in_array($code, [45, 48], true) => 'Berkabut',
            in_array($code, [51, 53, 55], true) => 'Gerimis',
            in_array($code, [56, 57], true) => 'Gerimis membeku',
            in_array($code, [61, 63, 65], true) => 'Hujan',
            in_array($code, [66, 67], true) => 'Hujan membeku',
            in_array($code, [71, 73, 75], true) => 'Salju',
            $code === 77 => 'Butiran salju',
            in_array($code, [80, 81, 82], true) => 'Hujan deras',
            in_array($code, [85, 86], true) => 'Salju deras',
            $code === 95 => 'Badai petir',
            in_array($code, [96, 99], true) => 'Badai petir + hujan es',
            default => 'Tidak diketahui',
        };
    }

    private function recommendations(array $forecast, float $currentPrecipMm): array
    {
        $warnings = [];

        if ($currentPrecipMm >= 5) {
            $warnings[] = 'Sedang/baru saja terjadi hujan, pertimbangkan tunda pemupukan dan penyemprotan.';
        }

        $heavyRainDays = array_filter($forecast, fn ($d) => (float) ($d['precip_mm'] ?? 0) >= 20);
        if (count($heavyRainDays) > 0) {
            $warnings[] = 'Perkiraan hujan lebat dalam beberapa hari ke depan, prioritaskan pengamanan lahan dan drainase.';
        }

        $suggestions = [
            'Waktu tanam terbaik biasanya saat curah hujan tidak terlalu tinggi namun kelembaban cukup stabil.',
            'Pemupukan dan penyemprotan lebih baik dilakukan saat tidak hujan untuk menghindari larutan terbuang.',
        ];

        return [
            'warnings' => $warnings,
            'suggestions' => $suggestions,
        ];
    }
}

