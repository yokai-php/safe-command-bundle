<?php

namespace Yokai\SafeCommandBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $tree = new TreeBuilder();
        $root = $tree->root('yokai_safe_command');

        $root
            ->canBeDisabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('commands')
                    ->canBeDisabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->createCommandsNode('config'))
                        ->append($this->createCommandsNode('doctrine'))
                        ->append($this->createCommandsNode('debug'))
                        ->append($this->createCommandsNode('lint'))
                        ->append($this->createCommandsNode('server'))
                        ->append($this->createCommandsNode('translation'))
                        ->append($this->createCommandsNode('misc'))
                    ->end()
                ->end()
                ->append($this->createEnvironmentsNode())
            ->end()
        ;

        return $tree;
    }

    /**
     * @return NodeDefinition
     */
    private function createEnvironmentsNode()
    {
        $node = (new TreeBuilder())->root('environments');

        $node
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('environments')
                    ->defaultValue(['prod'])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * @param string $name
     *
     * @return NodeDefinition
     */
    private function createCommandsNode($name)
    {
        $node = (new TreeBuilder())->root($name);

        $map = [
            'config' => [
                'config:dump-reference',
            ],
            'doctrine' => [
                'doctrine:database:drop',
                'doctrine:mapping:convert',
                'doctrine:mapping:import',
                'doctrine:schema:drop',
                'doctrine:schema:validate',
            ],
            'debug' => [
                'debug:config',
                'debug:container',
                'debug:event-dispatcher',
                'debug:router',
                'debug:swiftmailer',
                'debug:translation',
                'debug:twig',
            ],
            'lint' => [
                'lint:twig',
                'lint:yaml',
            ],
            'server' => [
                'server:run',
                'server:start',
                'server:status',
                'server:stop',
            ],
            'translation' => [
                'translation:update',
            ],
        ];

        $defaults = [];
        if (isset($map[$name])) {
            $defaults = $map[$name];
        }

        $node
            ->canBeDisabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('commands')
                    ->defaultValue($defaults)
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
