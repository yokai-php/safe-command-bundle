<?php

namespace Yokai\SafeCommandBundle\Tests;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CommandDisabledTest extends CommandTestCase
{

    /**
     * @return array
     */
    protected static function getDisabledCommands()
    {
        return static::$kernel->getContainer()->getParameter('yokai_app_keeper.disabled_commands');
    }

    /**
     * @test
     */
    public function disabled_command_should_not_appear_in_list_command_output()
    {
        static::createApplication()->run(new StringInput('list'), $output = new BufferedOutput());

        $out = $output->fetch();

        foreach (static::getDisabledCommands() as $command) {
            static::assertNotRegExp(
                '/'.$command.'/',
                $out,
                sprintf('Disabled command "%s" should not appear in commands listing.', $command)
            );
        }
    }

    /**
     * @test
     */
    public function disabled_command_should_not_run()
    {
        $application = static::createApplication();

        foreach (static::getDisabledCommands() as $command) {
            $exit = $application->run(new StringInput($command), $output = new BufferedOutput());

            static::assertSame(
                ConsoleCommandEvent::RETURN_CODE_DISABLED,
                $exit,
                'Running disabled command should return disabled return code.'
            );

            static::assertRegExp(
                '/This command has been disabled. Aborting.../',
                $output->fetch(),
                'Disabled command should output message to tell it wont run.'
            );
        }
    }
}
