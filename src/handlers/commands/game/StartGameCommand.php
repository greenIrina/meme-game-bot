<?php

namespace App\handlers\commands\game;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\handlers\commands\NewGameCommand;
use App\service\GameService;
use App\service\ThemeService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class StartGameCommand extends Conversation implements Command
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
        $cid = $bot->chatId();
        if ($gameService->checkStartedGame($cid)) {
            $bot->sendMessage("Игра пока не начата! Чтобы начать игру, используйте команду /" . NewGameCommand::getName());
        } else {
            $players = $gameService->getPlayers($cid);
            if (count($players) < 3) {
                $bot->sendMessage("В игре должно быть как минимум 2 участника. Чтобы присоединиться, используйте команду /"
                    . JoinCommand::getName());
            } else {
//                $this->next('getTheme');
                /** @var ThemeService $themeService */
                $themeService = $bot->getGlobalData(StoredVariables::THEME_SERVICE);
                $theme = $themeService->getRandomTheme($bot->chatId());
                $bot->sendMessage("Начинаем раунд! Текущая тема: " . $theme['name']
                . "\n\nЕсли желаете сменить тему, снова нажмите /" . StartGameCommand::getName());
                $bot->sendMessage("Участники, отправляйте по мему, после чего проведите голосование по команде /"
                . EndRoundCommand::getName());
//                $this->end();
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getTheme(Nutgram $bot) {
        /** @var ThemeService $themeService */
        $themeService = $bot->getGlobalData(StoredVariables::THEME_SERVICE);
        $theme = $themeService->getRandomTheme($bot->chatId());
        $bot->sendMessage("Тема текущего раунда: " . $theme['name']);
        $this->end();
    }

    public static function getName(): string
    {
        return "start";
    }

    public static function getDescription(): string
    {
        return "начать раунд";
    }
}