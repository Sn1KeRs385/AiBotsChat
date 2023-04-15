<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Enums\WalletType;
use App\Models\Tariff;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ShopAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __invoke(): void
    {
        $text = "Выберите количество токенов для покупки";

        $keyboard = Cache::tags([config('cache.config.tariff.tag')])
            ->remember('gptKeyboard', config('cache.config.tariff.ttl'), function () {
                $tariffs = Tariff::query()
                    ->where('wallet_type', WalletType::GPT)
                    ->where('is_active', true)
                    ->orderByDesc('weight')
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->get();

                $keyboard = InlineKeyboardMarkup::make();

                foreach ($tariffs as $tariff) {
                    $tokens = number_format($tariff->deposit, 0, '.', ' ');
                    $money = number_format($tariff->price / 100, 0, '.', '');
                    $keyboard->addRow(
                        InlineKeyboardButton::make(
                            "{$tokens} токенов - {$money} ₽",
                            callback_data: "/buy_tariff-tariff={$tariff->id}"
                        )
                    );
                }

                return $keyboard;
            });

        TelegramWebhook::getBot()->sendMessage(
            $text,
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::HTML,
                'reply_markup' => $keyboard,
            ]
        );
    }

    public static function getPaths(): array
    {
        return [
            '/^\/shop/u',
            '/^.{0,3}Магазин/u'
        ];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::MESSAGE];
    }
}
