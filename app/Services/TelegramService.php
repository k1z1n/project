<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\TelegramUser;

class TelegramService
{
    protected mixed $botToken;

    public function __construct()
    {
        // Получаем токен бота из конфигурации .env
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * Метод для отправки сообщения пользователю по username
     *
     * @param string $username - Имя пользователя Telegram (без символа @)
     * @param string $message - Текст сообщения для отправки
     * @return string          - Сообщение о результате отправки
     */
    public function sendMessageToUsername(string $username, string $message): string
    {
        // Получаем chat_id пользователя через его username
        $chatId = $this->getChatIdByUsername($username);

        if ($chatId === null) {
            return "Пользователь с username {$username} не найден.";
        }

        // Отправляем сообщение пользователю
        $result = $this->sendMessageToChatId($chatId, $message);

        return $result ? "Сообщение успешно отправлено пользователю @{$username}." : "Не удалось отправить сообщение пользователю @{$username}.";
    }

    /**
     * Метод для отправки сообщения по chat_id
     *
     * @param int $chatId - Telegram chat_id пользователя
     * @param string $message - Текст сообщения для отправки
     * @return bool           - Статус отправки сообщения (успех или неудача)
     */
    protected function sendMessageToChatId(int $chatId, string $message): bool
    {
        // Отправляем сообщение пользователю по chat_id
        $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        return $response->successful();
    }

    /**
     * Метод для получения chat_id пользователя по username
     *
     * @param string $username - Имя пользователя Telegram (без символа @)
     * @return int|null         - chat_id пользователя или null, если не найден
     */
    protected function getChatIdByUsername(string $username): int|null
    {
        $telegramUser = TelegramUser::where('username', $username)->first();
        return $telegramUser ? $telegramUser->chat_id : null;
    }

    public function updateTelegramUsers()
    {
        $response = Http::get("https://api.telegram.org/bot{$this->botToken}/getUpdates");

        if ($response->successful()) {
            $updates = $response->json()['result'];

            foreach ($updates as $update) {
                if (isset($update['message']['chat']['id']) && $update['message']['from']['username']) {
                    $chatId = $update['message']['chat']['id'];
                    $username = $update['message']['from']['username'];

                    TelegramUser::updateOrCreate([
                        'chat_id' => $chatId,
                        'username' => $username,
                    ]);
                }
            }
        } else {
            return null;
        }
    }
}
