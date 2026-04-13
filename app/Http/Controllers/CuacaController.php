<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CuacaController extends Controller
{
    public function index(Request $request)
    {
        $lat = (float) $request->query('lat', env('WEATHER_LAT', -4.5536));
        $lon = (float) $request->query('lon', env('WEATHER_LON', 136.8894));
        $timezone = (string) $request->query('tz', env('WEATHER_TIMEZONE', 'Asia/Jayapura'));
        $days = (int) $request->query('days', env('WEATHER_DAYS', 3));
        $days = max(1, min(7, $days));

        $locationName = (string) $request->query('name', env('WEATHER_LOCATION_NAME', 'Timika Jaya'));
        $locationSub = (string) $request->query('sub', env('WEATHER_LOCATION_SUB', 'Kabupaten Mimika'));
        $timezoneLabel = (string) $request->query('tz_label', env('WEATHER_TIMEZONE_LABEL', 'WIT'));

        $cacheKey = 'cuaca:openmeteo:'.$lat.':'.$lon.':'.$timezone.':'.$days;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($lat, $lon, $timezone, $days) {
            $res = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lon,
                'timezone' => $timezone,
                'forecast_days' => $days,
                'current' => 'temperature_2m,relative_humidity_2m,precipitation,rain,weather_code',
                'hourly' => 'temperature_2m,precipitation_probability,weather_code,soil_moisture_0_to_1cm',
                'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_sum,rain_sum,weather_code,sunrise,sunset',
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
        $hourly = (array) ($data['json']['hourly'] ?? []);

        $now = Carbon::now($timezone);
        $sunsetToday = $this->firstOrNull((array) ($daily['sunset'] ?? []));
        $sunsetAt = $sunsetToday ? Carbon::parse($sunsetToday, $timezone) : null;

        $soilMoistureNow = $this->getCurrentHourlyValue($hourly, $timezone, $now, 'soil_moisture_0_to_1cm');

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
                'icon' => $this->weatherIcon((int) $codes[$i]),
            ];
        }

        $todayMax = $forecast[0]['temp_max'] ?? null;
        $todayMin = $forecast[0]['temp_min'] ?? null;

        $hourlyItems = $this->buildHourlyItems($hourly, $timezone, $now, $sunsetAt);

        $recommendations = $this->recommendations($forecast, (float) ($current['precipitation'] ?? 0));

        return view('cuaca.index', [
            'lat' => $lat,
            'lon' => $lon,
            'timezone' => $timezone,
            'timezoneLabel' => $timezoneLabel,
            'days' => $days,
            'locationName' => $locationName,
            'locationSub' => $locationSub,
            'ok' => (bool) ($data['ok'] ?? false),
            'error' => $data['error'] ?? null,
            'now' => $now,
            'current' => [
                'temp_c' => $current['temperature_2m'] ?? null,
                'humidity' => $current['relative_humidity_2m'] ?? null,
                'precip_mm' => $current['precipitation'] ?? null,
                'rain_mm' => $current['rain'] ?? null,
                'soil_moisture_0_1' => $soilMoistureNow,
                'code' => $current['weather_code'] ?? null,
                'desc' => $this->weatherDescription((int) ($current['weather_code'] ?? 0)),
                'icon' => $this->weatherIcon((int) ($current['weather_code'] ?? 0)),
            ],
            'forecast' => $forecast,
            'todayMax' => $todayMax,
            'todayMin' => $todayMin,
            'hourlyItems' => $hourlyItems,
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

    private function weatherIcon(int $code): string
    {
        return match (true) {
            $code === 0 => 'bi-sun-fill',
            in_array($code, [1, 2], true) => 'bi-cloud-sun-fill',
            $code === 3 => 'bi-cloud-fill',
            in_array($code, [45, 48], true) => 'bi-cloud-fog2-fill',
            in_array($code, [51, 53, 55, 56, 57], true) => 'bi-cloud-drizzle-fill',
            in_array($code, [61, 63, 65, 66, 67], true) => 'bi-cloud-rain-fill',
            in_array($code, [80, 81, 82], true) => 'bi-cloud-rain-heavy-fill',
            in_array($code, [71, 73, 75, 77, 85, 86], true) => 'bi-snow',
            in_array($code, [95, 96, 99], true) => 'bi-cloud-lightning-rain-fill',
            default => 'bi-cloud-fill',
        };
    }

    private function buildHourlyItems(array $hourly, string $timezone, Carbon $now, ?Carbon $sunsetAt): array
    {
        $times = (array) ($hourly['time'] ?? []);
        $temps = (array) ($hourly['temperature_2m'] ?? []);
        $pops = (array) ($hourly['precipitation_probability'] ?? []);
        $codes = (array) ($hourly['weather_code'] ?? []);

        $count = min(count($times), count($temps), count($pops), count($codes));
        if ($count === 0) {
            return [];
        }

        $startIndex = 0;
        for ($i = 0; $i < $count; $i++) {
            $t = Carbon::parse($times[$i], $timezone);
            if ($t->greaterThanOrEqualTo($now->copy()->subMinutes(30))) {
                $startIndex = $i;
                break;
            }
        }

        $items = [];
        $limit = min($count, $startIndex + 6);
        for ($i = $startIndex; $i < $limit; $i++) {
            $t = Carbon::parse($times[$i], $timezone);
            $items[] = [
                'type' => 'hour',
                'time' => $t,
                'label' => $i === $startIndex ? 'Sekarang' : $t->format('H'),
                'temp' => $temps[$i],
                'pop' => $pops[$i] ?? null,
                'code' => $codes[$i],
                'icon' => $this->weatherIcon((int) $codes[$i]),
            ];
        }

        if ($sunsetAt) {
            $items[] = [
                'type' => 'sunset',
                'time' => $sunsetAt,
                'label' => $sunsetAt->format('H.i'),
                'temp' => null,
                'pop' => null,
                'code' => null,
                'icon' => 'bi-sunset-fill',
                'text' => 'Terbenam',
            ];
        }

        usort($items, fn ($a, $b) => $a['time']->getTimestamp() <=> $b['time']->getTimestamp());

        return $items;
    }

    private function getCurrentHourlyValue(array $hourly, string $timezone, Carbon $now, string $key): mixed
    {
        $times = (array) ($hourly['time'] ?? []);
        $values = (array) ($hourly[$key] ?? []);

        $count = min(count($times), count($values));
        if ($count === 0) {
            return null;
        }

        $index = 0;
        for ($i = 0; $i < $count; $i++) {
            $t = Carbon::parse($times[$i], $timezone);
            if ($t->greaterThanOrEqualTo($now->copy()->subMinutes(30))) {
                $index = $i;
                break;
            }
        }

        return $values[$index] ?? null;
    }

    private function firstOrNull(array $arr): mixed
    {
        return count($arr) > 0 ? $arr[0] : null;
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
