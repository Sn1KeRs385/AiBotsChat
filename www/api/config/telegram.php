<?php


return [
    'bot_api_keys' => [
        'chat_gpt' => env('TELEGRAM_CHATGPT_BOT_API_KEY')
    ],
    'pay_provider_token' => [
        'chat_gpt' => env('TELEGRAM_CHATGPT_BOT_PAY_PROVIDER_TOKEN'),
    ],
    'webhook_token' => env('TELEGRAM_WEBHOOK_TOKEN'),
];
