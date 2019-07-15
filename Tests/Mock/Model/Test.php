<?php

namespace Draw\SwaggerBundle\Tests\Mock\Model;

use JMS\Serializer\Annotation as Serializer;

class Test
{
    /**
     * Property description
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({"Included"})
     *
     * @var string
     */
    private $property;

    /**
     * Will be excluded because of the group
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({"Excluded"})
     */
    private $propertyGroupExclusion;

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }
}