<?php namespace Draw\SwaggerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class JmsTypeHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $swagger = $container->getDefinition('draw.swagger.extractor.jms_extractor');

        foreach (array_keys($container->findTaggedServiceIds("swagger.jms_type_handler")) as $id) {
            if ($container->getDefinition($id)->isAbstract()) {
                continue;
            }

            $swagger->addMethodCall("registerTypeToSchemaHandler", [new Reference($id)]);
        }
    }
}