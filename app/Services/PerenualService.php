<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PerenualService
{
    public function searchSpecies(string $query, int $page = 1): array
    {
        $key = config('services.perenual.key');
        $baseUrl = rtrim((string) config('services.perenual.base_url'), '/');

        if (! $key || ! $baseUrl) {
            throw new RuntimeException('Perenual API is not configured.');
        }

        $response = Http::timeout(10)->get($baseUrl.'/species-list', [
            'key' => $key,
            'q' => $query,
            'page' => $page,
        ]);

        if (! $response->ok()) {
            throw new RuntimeException('Perenual API request failed.');
        }

        return $response->json() ?? [];
    }

    public function normalizeList(array $payload): array
    {
        $data = $payload['data'] ?? [];
        $results = [];

        foreach ($data as $row) {
            $image = $row['default_image'] ?? [];
            $imageUrl = $image['thumbnail'] ?? $image['small_url'] ?? $image['regular_url'] ?? $image['medium_url'] ?? $image['original_url'] ?? null;

            $results[] = [
                'id' => (string) ($row['id'] ?? ''),
                'name' => $row['common_name'] ?? ($row['scientific_name'][0] ?? null) ?? 'Unknown',
                'scientific_name' => $row['scientific_name'][0] ?? null,
                'image_url' => $imageUrl,
            ];
        }

        return $results;
    }
}
