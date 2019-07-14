<?php namespace Draw\SwaggerBundle\DependencyInjection;

use Draw\Swagger\Extraction\Extractor\JmsSerializer\TypeToSchemaHandlerInterface;
use Draw\Swagger\Swagger;
use Draw\SwaggerBundle\Extractor\JmsSerializer\ReferenceTypeToSchemaHandler;
use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $container
            ->registerForAutoconfiguration(TypeToSchemaHandlerInterface::class)
            ->addTag('swagger.jms_type_handler');

        $container->setParameter("draw_swagger.schema", $config['schema']);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $fileLocator);

        // The order that extractor get registered is important so th fos_rest.yml must be loaded first
        $this->loadConditionalBundleFile(
            $config,
            $container,
            'FOSRestBundle',
            $loader,
            'fos_rest.yaml',
            'enableFosRestSupport'
        );

        $loader->load('swagger.yaml');

        $container->getDefinition(Swagger::class)
            ->addMethodCall('setCleanOnDump', [$config['cleanOnDump']]);

        $definition = $container->getDefinition("draw.swagger.extractor.type_schema_extractor");

        foreach ($config['definitionAliases'] as $alias) {
            $definition->addMethodCall(
                'registerDefinitionAlias',
                [$alias['class'], $alias['alias']]
            );
        }

        $doctrineSupportEnabled = $this->loadConditionalBundleFile(
            $config,
            $container,
            'DoctrineBundle',
            $loader,
            'doctrine.yaml',
            'enableDoctrineSupport'
        );

        if ($doctrineSupportEnabled) {
            $container
                ->getDefinition('draw.swagger.extractor.jms_extractor')
                ->addMethodCall('registerTypeToSchemaHandler', [new Reference(ReferenceTypeToSchemaHandler::class)]);
        }
    }

    private function loadConditionalBundleFile(
        $config,
        ContainerBuilder $container,
        $bundleName,
        LoaderInterface $loader,
        $file,
        $configurationName
    ) {
        $bundles = $container->getParameter('kernel.bundles');
        switch (true) {
            case is_null($config['enableFosRestSupport']) && isset($bundles[$bundleName]):
            case $config[$configurationName]:
                if (!isset($bundles[$bundleName])) {
                    throw new RuntimeException(
                        sprintf(
                            '%s is not enabled while draw_swagger.%s is set to true. Remove draw_swagger.%s node or set it to false',
                            $bundleName,
                            $configurationName,
                            $configurationName
                        )
                    );
                }
                $loader->load($file);
                return true;
        }

        return false;
    }
}