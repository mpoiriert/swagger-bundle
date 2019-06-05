<?php

namespace Draw\SwaggerBundle\DependencyInjection;

use Draw\Swagger\Extraction\Extractor\DoctrineInheritanceExtractor;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class DrawSwaggerExtension extends ConfigurableExtension
{
    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
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