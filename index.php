<?php

use App\config\StoredVariables;
use App\handlers\commands\game\EndRoundCommand;
use App\handlers\commands\game\JoinCommand;
use App\handlers\commands\game\LeaveGameCommand;
use App\handlers\commands\game\ListPlayersCommand;
use App\handlers\commands\game\StartGameCommand;
use App\handlers\commands\game\StopGameCommand;
use App\handlers\commands\HelpCommand;
use App\handlers\commands\NewGameCommand;
use App\handlers\commands\themes\AddNewThemeCommand;
use App\handlers\commands\themes\RemoveThemeCommand;
use App\handlers\commands\themes\ShowAllThemesCommand;
use App\handlers\ExceptionHandler;
use App\middleware\AuthMiddleware;
use App\repository\ChatRepository;
use App\repository\GameRepository;
use App\repository\ThemeRepository;
use App\service\GameService;
use App\service\ThemeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;

require_once 'vendor/autoload.php';

ini_set('max_execution_time', '300');
set_time_limit(0);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


try {
    $config = [
        'bot_name' => $_ENV['BOT_NAME'],
        'timeout' => 15
    ];
    $bot = new Nutgram($_ENV['BOT_TOKEN'], $config);
    $bot->setRunningMode(Polling::class);

    $mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $bot->setGlobalData(StoredVariables::THEME_SERVICE, new ThemeService(new ThemeRepository($mysqli)));
    $bot->setGlobalData(StoredVariables::CHAT_SERVICE, new ChatRepository($mysqli));
    $bot->setGlobalData(StoredVariables::GAME_SERVICE, new GameService(new GameRepository($mysqli)));
    $bot->middleware(AuthMiddleware::class);

    $bot->onException(ExceptionHandler::class);

    $bot->onCommand(HelpCommand::getName(), HelpCommand::class)
        ->description(HelpCommand::getDescription());

    $bot->onCommand(AddNewThemeCommand::getName(), AddNewThemeCommand::class)
        ->description(AddNewThemeCommand::getDescription());

    $bot->onCommand(ShowAllThemesCommand::getName(), ShowAllThemesCommand::class)
        ->description(ShowAllThemesCommand::getDescription());

    $bot->onCommand(RemoveThemeCommand::getName(), RemoveThemeCommand::class)
        ->description(RemoveThemeCommand::getDescription());

    $bot->onCommand(NewGameCommand::getName(), NewGameCommand::class)
        ->description(NewGameCommand::getDescription());

    $bot->onCommand(JoinCommand::getName(), JoinCommand::class)
        ->description(JoinCommand::getDescription());

    $bot->onCommand(ListPlayersCommand::getName(), ListPlayersCommand::class)
        ->description(ListPlayersCommand::getDescription());

    $bot->onCommand(StopGameCommand::getName(), StopGameCommand::class)
        ->description(StopGameCommand::getDescription());

    $bot->onCommand(StartGameCommand::getName(), StartGameCommand::class)
        ->description(StartGameCommand::getDescription());

    $bot->onCommand(EndRoundCommand::getName(), EndRoundCommand::class)
        ->description(EndRoundCommand::getDescription());

    $bot->onCommand(LeaveGameCommand::getName(), LeaveGameCommand::class)
        ->description(LeaveGameCommand::getDescription());

    $bot->registerMyCommands();

    try {
        $bot->run();
    } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
        die('Connect Error');
    }
} catch (NotFoundExceptionInterface|ContainerExceptionInterface|\Psr\SimpleCache\InvalidArgumentException $e) {
}

