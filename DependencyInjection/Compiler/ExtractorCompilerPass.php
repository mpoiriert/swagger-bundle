<?php

namespace Draw\SwaggerBundle\DependencyInjection\Compiler;

use Draw\Swagger\Swagger;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExtractorCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $swagger = $container->getDefinition(Swagger::class);

        foreach (array_keys($container->findTaggedServiceIds("swagger.extractor")) as $id) {
            if ($container->getDefinition($id)->isAbstract()) {
                continue;
            }

            $swagger->addMethodCall("registerExtractor", [new Reference($id)]);
        }
    }
}