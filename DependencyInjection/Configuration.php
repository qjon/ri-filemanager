<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ri_file_manager');

        $rootNode
            ->children()
                ->variableNode('upload_dir')
                ->defaultValue('/uploads')
            ->end();
        $rootNode
            ->children()
                ->booleanNode('resize')
                ->defaultValue(true)
            ->end();
        $rootNode
            ->children()
                ->integerNode('resize_max_width')
                ->defaultValue(1024)
            ->end();
        $rootNode
            ->children()
                ->arrayNode('dimensions')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('width')->end()
                            ->scalarNode('height')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        $rootNode
            ->children()
            ->booleanNode('allow_change_language')
            ->defaultValue(true)
            ->end();
        $rootNode
            ->children()
            ->variableNode('default_language')
            ->defaultValue('en_EN')
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
