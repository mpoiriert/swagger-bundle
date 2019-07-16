<?php namespace Draw\SwaggerBundle\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Annotation
 */
class DeserializeBody extends ParamConverter
{
    /**
     * The groups use for deserialization
     *
     * @var array
     */
    private $deserializationGroups;

    /**
     * @var boolean
     */
    private $deserializationEnableMaxDepth;

    /**
     * If we must validate the deserialized object
     *
     * @var boolean
     */
    private $validate;

    /**
     * The validation groups to use if we do perform a validation
     *
     * @var array
     */
    private $validationGroups;

    public function __construct(array $values)
    {
        $values['converter'] = $values['converter'] ?? 'draw.request_body';
        parent::__construct($values);
    }

    /**
     * @return bool
     */
    public function getValidate()
    {
        return $this->getOptions()['validate'] ?? null;
    }

    /**
     * @param bool $validate
     */
    public function setValidate($validate)
    {
        $options = $this->getOptions();
        $options['validate'] = $validate;
        $this->setOptions($options);
    }

    /**
     * @return array
     */
    public function getDeserializationGroups()
    {
        return $this->getDeserializationContextOptions('groups', null);
    }

    /**
     * @param array $deserializationGroups
     */
    public function setDeserializationGroups($deserializationGroups)
    {
        $deserializationGroups = (array)$deserializationGroups;
        $this->setDeserializationContextOptions('groups', $deserializationGroups);
    }

    /**
     * @return bool
     */
    public function getDeserializationEnableMaxDepth()
    {
        return $this->getDeserializationContextOptions('enableMaxDepth', null);
    }

    /**
     * @param bool $deserializationEnableMaxDepth
     */
    public function setDeserializationEnableMaxDepth($deserializationEnableMaxDepth)
    {
        $this->setDeserializationContextOptions('enableMaxDept', $deserializationEnableMaxDepth);
    }

    private function setDeserializationContextOptions($name, $value)
    {
        $options = $this->getOptions();
        $options['deserializationContext'][$name] = $value;
        $this->setOptions($options);
    }

    private function getDeserializationContextOptions($name, $default = null)
    {
        $options = $this->getOptions();
        return $options['deserializationContext'][$name] ?? $default;
    }
}