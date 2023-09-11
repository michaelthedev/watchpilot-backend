<?php
// +----------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : michael@logad.net
// | @date          : 10 Sep, 2023 12:11 PM
// +----------------------------------------------------+

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;

// Include the composer autoloader
require dirname(__FILE__, 2) . '/app/bootstrap.php';

// Include routes
require ROUTES_PATH . '/api.php';

SimpleRouter::enableMultiRouteRendering(false);
try {
    SimpleRouter::start();
} catch (NotFoundHttpException) {
    // Display custom 404 page
    response()->httpCode(404)->json([
        'error' => true,
        'message' => 'Route Not Found'
    ]);
}
