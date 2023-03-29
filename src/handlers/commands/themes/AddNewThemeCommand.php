<?php

namespace App\handlers\commands\themes;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\service\ThemeService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class AddNewThemeCommand extends Conversation implements Command
{

    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot)
    {
        $bot->sendMessage('Введите описание темы ответом на это сообщение (тот же человек, что нажал на команду, я тупой):');
        $this->next('addTheme');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addTheme(Nutgram $bot)
    {
        $input = $bot->message()->text;

        /** @var ThemeService $themeService */
        $themeService = $bot->getGlobalData(StoredVariables::THEME_SERVICE);

        $themeService->addTheme($input, $bot->chatId());
        $this->end();
        $bot->sendMessage("Поздравляем, тема успешно добавлена!");
    }
    public static function getName(): string
    {
        return "addNewTheme";
    }

    public static function getDescription(): string
    {
        return "добавить новую тему";
    }
}