<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\Tests\Stubs;

use Psr\Log\NullLogger;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

final class YokaiSafeCommandTestKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Yokai\SafeCommandBundle\YokaiSafeCommandBundle(),
        ];
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->set('logger', new NullLogger());
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config.yml');
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/' . Kernel::VERSION . '/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/' . Kernel::VERSION . '/logs';
    }
}
