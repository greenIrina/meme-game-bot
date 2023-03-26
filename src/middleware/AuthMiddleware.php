<?php

namespace App\middleware;

use App\config\StoredVariables;
use App\repository\ChatRepository;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Nutgram;

class AuthMiddleware
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(Nutgram $bot, $next): void
    {
        if (!$bot->getData(StoredVariables::LOGGED_IN)) {
            /** @var ChatRepository $chatRepository */
            $chatRepository = $bot->getGlobalData(StoredVariables::CHAT_SERVICE);
//            $bot->sendMessage($bot->chatId());
            $chatRepository->addIfAbsent($bot->chatId());
            $bot->setData(StoredVariables::LOGGED_IN, true);
        }
        $next($bot);
    }
}