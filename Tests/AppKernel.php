<?php namespace Draw\SwaggerBundle\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Draw\SwaggerBundle\DrawSwaggerBundle(),
            new \FOS\RestBundle\FOSRestBundle(),
            new \Draw\DrawBundle\DrawDrawBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/fixtures/config/config.yml');
    }
}