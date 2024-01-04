<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\Tests;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class EnvironmentAllowedTest extends CommandTestCase
{
    /**
     * @test
     */
    public function command_with_disallowed_environment_should_not_run(): void
    {
        $application = self::createApplication();

        $exit = $application->run(new StringInput('list --env=prod'), $output = new BufferedOutput());

        self::assertSame(
            ConsoleCommandEvent::RETURN_CODE_DISABLED,
            $exit,
            'Running command in disallowed environment return disabled return code.'
        );

        self::assertMatchesRegularExpression(
            '/Running command with "prod" environment is not allowed. Aborting.../',
            $output->fetch(),
            'Running command with disallowed environment should output message to tell it wont run.'
        );
    }
}
