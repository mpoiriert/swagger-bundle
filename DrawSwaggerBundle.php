<?php namespace Draw\SwaggerBundle;

use Draw\SwaggerBundle\DependencyInjection\Compiler\ExtractorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DrawSwaggerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ExtractorCompilerPass());
    }
}