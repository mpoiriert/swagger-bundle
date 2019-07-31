<?php namespace Draw\SwaggerBundle\View;

use Draw\Swagger\Schema\Header;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class View extends Template
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $serializerGroups;

    /**
     * @var bool
     */
    protected $serializerEnableMaxDepthChecks;

    /**
     * @var string
     */
    protected $serializerVersion;

    /**
     * @var Header[]|array
     */
    protected $headers;

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param array $serializerGroups
     */
    public function setSerializerGroups($serializerGroups)
    {
        $this->serializerGroups = $serializerGroups;
    }

    /**
     * @return array
     */
    public function getSerializerGroups()
    {
        return $this->serializerGroups;
    }

    /**
     * @param bool $serializerEnableMaxDepthChecks
     */
    public function setSerializerEnableMaxDepthChecks($serializerEnableMaxDepthChecks)
    {
        $this->serializerEnableMaxDepthChecks = $serializerEnableMaxDepthChecks;
    }

    /**
     * @return bool
     */
    public function getSerializerEnableMaxDepthChecks()
    {
        return $this->serializerEnableMaxDepthChecks;
    }

    /**
     * @return string
     */
    public function getSerializerVersion()
    {
        return $this->serializerVersion;
    }

    /**
     * @param string $serializerVersion
     */
    public function setSerializerVersion($serializerVersion)
    {
        $this->serializerVersion = $serializerVersion;
    }

    /**
     * @return Header[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param Header[] $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }
}