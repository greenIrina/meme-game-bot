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
                $bot->sendMessage("В игре должно быть как минимум 2 участника.");
            } else {
                $bot->sendMessage("Начинаем раунд! Как только заканчиваете раунд, введите команду /" . EndRoundCommand::getName()
                    . ", чтобы провести голосование, и команду /" . StartGameCommand::getName()
                    . ", чтобы перейти к следующему раунду.");
//                $this->next('getTheme');
                /** @var ThemeService $themeService */
                $themeService = $bot->getGlobalData(StoredVariables::THEME_SERVICE);
                $theme = $themeService->getRandomTheme($bot->chatId());
                $bot->sendMessage("Тема текущего раунда: " . $theme['name']);
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