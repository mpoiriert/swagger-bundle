<?php namespace Draw\SwaggerBundle\Tests\Listener;

use Draw\SwaggerBundle\Tests\TestCase;

/**
 * This is a integration test but mainly to test the QueryParameterFetcherSubscriber.
 * It base itself on the configuration of the AppKernel and the Mock TestController
 */
class QueryParameterFetcherSubscriberTest extends TestCase
{
    public function testOnKernelController_withValue()
    {
        self::$client
            ->post('/tests?param1=toto', '')
            ->assertStatus(200)
            ->toJsonDataTester()
            ->path('param1')
            ->assertSame('toto');
    }

    public function testOnKernelController_defaultValue()
    {
        self::$client
            ->post('/tests', '')
            ->assertStatus(200)
            ->toJsonDataTester()
            ->path('param1')
            ->assertSame('default');
    }
}