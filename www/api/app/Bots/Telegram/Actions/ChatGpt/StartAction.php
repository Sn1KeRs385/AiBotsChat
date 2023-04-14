<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Bots\Telegram\Keyboards\ChatGptKeyboards;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class StartAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __construct(protected ChatGptKeyboards $keyboards)
    {
    }

    public function __invoke(): void
    {
        $name = TelegramWebhook::getData()->getUser()->first_name;
        $text = "<b>🤖 Привет, {$name}!</b> Я бот ChatGPT!

<b>🔗Я искусственный интеллект, могу написать для вас любой текст или компьютерную программу. Вы можете задавать любые вопросы.</b>

Советы к правильному использованию:
– Задавайте осмысленные вопросы, расписывайте детальнее.
– Не пишите ерунду иначе одержите её же в ответ.

Примеры вопросов/запросов:
~ Сколько будет 7 * 8?
~ Когда началась Вторая Мировая?
~ Напиши код калькулятора на Python
~ Напиши сочинение как я провел лето

🔥 Чтобы начать общение, напиши что-нибудь CHATGPT в строку ниже 👇🏻";

        TelegramWebhook::getBot()->sendMessage(
            $text,
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::HTML,
                'reply_markup' => $this->keyboards->getMainReplyKeyboard(),
            ]
        );
    }

    public static function getPaths(): array
    {
        return [
            '/^\/start/u',
            '/^.{0,3}Возможности/u'
        ];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::MESSAGE];
    }
}
