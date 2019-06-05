<?php

namespace Draw\SwaggerBundle\DependencyInjection;

use Draw\Swagger\Extraction\Extractor\DoctrineInheritanceExtractor;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DrawSwaggerExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter("draw_swagger.schema", $config['schema']);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $fileLocator);

        // The order that extractor get registered is important so th fos_rest.yml must be loaded first
        if(class_exists(\FOS\RestBundle\Routing\Loader\Reader\RestControllerReader::class)) {
            $loader->load('fos_rest.yml');
        }

        $loader->load('swagger.yml');

        $definition = $container->getDefinition("draw.swagger.extrator.type_schema_extractor");

        foreach ($config['definitionAliases'] as $alias) {
            $definition->addMethodCall(
                'registerDefinitionAlias',
                [$alias['class'], $alias['alias']]
            );
        }

        if(class_exists(EntityManager::class)) {
            $container->setDefinition(
                DoctrineInheritanceExtractor::class,
                $definition = new Definition(DoctrineInheritanceExtractor::class, [new Reference('doctrine')])
            );

            $definition->addTag('swagger.extractor');
        }
    }
}