<?php

namespace App\Console\Commands;

use App\Bots\Telegram\TelegramBot;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-web-hook {--dropUpdates=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set webhook for new messages';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->setBotWebhook(config('telegram.bot_api_keys.chat_gpt'), route('api.telegram.webhook.chat-gpt'));
    }

    protected function setBotWebhook(string $apiKey, string $url): void
    {
        $bot = new TelegramBot($apiKey);
        $bot->setWebhook(
            $url,
            [
                'secret_token' => config('telegram.webhook_token'),
                'allowed_updates' => [
                    'message',
                    'callback_query',
                    'chat_join_request',
                    'chat_member',
                    'pre_checkout_query',
                ],
                'drop_pending_updates' => filter_var(
                    $this->option('dropUpdates') ?? false,
                    FILTER_VALIDATE_BOOL
                ),
            ]
        );
    }
}
