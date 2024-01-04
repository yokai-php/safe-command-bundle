<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PreventDisabledCommandFromBeingUsedListener implements EventSubscriberInterface
{
    public function __construct(
        /**
         * @var array<string>
         */
        private array $commands,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => '__invoke',
        ];
    }

    public function __invoke(ConsoleCommandEvent $event): void
    {
        $name = $event->getCommand()->getName();
        $output = $event->getOutput();

        // if the command is the listing command
        if ($name === 'list') {
            // hide disabled commands from the list
            foreach ($event->getCommand()->getApplication()->all() as $command) {
                if (\in_array($command->getName(), $this->commands, true)) {
                    $command->setHidden();
                }
            }

            return;
        }

        // if the command is one of the disabled commands
        if (\in_array($name, $this->commands, true)) {
            // disable command
            $event->disableCommand();
            $output->writeln('<error>This command has been disabled. Aborting...</error>');
        }
    }
}
