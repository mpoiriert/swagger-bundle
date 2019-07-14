<?php

namespace Draw\SwaggerBundle\DependencyInjection;

use Draw\DrawBundle\Config\Definition\Builder\AllowExtraPropertiesNodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('draw_swagger');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode->setBuilder(new AllowExtraPropertiesNodeBuilder());

        $rootNode->children()
            ->booleanNode('cleanOnDump')
                ->defaultTrue()
            ->end()
            ->booleanNode('enableFosRestSupport')
                ->defaultNull()
            ->end()
            ->booleanNode('enableDoctrineSupport')
                ->defaultNull()
            ->end()
            ->booleanNode('convertQueryParameterToAttribute')
                ->defaultFalse()
            ->end()
            ->arrayNode('definitionAliases')
                ->defaultValue(array())
                ->prototype("array")
                    ->children()
                        ->scalarNode("class")->isRequired()->end()
                        ->scalarNode("alias")->isRequired()->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('schema')
            ->normalizeKeys(false)
            ->acceptExtraKeys(true)
                ->children()
                    ->arrayNode("info")
                        ->children()
                            ->scalarNode("version")->defaultValue("1.0")->end()
                            ->scalarNode("contact")->end()
                            ->scalarNode("termsOfService")->end()
                            ->scalarNode("description")->end()
                            ->scalarNode("title")->end()
                        ->end()
                    ->end()
                    ->scalarNode("basePath")->end()
                    ->scalarNode("swagger")->defaultValue("2.0")->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
