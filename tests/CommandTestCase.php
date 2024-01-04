<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class CommandTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();
    }

    protected static function createApplication(): Application
    {
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        return $application;
    }
}
