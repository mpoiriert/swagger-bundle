<?php namespace Draw\SwaggerBundle\Bridge\Doctrine\Extractor\JmsSerializer;

use Doctrine\Persistence\ManagerRegistry;
use Draw\Swagger\Extraction\ExtractionContextInterface;
use Draw\Swagger\Extraction\Extractor\JmsSerializer\TypeToSchemaHandlerInterface;
use Draw\Swagger\Schema\Schema;
use JMS\Serializer\Metadata\PropertyMetadata;

class ReferenceTypeToSchemaHandler implements TypeToSchemaHandlerInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function extractSchemaFromType(
        PropertyMetadata $propertyMetadata,
        ExtractionContextInterface $extractionContext
    ) {
        if (is_null($type = $this->getReferenceType($propertyMetadata))) {
            return null;
        }

        $propertySchema = new Schema();
        $propertySchema->type = $type;

        return $propertySchema;
    }

    private function getReferenceType(PropertyMetadata $item)
    {
        switch (true) {
            case !isset($item->type['name']):
            case $item->type['name'] != 'ObjectReference':
            case !isset($item->type['params'][0]['name']):
                return null;
        }

        $class = $item->type['params'][0]['name'];
        $metadataFor = $this->managerRegistry->getManagerForClass($class)
            ->getMetadataFactory()
            ->getMetadataFor($class);

        return $metadataFor->getTypeOfField($metadataFor->getIdentifierFieldNames()[0]);
    }
}