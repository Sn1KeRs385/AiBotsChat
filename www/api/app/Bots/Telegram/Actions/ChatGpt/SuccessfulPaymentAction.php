<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Attributes\UpdateTypes;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Enums\WalletTransactionType;
use App\Enums\WalletType;
use App\Models\Tariff;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;

class SuccessfulPaymentAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __invoke(): void
    {
        $invoicePayload = json_decode(
            TelegramWebhook::getData()->getMessage()->successful_payment->invoice_payload,
            true
        );

        $tariff = Tariff::query()
            ->where('wallet_type', WalletType::GPT)
            ->where('id', $invoicePayload['tariffId'])
            ->first();

        $wallet = TelegramWebhook::getUser()
            ->getOrCreateWalletByType(WalletType::GPT);

        $wallet->createTransaction(WalletTransactionType::REFILL, $tariff->deposit, [
            'date' => TelegramWebhook::getData()->getMessage()->date,
            'payment_info' => TelegramWebhook::getData()->getMessage()->successful_payment,
        ]);

        $tokensDeposit = number_format($tariff->deposit, 0, '.', ' ');
        $tokensBalance = number_format($wallet->getBalanceNormalize(), 0, '.', ' ');

        TelegramWebhook::getBot()->sendMessage(
            "<b>Зачислено токенов: <code>$tokensDeposit</code>. Текущий баланс: <code>$tokensBalance</code></b>",
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::HTML,
            ]
        );
    }

    public static function getPaths(): array
    {
        return [];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::SUCCESSFUL_PAYMENT];
    }
}
