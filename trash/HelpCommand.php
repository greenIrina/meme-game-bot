<?php

namespace trash;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Message\Message;

class HelpCommand extends Command
{
    protected string $command = 'help';

    protected ?string $description = 'Доступные команды';

    public function handle(Nutgram $bot): void
    {
        $bot->sendMessage('Доступные команды:'
////            . $this->buildDescription(StartGameCommand::class)
////            . "\n"
////            . $this->buildDescription(ShowAllTextsCommand::class)
////            . "\n"
////            . $this->buildDescription(AddTextCommand::class)
////            . $this->buildDescription(DeleteTextCommand::class)
////            . "\n"
            . $this->buildDescription(HelpCommand::class)
        );
    }
    private function buildDescription($class): string
    {
        return '
        /' . $class::getName() . ' - ' . $class::getDescription();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function begin(Nutgram $bot): self
    {
        $instance = new static();
        $instance($bot);

        return $instance;
    }
}