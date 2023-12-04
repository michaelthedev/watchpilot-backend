<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\MediaService;

/**
 * API Controller
 *
 * Handles requests for /api/* routes
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class ApiController
{
    public function index(): void
    {
        $appName = config('app.name');
        response()->json([
            'error' => false,
            'message' => 'Welcome to '.$appName.' API'
        ]);
    }
}