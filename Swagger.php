<?php namespace Draw\SwaggerBundle;

use Draw\Swagger\Schema\Swagger as Schema;
use Draw\SwaggerBundle\Event\PreDumpSwaggerSchemaEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Swagger extends \Draw\Swagger\Swagger
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @required
     *
     * @param EventDispatcherInterface|null $eventDispatcher
     */
    public function setEventDispatcher(?EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dump(Schema $schema, $validate = true)
    {
        if($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(new PreDumpSwaggerSchemaEvent($schema));
        }

        return parent::dump($schema, $validate);
    }
}