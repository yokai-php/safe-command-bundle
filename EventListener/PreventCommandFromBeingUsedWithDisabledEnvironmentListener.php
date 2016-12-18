<?php

namespace Yokai\SafeCommandBundle\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PreventCommandFromBeingUsedWithDisabledEnvironmentListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $environments;

    /**
     * @param array $environments
     */
    public function __construct(array $environments)
    {
        $this->environments = $environments;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => '__invoke',
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function __invoke(ConsoleCommandEvent $event)
    {
        // if there is no "env" option (this should never happen)
        if (!$event->getInput()->hasOption('env')) {
            return;
        }

        $environment = $event->getInput()->getOption('env');
        $output = $event->getOutput();

        // if the environment is not one of allowed
        if (!in_array($environment, $this->environments)) {
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
