<?php namespace Draw\SwaggerBundle\Extractor;

use Doctrine\Common\Annotations\Reader;
use Draw\Swagger\Extraction\ExtractionContextInterface;
use Draw\Swagger\Extraction\ExtractionImpossibleException;
use Draw\Swagger\Extraction\ExtractorInterface;
use Draw\Swagger\Schema\Schema;
use Draw\SwaggerBundle\View\View;
use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use ReflectionMethod;

class ViewExtractor implements ExtractorInterface
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
        if (!$target instanceof Schema) {
            return false;
        }

        if (!$extractionContext->hasParameter('controller-reflection-method')) {
            return false;
        }

        if (!$this->getView($extractionContext->getParameter('controller-reflection-method'))) {
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

        $groups = [];

        if ($view = $this->getView($extractionContext->getParameter('controller-reflection-method'))) {
            $groups = $view->getSerializerGroups();
        }

        if (empty($groups)) {
            $groups = [GroupsExclusionStrategy::DEFAULT_GROUP];
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
        /** @var View|null $view */
        $view = $this->annotationReader->getMethodAnnotation($reflectionMethod, View::class);
        return $view;
    }
}