<?php

namespace App\Http\Controllers;

use App\Services\PerenualService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ExternalPlantController extends Controller
{
    public function search(Request $request, PerenualService $service)
    {
        $query = trim((string) $request->get('q', ''));
        $page = (int) $request->get('page', 1);

        if ($query === '') {
            return response()->json(['data' => []]);
        }

        $cacheKey = sprintf('perenual.search.%s.%d', md5($query), $page);

        try {
            $payload = Cache::remember($cacheKey, 3600, function () use ($service, $query, $page) {
                return $service->searchSpecies($query, $page);
            });
        } catch (Throwable $e) {
            return response()->json(['error' => 'External API unavailable.'], 503);
        }

        return response()->json([
            'data' => $service->normalizeList($payload),
            'current_page' => $payload['current_page'] ?? $page,
            'last_page' => $payload['last_page'] ?? null,
        ]);
    }
}
