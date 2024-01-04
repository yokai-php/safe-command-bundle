<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\Tests;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;

final class CommandDisabledTest extends CommandTestCase
{
    /**
     * @test
     */
    public function disabled_command_should_not_appear_in_list_command_output(): void
    {
        self::createApplication()->run(new StringInput('list'), $output = new BufferedOutput());

        $out = $output->fetch();

        self::assertMatchesRegularExpression('/cache:clear/', $out, 'Some commands are still viewable.');

        foreach (self::commands() as [$command]) {
            self::assertDoesNotMatchRegularExpression(
                '/' . $command . '/',
                $out,
                sprintf('Disabled command "%s" should not appear in commands listing.', $command)
            );
        }
    }

    /**
     * @test
     * @dataProvider commands
     */
    public function disabled_command_should_not_run(string $command): void
    {
        $application = self::createApplication();

        $exit = $application->run(new StringInput($command), $output = new BufferedOutput());

        self::assertSame(
            ConsoleCommandEvent::RETURN_CODE_DISABLED,
            $exit,
            sprintf('Running disabled command "%s" should return disabled return code.', $command)
        );

        self::assertMatchesRegularExpression(
            '/This command has been disabled. Aborting.../',
            $output->fetch(),
            sprintf('Disabled command "%s" should output message to tell it wont run.', $command)
        );
    }

    /**
     * @test
     */
    public function enabled_command_should_run(): void
    {
        $application = self::createApplication();

        $exit = $application->run(new StringInput('cache:clear'), new NullOutput());

        self::assertSame(Command::SUCCESS, $exit, 'Some commands are still runnable');
    }

    public static function commands(): \Generator
    {
        yield ['debug:container'];
        yield ['debug:autowiring'];
        yield ['lint:container'];
    }
}
