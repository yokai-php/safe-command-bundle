<?php

namespace Yokai\SafeCommandBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class YokaiSafeCommandExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$config['enabled']) {
            return;
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $this->defineDisabledCommands($config['commands'], $container);
        $this->defineAllowedEnvironments($config['environments'], $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function defineDisabledCommands(array $config, ContainerBuilder $container)
    {
        if (!$config['enabled']) {
            $container->removeDefinition(
                'yokai_safe_command.event_listener.prevent_disabled_command_from_being_used_listener'
            );

            return;
        }

        // collect commands over config sections
        $commands = [];
        foreach ($config as $name => $value) {
            if (!is_array($value)) {
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
        $container->setParameter('yokai_app_keeper.disabled_commands', $commands);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function defineAllowedEnvironments(array $config, ContainerBuilder $container)
    {
        if (!$config['enabled']) {
            $container->removeDefinition(
                'yokai_safe_command.event_listener.prevent_command_from_being_used_with_disabled_environment_listener'
            );

            return;
        }

        // set allowed environments parameter
        $container->setParameter('yokai_app_keeper.allowed_environments', $config['environments']);
    }
}
