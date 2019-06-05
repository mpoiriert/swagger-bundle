<?php namespace Draw\SwaggerBundle\Tests\Controller;

use Draw\DataTester\AgainstJsonFileTester;
use Draw\SwaggerBundle\Tests\TestCase;

class SwaggerControllerTest extends TestCase
{
    public function testApiDocAction()
    {
        static::$client
            ->get('/api-doc')
            ->assertStatus(302)
            ->assertHeader('Location', 'http://petstore.swagger.io/?url=/api-doc.json');
    }

    public function testApiDocAction_json()
    {
        static::$client
            ->get('/api-doc.json')
            ->assertStatus(200)
            ->toJsonDataTester()
            ->test(
                new AgainstJsonFileTester(__DIR__ . '/fixtures/SwaggerControllerTest_testApiDocAction_json.json')
            );
    }
}