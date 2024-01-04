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

        if (!$config['enabled']) {
            return;
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        $this->defineDisabledCommands($config['commands'], $container);
        $this->defineAllowedEnvironments($config['environments'], $container);
    }

    private function defineDisabledCommands(array $config, ContainerBuilder $container): void
    {
        if (!$config['enabled']) {
            $container->removeDefinition(
                'yokai_safe_command.event_listener.prevent_disabled_command_from_being_used_listener'
            );

            return;
        }

        // collect commands over config sections
        $commands = [];
        foreach ($config as $value) {
            if (!\is_array($value)) {
                continue;
            }
            if (!isset($value['enabled']) || !$value['enabled']) {
                continue;
            }
            if (!isset($value['commands'])) {
                continue;
            }

            $commands = array_merge($commands, $value['commands']);
        }

        // format commands
        $commands = array_unique($commands);
        sort($commands);

        // set disabled commands parameter
        $container->setParameter('yokai_safe_command.disabled_commands', $commands);
    }

    private function defineAllowedEnvironments(array $config, ContainerBuilder $container): void
    {
        if (!$config['enabled']) {
            $container->removeDefinition(
                'yokai_safe_command.event_listener.prevent_command_from_being_used_with_disabled_environment_listener'
            );

            return;
        }

        // set allowed environments parameter
        $container->setParameter('yokai_safe_command.allowed_environments', $config['environments']);
    }
}
