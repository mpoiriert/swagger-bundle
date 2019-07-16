<?php namespace Draw\SwaggerBundle\Listener;

use Draw\SwaggerBundle\View\View;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseConverterSubscriber implements EventSubscriberInterface
{
    /**
     * @var SerializationContextFactoryInterface
     */
    private $serializationContextFactory;

    /**
     * If we must serialize null
     *
     * @var boolean
     */
    private $serializeNull;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public static function getSubscribedEvents()
    {
        // Must be executed before SensioFrameworkExtraBundle's listener
        return [
            KernelEvents::VIEW => ['onKernelView', 30]
        ];
    }

    public function __construct(
        SerializerInterface $serializer,
        SerializationContextFactoryInterface $serializationContextFactory,
        $serializeNull
    )
    {
        $this->serializationContextFactory = $serializationContextFactory;
        $this->serializer = $serializer;
        $this->serializeNull = $serializeNull;
    }

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();

        if ($result instanceof Response) {
            return;
        }

        if(!($contentTypes = $request->getAcceptableContentTypes())) {
            return;
        }

        switch ($requestFormat = $request->getFormat($contentTypes[0])) {
            case 'json':
            case 'xml':
                break;
            default:
                return;
        }

        if (is_null($result)) {
            $event->setResponse(new Response('', 204));
            return;
        }

        $context = $this->serializationContextFactory->createSerializationContext();
        $context->setSerializeNull($this->serializeNull);

        // If we have a view annotation set via the controller configuration it will be available under _template
        // This is were symfony store the template attribute and since the view extend from template it will be
        // stored there.
        $view = $request->attributes->get('_template');

        if($view instanceof View) {
            if($version = $view->getSerializerVersion()) {
                $context->setVersion($version);
            }

            if($groups = $view->getSerializerGroups()) {
                $context->setGroups($groups);
            }
        }

        $data = $this->serializer->serialize($result, $requestFormat, $context);
        $response = new JsonResponse($data, 200, ['Content-Type' => 'application/' . $requestFormat], true);

        if($view instanceof View && $view->getStatusCode()) {
            $response->setStatusCode($view->getStatusCode());
        }

        $event->setResponse($response);
    }
}