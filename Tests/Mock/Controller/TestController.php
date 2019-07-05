<?php

namespace Draw\SwaggerBundle\Tests\Mock\Controller;

use Draw\Swagger\Schema as Swagger;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends FOSRestController
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
     */
    public function createAction()
    {

    }
}