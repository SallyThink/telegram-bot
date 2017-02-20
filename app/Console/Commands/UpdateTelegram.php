<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram;

class UpdateTelegram extends Command
{
    protected $signature = 'telegram:update';
    protected $description = 'Обновить данные webhook.';

    public function handle()
    {
        $url = str_replace('http://', 'https://', route('telegram'));

        $result = Telegram::bot()->setWebhook([
            'url' => $url,
        ]);

        if (!$result->getResult()) {
            $this->error('Webhook установить не удалось: ' . $result->getDecodedBody()['description']);
            return;
        }

        $this->info('Webhook был успешно установлен');
    }
}