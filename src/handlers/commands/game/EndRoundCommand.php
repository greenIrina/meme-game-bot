<?php

namespace App\handlers\commands\game;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\handlers\commands\NewGameCommand;
use App\service\GameService;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class EndRoundCommand extends Conversation implements Command
{

    /**
     * @throws InvalidArgumentException
     * @throws JsonException
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
            if (count($players) < 3) {
                $bot->sendMessage("В игре должно быть как минимум 2 участника.");
            } else {
                $bot->sendMessage("Раунд окончен. Голосуем за лучший вариант:");

                $options = array();
                foreach ($players as $player) {
                    if ($player['player_name'] == '0') {
                        continue;
                    }
                    $options[] = $player['player_name'];
                }
                $bot->sendPoll("Победитель раунда", $options);
            }

        }
    }

    public static function getName(): string
    {
        return "endRound";
    }

    public static function getDescription(): string
    {
        return "закончить текущий раунд";
    }
}