<?php namespace Draw\SwaggerBundle\Tests\Listener;

use Draw\SwaggerBundle\Tests\Mock\Controller\TestController;
use Draw\SwaggerBundle\Tests\TestCase;

/**
 * This is a integration test but mainly to test the QueryParameterFetcherSubscriber.
 * It base itself on the configuration of the AppKernel and the Mock TestController
 */
class QueryParameterFetcherSubscriberTest extends TestCase
{
    /**
     * @see TestController::createAction()
     */
    public function testOnKernelController_withValue()
    {
        self::$client
            ->post('/tests?param1=toto', '')
            ->toJsonDataTester()
            ->path('property')
            ->assertSame('toto');
    }

    /**
     * @see TestController::createAction()
     */
    public function testOnKernelController_defaultValue()
    {
        self::$client
            ->post('/tests', '')
            ->toJsonDataTester()
            ->path('property')
            ->assertSame('default');
    }
}