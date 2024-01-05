<?php

declare(strict_types=1);

namespace Yokai\SafeCommandBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree = new TreeBuilder('yokai_safe_command');
        $root = $tree->getRootNode();

        $root
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('standard')
                    ->defaultValue([
                        'config:dump-reference',
                        'doctrine:database:drop',
                        'doctrine:mapping:convert',
                        'doctrine:mapping:import',
                        'doctrine:schema:drop',
                        'doctrine:schema:validate',
                        'debug:autowiring',
                        'debug:config',
                        'debug:container',
                        'debug:dotenv',
                        'debug:event-dispatcher',
                        'debug:firewall',
                        'debug:form',
                        'debug:router',
                        'debug:serializer',
                        'debug:translation',
                        'debug:twig',
                        'debug:validator',
                        'lint:container',
                        'lint:twig',
                        'lint:xliff',
                        'lint:yaml',
                        'server:dump',
                        'server:log',
                        'translation:extract',
                        'translation:pull',
                        'translation:push',
                    ])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('custom')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $tree;
    }

    private function createEnvironmentsNode(): NodeDefinition
    {
        $node = (new TreeBuilder('environments'))->getRootNode();

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

    private function createCommandsNode(string $name): NodeDefinition
    {
        $node = (new TreeBuilder($name))->getRootNode();

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
                'debug:autowiring',
                'debug:config',
                'debug:container',
                'debug:dotenv',
                'debug:event-dispatcher',
                'debug:firewall',
                'debug:form',
                'debug:router',
                'debug:serializer',
                'debug:translation',
                'debug:twig',
                'debug:validator',
            ],
            'lint' => [
                'lint:container',
                'lint:twig',
                'lint:xliff',
                'lint:yaml',
            ],
            'server' => [
                'server:dump',
                'server:log',
            ],
            'translation' => [
                'translation:extract',
                'translation:pull',
                'translation:push',
            ],
        ];

        $node
            ->canBeDisabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('commands')
                    ->defaultValue($map[$name] ?? [])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
