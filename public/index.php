<?php

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;

// Include the composer autoloader
require dirname(__FILE__, 2) . '/bootstrap/app.php';

SimpleRouter::enableMultiRouteRendering(false);

// Include routes
require ROUTES_PATH . '/api.php';

try {
    SimpleRouter::start();
} catch (NotFoundHttpException) {

    // Display custom 404 page
    http_response_code(404);

    if (request()->isFormatAccepted('application/json')) {
        response()->httpCode(404)->json([
            'error' => true,
            'message' => $e->getMessage()
        ]);
    }

}