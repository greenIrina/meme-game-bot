<?php

namespace App\handlers\commands;

use App\config\StoredVariables;
use App\handlers\commands\game\JoinCommand;
use App\handlers\commands\game\StartGameCommand;
use App\service\GameService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class NewGameCommand extends Conversation implements Command
{

    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot)
    {
        /**
         * @var GameService $gameService
         */
        $gameService = $bot->getGlobalData(StoredVariables::GAME_SERVICE);
        $currentGame = $gameService->checkStartedGame($bot->chatId());
        if ($currentGame) {
//            $bot->setData(StoredVarNames::CURRENT_GAME, true);
            $bot->sendMessage("Новая игра создана! Если желаете присоединиться, используйте команду /"
                . JoinCommand::getName()
                . ". Чтобы начать игру, используйте команду /"
                . StartGameCommand::getName());
            $gameService->addGame($bot->chatId());
        } else {
            $bot->sendMessage("Игра уже начата!");
        }
    }


    public static function getName(): string
    {
        return "newGame";
    }

    public static function getDescription(): string
    {
        return "начать новую игру";
    }
}