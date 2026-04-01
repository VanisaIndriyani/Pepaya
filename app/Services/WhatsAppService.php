<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsAppService
{
    public function send(string $nomor, string $pesan): array
    {
        $url = config('services.whatsapp.url', env('WA_GATEWAY_URL', 'https://example.com/dummy-wa'));
        $token = config('services.whatsapp.token', env('WA_GATEWAY_TOKEN'));

        $nomor = $this->normalizePhone($nomor);

        try {
            $req = Http::timeout(10);
            $res = $this->sendRequest($req, $url, $token, $nomor, $pesan);
            $parsed = $this->parseGatewayResponse($url, $res->status(), $res->body(), $res->json());

            return [
                'success' => $parsed['success'],
                'status' => $parsed['success'] ? 'terkirim' : 'gagal',
                'response' => $parsed['response'],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'status' => 'gagal',
                'response' => $e->getMessage(),
            ];
        }
    }

    private function sendRequest($req, string $url, ?string $token, string $nomor, string $pesan)
    {
        if (Str::contains($url, 'fonnte.com')) {
            if ($token) {
                $req = $req->withHeaders(['Authorization' => $token]);
            }

            return $req->asForm()->post($url, [
                'target' => $nomor,
                'message' => $pesan,
                'countryCode' => '62',
            ]);
        }

        if ($token) {
            $req = $req->withToken($token);
        }

        return $req->post($url, [
            'to' => $nomor,
            'message' => $pesan,
        ]);
    }

    private function parseGatewayResponse(string $url, int $status, string $rawBody, $json): array
    {
        if (Str::contains($url, 'fonnte.com')) {
            $success = false;

            if (is_array($json)) {
                $success = ($json['status'] ?? null) === true;
            }

            if (! $success) {
                $success = $status >= 200 && $status < 300 && Str::contains(Str::lower($rawBody), 'success');
            }

            $response = is_array($json)
                ? json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : $rawBody;

            return [
                'success' => $success,
                'response' => $response ?: $rawBody,
            ];
        }

        return [
            'success' => $status >= 200 && $status < 300,
            'response' => $rawBody,
        ];
    }

    private function normalizePhone(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9+]/', '', $nomor) ?: $nomor;

        if (Str::startsWith($nomor, '+')) {
            $nomor = substr($nomor, 1);
        }

        if (Str::startsWith($nomor, '0')) {
            $nomor = '62'.substr($nomor, 1);
        }

        return $nomor;
    }
}
