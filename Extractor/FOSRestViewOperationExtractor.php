<?php

namespace Draw\SwaggerBundle\Extractor;

use Doctrine\Common\Annotations\Reader;
use Draw\Swagger\Extraction\ExtractionContextInterface;
use Draw\Swagger\Extraction\ExtractionImpossibleException;
use Draw\Swagger\Extraction\ExtractorInterface;
use Draw\Swagger\Schema\Schema;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use ReflectionMethod;

class FOSRestViewOperationExtractor implements ExtractorInterface
{
    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    /**
     * Return if the extractor can extract the requested data or not.
     *
     * @param $source
     * @param $target
     * @param ExtractionContextInterface $extractionContext
     * @return boolean
     */
    public function canExtract($source, $target, ExtractionContextInterface $extractionContext)
    {
        if(!$target instanceof Schema) {
            return false;
        }

        if(!$extractionContext->hasParameter('controller-reflection-method')) {
            return false;
        }

        return true;
    }

    /**
     * Extract the requested data.
     *
     * The system is a incrementing extraction system. A extractor can be call before you and you must complete the
     * extraction.
     *
     * @param \ReflectionMethod $source
     * @param Schema $target
     * @param ExtractionContextInterface $extractionContext
     */
    public function extract($source, $target, ExtractionContextInterface $extractionContext)
    {
        if (!$this->canExtract($source, $target, $extractionContext)) {
            throw new ExtractionImpossibleException();
        }

        $groups = array();

        if($view = $this->getView($extractionContext->getParameter('controller-reflection-method'))) {
            $groups = $view->getSerializerGroups();
        }

        if(empty($groups)) {
            $groups = array(GroupsExclusionStrategy::DEFAULT_GROUP);
        }

        $modelContext = $extractionContext->getParameter('model-context', []);
        $modelContext['serializer-groups'] = $groups;
        $extractionContext->setParameter('model-context', $modelContext);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return View|null
     */
    private function getView(ReflectionMethod $reflectionMethod)
    {
        $views = array_filter(
            $this->annotationReader->getMethodAnnotations($reflectionMethod),
            function ($annotation) {
                return $annotation instanceof View;
            }
        );

        if($views) {
            return reset($views);
        }

        return null;
    }
}