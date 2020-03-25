<?php namespace Draw\SwaggerBundle\DependencyInjection\Compiler;

use Draw\Swagger\Extraction\Extractor\JmsExtractor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class JmsTypeHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $swagger = $container->getDefinition(JmsExtractor::class);

        foreach (array_keys($container->findTaggedServiceIds("swagger.jms_type_handler")) as $id) {
            $swagger->addMethodCall("registerTypeToSchemaHandler", [new Reference($id)]);
        }
    }
}