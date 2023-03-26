<?php

namespace App\handlers\commands\themes;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\service\ThemeService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class ShowAllThemesCommand extends Conversation implements Command
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) {

        /** @var ThemeService $themeService */
        $themeService = $bot->getGlobalData(StoredVariables::THEME_SERVICE);
        $themes = $themeService->findAllByChatId($bot->chatId());
        $output = "";
        foreach ($themes as &$theme) {
            $output = $output . $theme['id'] . ". " . $theme['name'] . "\n";
        }
        if ($output == "") {
            $bot->sendMessage("У этого чата нет тем.");
        } else {
            $bot->sendMessage("Список тем этого чата\n" . $output);
        }

    }

    public static function getName(): string
    {
        return "showAllThemes";
    }

    public static function getDescription(): string
    {
        return "показать все темы";
    }
}