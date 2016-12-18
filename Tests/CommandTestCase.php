<?php

namespace Yokai\SafeCommandBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class CommandTestCase extends KernelTestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @return Application
     */
    protected static function createApplication()
    {
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        return $application;
    }
}
