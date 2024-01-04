<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PreventCommandFromBeingUsedWithDisabledEnvironmentListener implements EventSubscriberInterface
{
    public function __construct(
        /**
         * @var array<string>
         */
        private array $environments,
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
        // if there is no "env" option (this should never happen)
        if (!$event->getInput()->hasOption('env')) {
            return;
        }

        $environment = $event->getInput()->getOption('env');
        $output = $event->getOutput();

        // if the environment is not one of allowed
        if (!\in_array($environment, $this->environments, true)) {
            // disable command
            $event->disableCommand();
            $output->writeln(
                sprintf(
                    '<error>Running command with "%s" environment is not allowed. Aborting...</error>',
                    $environment
                )
            );
        }
    }
}
