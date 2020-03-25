<?php namespace Draw\SwaggerBundle\Tests\Mock\Controller;

use Draw\SwaggerBundle\Request\DeserializeBody;
use Draw\Swagger\Schema as Swagger;
use Draw\SwaggerBundle\Tests\Mock\Model\Test;
use Draw\SwaggerBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
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
     * @View(
     *     statusCode=201,
     *     serializerGroups={"Included"},
     *     headers={
     *       "X-Draw":@Swagger\Header(type="string", description="Description of the header")
     *     }
     * )
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