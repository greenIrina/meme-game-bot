<?php

namespace App\handlers\commands\game;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\handlers\commands\NewGameCommand;
use App\service\GameService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class JoinCommand extends Conversation implements Command
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) {
        /**
         * @var GameService $gameService
         */
        $gameService = $bot->getGlobalData(StoredVariables::GAME_SERVICE);
        $uname = $bot->user()->username;
        $cid = $bot->chatId();
        if ($gameService->checkStartedGame($cid)) {
            $bot->sendMessage("Игра пока не начата! Чтобы начать игру, используйте команду /" . NewGameCommand::getName());
        } else if (!$gameService->addPlayer($cid, $uname)) {
            $bot->sendMessage("Вы уже в игре!");
        } else {
            $bot->sendMessage("Вы присоединились к игре!");
        }
    }

    public static function getName(): string
    {
        return "join";
    }

    public static function getDescription(): string
    {
        return "присоединиться к игре";
    }
}