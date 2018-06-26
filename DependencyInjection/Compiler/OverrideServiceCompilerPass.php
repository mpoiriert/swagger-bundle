<?php

namespace Draw\SwaggerBundle\DependencyInjection\Compiler;

use Draw\SwaggerBundle\Routing\Loader\Reader\RestControllerReader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
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
        if(class_exists(\FOS\RestBundle\Routing\Loader\Reader\RestControllerReader::class)) {

            $definition = null;
            if($container->hasDefinition(\FOS\RestBundle\Routing\Loader\Reader\RestControllerReader::class)) {
                $definition = $container->getDefinition(\FOS\RestBundle\Routing\Loader\Reader\RestControllerReader::class);
            } elseif($container->hasDefinition('fos_rest.routing.loader.reader.controller')) {
                $definition = $container->getDefinition('fos_rest.routing.loader.reader.controller');
            }
            if(!is_null($definition)) {
                $definition->setClass(RestControllerReader::class);
            }
        }
    }
}