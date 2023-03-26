<?php

namespace App\handlers;

use SergiX44\Nutgram\Nutgram;
use Throwable;

class ExceptionHandler
{

    public function __invoke(Nutgram $bot, Throwable $exception): void
    {
//        if ($_ENV['MODE'] == 'dev') {
//            echo $exception->getMessage();
//            error_log($exception);
//        }
        $bot->sendMessage('Неопознанная ошибка( Попробуйте снова.');
        $bot->sendMessage("ERROR: " . $exception->getTraceAsString() . " \n" . $exception->getMessage());
    }
}