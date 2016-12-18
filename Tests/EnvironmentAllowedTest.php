<?php

namespace Yokai\SafeCommandBundle\Tests;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class EnvironmentAllowedTest extends CommandTestCase
{
    /**
     * @test
     */
    public function command_with_disallowed_environment_should_not_run()
    {
        $application = static::createApplication();

        $exit = $application->run(new StringInput('list --env=prod'), $output = new BufferedOutput());

        static::assertSame(
            ConsoleCommandEvent::RETURN_CODE_DISABLED,
            $exit,
            'Running command in disallowed environment return disabled return code.'
        );

        static::assertRegExp(
            '/Running command with "prod" environment is not allowed. Aborting.../',
            $output->fetch(),
            'Running command with disallowed environment should output message to tell it wont run.'
        );
    }
}
