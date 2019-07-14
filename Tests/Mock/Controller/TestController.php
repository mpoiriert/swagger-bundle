<?php

namespace Draw\SwaggerBundle\Tests\Mock\Controller;

use Draw\Swagger\Schema as Swagger;
use Draw\SwaggerBundle\Tests\Mock\Model\Test;
use Draw\SwaggerBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Swagger\Tag("Test")
     *
     * @FOS\Get("/tests/{id}")
     * @FOS\QueryParam(name="filter", description="fos description")
     * @FOS\RequestParam(name="object")
     *
     * @param string $id Php doc description
     * @param string $filter Should not be used since define in QueryParam
     * @param Test $object Object parameter
     *
     * @return Test
     */
    public function getAction($object, $id, $filter = null)
    {
        return new Test();
    }

    /**
     * @Route(methods={"POST"}, path="/tests")
     *
     * @Swagger\Operation(
     *     operationId="createTest",
     *     tags={"test"}
     * )
     *
     * @Swagger\QueryParameter(name="param1")
     *
     * @View(statusCode=201)
     *
     * @param string $param1
     *
     * @return Test
     */
    public function createAction($param1 = 'default')
    {
        $test = new Test();
        $test->setProperty($param1);

        return $test;
    }
}