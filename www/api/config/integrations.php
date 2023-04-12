<?php

return [
    'open_ai' => [
        'api_key' => env('OPEN_AI_API_KEY'),
        'stream_enabled' => env('OPEN_AI_STREAM_ENABLED', false),
    ],
];
