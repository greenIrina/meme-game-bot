<?php

namespace App\handlers\commands\game;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\handlers\commands\NewGameCommand;
use App\service\GameService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class ListPlayersCommand extends Conversation implements Command
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) {
        /**
         * @var GameService $gameService
         */
        $gameService = $bot->getGlobalData(StoredVariables::GAME_SERVICE);
        $cid = $bot->chatId();
        if ($gameService->checkStartedGame($cid)) {
            $bot->sendMessage("Игра пока не начата! Чтобы начать игру, используйте команду /" . NewGameCommand::getName());
        } else {
            $players = $gameService->getPlayers($cid);
            if (count($players) == 1) {
                $bot->sendMessage("В текущей игре нет игроков.");
            } else {
                $names = "Список игроков:";
                foreach ($players as $player) {
                    if ($player['player_name'] == '0') {
                        continue;
                    }
                    $names = $names . "\n@" . $player['player_name'];
                }
                $bot->sendMessage($names);
            }

        }
    }

    public static function getName(): string
    {
        return "listPlayers";
    }

    public static function getDescription(): string
    {
        return "список текущих игроков";
    }
}