<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class YokaiSafeCommandExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        $commands = \array_unique([
            ...$config['standard'],
            ...$config['custom'],
        ]);
        $container->setParameter('yokai_safe_command.disabled_commands', $commands);
    }
}
