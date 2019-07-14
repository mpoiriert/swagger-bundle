<?php

namespace Draw\SwaggerBundle\Tests\Mock\Controller;

use Draw\Swagger\Schema as Swagger;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param \Draw\SwaggerBundle\Tests\Mock\Model\Test $object Object parameter
     *
     * @return \Draw\SwaggerBundle\Tests\Mock\Model\Test
     */
    public function getAction($object, $id, $filter = null)
    {

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
     * @param string $param1
     */
    public function createAction($param1 = 'default')
    {
        return new JsonResponse(compact('param1'));
    }
}