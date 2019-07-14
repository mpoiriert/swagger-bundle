<?php namespace Draw\SwaggerBundle\Tests;

use Draw\HttpTester\Bridge\Symfony4\Symfony4TestContextInterface;
use Draw\HttpTester\HttpTesterTrait;
use Draw\HttpTester\Request\DefaultValueObserver;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase implements Symfony4TestContextInterface
{
    use HttpTesterTrait;

    public function getTestClient()
    {
        return static::createClient();
    }

    protected function newClient()
    {
        $client = $this->getBridgeClientFactory()->createClient();
        $client->registerObserver(
            new DefaultValueObserver(
                [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            )
        );

        return $client;
    }
}