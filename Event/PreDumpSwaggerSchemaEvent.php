<?php namespace Draw\SwaggerBundle\Event;

use Draw\Swagger\Schema\Swagger;
use Symfony\Contracts\EventDispatcher\Event;

class PreDumpSwaggerSchemaEvent extends Event
{
    private $schema;

    public function __construct(Swagger $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return Swagger
     */
    public function getSchema()
    {
        return $this->schema;
    }
}