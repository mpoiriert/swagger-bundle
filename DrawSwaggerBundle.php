<?php namespace Draw\SwaggerBundle;

use Draw\SwaggerBundle\DependencyInjection\Compiler\ExtractorCompilerPass;
use Draw\SwaggerBundle\DependencyInjection\Compiler\JmsTypeHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DrawSwaggerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ExtractorCompilerPass());
        $container->addCompilerPass(new JmsTypeHandlerCompilerPass());
    }
}