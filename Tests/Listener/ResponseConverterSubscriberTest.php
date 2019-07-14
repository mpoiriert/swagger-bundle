<?php namespace Draw\SwaggerBundle\Tests\Listener;

use Draw\SwaggerBundle\Tests\Mock\Controller\TestController;
use Draw\SwaggerBundle\Tests\TestCase;

/**
 * This is a integration test but mainly to test the ResponseConverterSubscriberTest.
 * It base itself on the configuration of the AppKernel and the Mock TestController
 */
class ResponseConverterSubscriberTest extends TestCase
{
    /**
     * @see TestController::createAction()
     */
    public function testOnKernelView()
    {
        self::$client
            ->post('/tests', '')
            ->assertStatus(201);
    }
}