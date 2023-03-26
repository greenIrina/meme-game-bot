<?php

namespace trash;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\handlers\commands\game\EndRoundCommand;
use App\handlers\commands\NewGameCommand;
use App\service\GameService;
use App\service\ThemeService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class NextRoundCommand extends Conversation implements Command
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
            $bot->sendMessage("Начинаем раунд! Как только заканчиваете раунд, введите команду /"
                . " чтобы провести голосование и команду /"
                . " чтобы перейти к следующему раунду.");
            $this->next('getTheme');
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
        EndRoundCommand::class->start();
    }

    public static function getName(): string
    {
        return "nextRound";
    }

    public static function getDescription(): string
    {
        return "перейти к следующему раунду";
    }
}