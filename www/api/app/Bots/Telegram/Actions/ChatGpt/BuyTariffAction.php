<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Actions\Traits\ParamsParse;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Enums\WalletType;
use App\Models\Tariff;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;
use SergiX44\Nutgram\Telegram\Types\Payment\LabeledPrice;

class BuyTariffAction extends AbstractAction
{
    use ActionRouteInfoMapper;
    use ParamsParse;

    public function __invoke(): void
    {
        $params = $this->getParamsFromWebhookData(TelegramWebhook::getFacadeRoot());
        $tariff = Tariff::query()
            ->where('wallet_type', WalletType::GPT)
            ->where('id', $params['tariff'])
            ->first();

        if (!$tariff) {
            TelegramWebhook::getBot()->sendMessage(
                'Произошла ошибка, попробуйте позже',
                [
                    'chat_id' => TelegramWebhook::getData()->getChat()->id,
                    'parse_mode' => ParseMode::HTML,
                ]
            );
        }

        $tokens = number_format($tariff->deposit, 0, '.', ' ');
        TelegramWebhook::getBot()->sendInvoice(
            "$tokens токенов",
            'Покупка токенов в боте "ChatGPT"',
            json_encode(['tariffId' => $tariff->id]),
            config('telegram.pay_provider_token.chat_gpt'),
            'RUB',
            [
                ['label' => 'Товар', 'amount' => $tariff->price]
            ],
            [
                'need_email' => true,
                'send_email_to_provider' => true,
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'provider_data' => [
                    'receipt' => [
                        'items' => [
                            [
                                'description' => 'Покупка токенов в боте "ChatGPT - $tokens токенов',
                                'amount' => [
                                    'value' => number_format($tariff->price / 100, 2, '.', ''),
                                    'currency' => 'RUB',
                                ],
                                'vat_code' => 1,
                                'quantity' => 1,
                            ]
                        ],
                    ],
                ],
            ],
        );
    }

    public static function getPaths(): array
    {
        return ['/^\/buy_tariff/u'];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::CALLBACK_QUERY];
    }
}
