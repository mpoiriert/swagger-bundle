<?php namespace Draw\SwaggerBundle\DependencyInjection;

use Draw\Swagger\Extraction\Extractor\JmsSerializer\TypeToSchemaHandlerInterface;
use Draw\Swagger\Extraction\Extractor\TypeSchemaExtractor;
use Draw\Swagger\Extraction\ExtractorInterface;
use Draw\Swagger\Swagger;
use Draw\SwaggerBundle\Listener\ResponseConverterSubscriber;
use Draw\SwaggerBundle\Request\DeserializeBody;
use Draw\SwaggerBundle\Request\RequestBodyParamConverter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
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
            ->registerForAutoconfiguration(ExtractorInterface::class)
            ->addTag('swagger.extractor');

        $container
            ->registerForAutoconfiguration(TypeToSchemaHandlerInterface::class)
            ->addTag('swagger.jms_type_handler');

        $container->setParameter("draw_swagger.schema", $config['schema']);
        $container->setParameter(
            'draw_swagger.dir',
            dirname((new \ReflectionClass(Swagger::class))->getFileName())
        );

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new XmlFileLoader($container, $fileLocator);

        $loader->load('swagger.xml');

        $container
            ->getDefinition(Swagger::class)
            ->addMethodCall('setCleanOnDump', [$config['cleanOnDump']]);

        $definition = $container->getDefinition(TypeSchemaExtractor::class);

        foreach ($config['definitionAliases'] as $alias) {
            $definition->addMethodCall(
                'registerDefinitionAlias',
                [$alias['class'], $alias['alias']]
            );
        }

        $this->configDoctrine($config['doctrine'], $loader, $container);

        if($config['convertQueryParameterToAttribute']) {
            $loader->load('query_parameter_fetcher.xml');
        }

        if ($config['responseConverter']['enabled']) {
            $loader->load('response_converter.xml');
            $container
                ->getDefinition(ResponseConverterSubscriber::class)
                ->setArgument('$serializeNull', $config['responseConverter']['serializeNull']);
        }

        $container
            ->getDefinition(RequestBodyParamConverter::class)
            ->setArgument(
                '$defaultConfiguration',
                new Definition(
                    DeserializeBody::class,
                    [$config['requestBodyParamConverter']['defaultDeserializationConfiguration']]
                )
            );
    }

    private function configDoctrine(array $config, LoaderInterface $loader, ContainerBuilder $container)
    {
        if (!$config['enabled']) {
            return;
        }

        $loader->load('doctrine.xml');
    }
}