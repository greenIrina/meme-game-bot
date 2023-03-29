<?php

namespace App\handlers\commands\themes;

use App\config\StoredVariables;
use App\handlers\commands\Command;
use App\service\ThemeService;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class RemoveThemeCommand extends Conversation implements Command
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) {
        $bot->sendMessage("Напишите номер темы, которую хотите удалить, ответом на это сообщение (тот же человек, что нажал на команду, я тупой):");
        $this->next('deleteTheme');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function deleteTheme(Nutgram $bot) {
        $input = $bot->message()->text;
        /** @var ThemeService $themeService */
        $themeService = $bot->getGlobalData(StoredVariables::THEME_SERVICE);
        if (!$themeService->findById($input)) {
            $bot->sendMessage("У вас нет темы с таким номером, попробуйте ещё раз.");
        } else {
            $themeService->deleteById($input);
            $bot->sendMessage('Тема успешно удалена.');
        }
        $this->end();

    }

    public static function getName(): string
    {
        return "removeTheme";
    }

    public static function getDescription(): string
    {
        return "удалить тему по номеру";
    }
}