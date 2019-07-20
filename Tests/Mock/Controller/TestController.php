<?php namespace Draw\SwaggerBundle\Tests\Mock\Controller;

use Draw\SwaggerBundle\Request\DeserializeBody;
use Draw\Swagger\Schema as Swagger;
use Draw\SwaggerBundle\Tests\Mock\Model\Test;
use Draw\SwaggerBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Swagger\Tag("Test")
     *
     * @FOS\Get("/tests/{id}")
     * @FOS\QueryParam(name="filter", description="fos description")
     * @ParamConverter(
     *     name="object",
     *     converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"Included"}}}
     * )
     *
     * @FOS\View(
     *     serializerGroups={"Included"}
     * )
     *
     * @param string $id Php doc description
     * @param string $filter Should not be used since define in QueryParam
     * @param Test $object Object parameter
     *
     * @return Test The loaded test entity
     */
    public function getAction(Test $object, $id, $filter = null)
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
     * @DeserializeBody(
     *     name="test",
     *     deserializationGroups={"Included"}
     * )
     *
     * @View(statusCode=201, serializerGroups={"Included"})
     *
     * @param string $param1
     * @param Test $test
     *
     * @return Test The created test entity
     */
    public function createAction(Test $test, $param1 = 'default')
    {
        $test->setProperty($param1);

        return $test;
    }
}