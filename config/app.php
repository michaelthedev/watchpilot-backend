<?php

return [
    'name' => 'WatchPilot',
    'url' => 'https://www.watchpilot.test',
    'timezone' => 'Africa/Lagos',
    'apiProvider' => 'tmdb',
    'providers' => [
        'tmdb' => [
            'apiKey' => $_ENV['TMDB_API_KEY'],
        ]
    ]
];