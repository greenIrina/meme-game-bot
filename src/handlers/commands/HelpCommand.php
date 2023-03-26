<?php

namespace App\handlers\commands;

use App\handlers\commands\game\EndRoundCommand;
use App\handlers\commands\game\JoinCommand;
use App\handlers\commands\game\LeaveGameCommand;
use App\handlers\commands\game\StartGameCommand;
use App\handlers\commands\game\StopGameCommand;
use App\handlers\commands\themes\AddNewThemeCommand;
use App\handlers\commands\themes\RemoveThemeCommand;
use App\handlers\commands\themes\ShowAllThemesCommand;
use SergiX44\Nutgram\Nutgram;

class HelpCommand implements Command
{

    public function __invoke(Nutgram $bot)
    {
        $bot->sendMessage("Привет! Я игровой бот по мемам. Краткие правила:\n\n"
            . "На каждый раунд вам выдаётся определённая тема или ситуация, например, \"Когда открыл холодильник и там пусто.\" "
            . "Каждый игрок, зарегистрировавшийся в игре, кидает мем, наиболее подходящий под ситуацию. "
            . "После окончания раунда проводится голосование для определения победителя раунда.\n\n"
            . "Доступные команды:\n"
            . $this->commandToText(ShowAllThemesCommand::class)
            . $this->commandToText(AddNewThemeCommand::class)
            . $this->commandToText(RemoveThemeCommand::class)
            . "\n"
            . $this->commandToText(NewGameCommand::class)
            . $this->commandToText(JoinCommand::class)
            . $this->commandToText(LeaveGameCommand::class)
            . "\n"
            . $this->commandToText(StartGameCommand::class)
            . $this->commandToText(EndRoundCommand::class)
            . $this->commandToText(StopGameCommand::class)
            . "\n"
            . $this->commandToText(HelpCommand::class)
        );
    }

    private function commandToText($class): string
    {
        return "\n/" . $class::getName() . ' - ' . $class::getDescription();
    }

    public static function getName(): string
    {
        return 'help';
    }

    public static function getDescription(): string
    {
        return 'правила и доступные команды';
    }
}