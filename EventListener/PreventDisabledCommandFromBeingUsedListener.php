<?php

namespace Yokai\SafeCommandBundle\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class PreventDisabledCommandFromBeingUsedListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $commands;

    /**
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
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
        $name = $event->getCommand()->getName();
        $output = $event->getOutput();

        // if the command is the listing command
        if ('list' === $name) {
            // hide disabled commands from the list
            foreach ($event->getCommand()->getApplication()->all() as $command) {
                if (in_array($command->getName(), $this->commands)) {
                    if (method_exists($command, 'setHidden')) {
                        $command->setHidden(true);
                    } elseif (method_exists($command, 'setPublic')) {
                        $command->setPublic(false);
                    }
                }
            }
        // if the command is one of the disabled commands
        } elseif (in_array($name, $this->commands)) {
            // disable command
            $event->disableCommand();
            $output->writeln('<error>This command has been disabled. Aborting...</error>');
        }
    }
}
