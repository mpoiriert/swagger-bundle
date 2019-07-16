<?php namespace Draw\SwaggerBundle\Tests\Request;

use Draw\DataTester\Tester;
use Draw\SwaggerBundle\Tests\TestCase;

/**
 * This is a integration test but mainly to test the RequestBodyParamConverter.
 * It base itself on the configuration of the AppKernel and the Mock TestController
 */
class RequestBodyParamConverterTest extends TestCase
{
    public function testApply()
    {
        self::$client
            ->post(
                '/tests',
                json_encode([
                    'property_from_body' => 'propertyValue'
                ])
                )
            ->toJsonDataTester()
            ->path('property_from_body')
            ->assertSame('propertyValue');
    }

    public function testApply_failValidation()
    {
        self::$client
            ->post(
                '/tests',
                json_encode([
                    'property_from_body' => 'invalidValue'
                ])
            )
            ->assertStatus(400)
            ->toJsonDataTester()
            ->path('errors')
            ->assertCount(1)
            ->path('[0]')
            ->assertEquals((object)[
                'propertyPath' => 'propertyFromBody',
                'message' => 'This value should not be equal to "invalidValue".',
                'invalidValue' => 'invalidValue',
                'code' => 'aa2e33da-25c8-4d76-8c6c-812f02ea89dd'
            ]);

    }
}