<?php namespace Draw\SwaggerBundle\Tests;

use Draw\HttpTester\HttpTesterTrait;
use Draw\Swagger\Swagger;

class DrawSwaggerBundleTest extends TestCase
{
    use HttpTesterTrait;

    public function testGetService()
    {
        $swagger = static::createClient()->getContainer()->get(Swagger::class);

        $this->assertInstanceOf(Swagger::class, $swagger);

        return $swagger;
    }
}