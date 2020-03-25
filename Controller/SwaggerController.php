<?php namespace Draw\SwaggerBundle\Controller;

use Draw\Swagger\Swagger;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SwaggerController
{
    public function apiDocAction(
        Swagger $swagger,
        Request $request,
        UrlGeneratorInterface $urlGenerator,
        ParameterBagInterface $parameterBag,
        ContainerInterface $container
    ) {
        if ($request->getRequestFormat() != 'json') {
            $currentRoute = $request->attributes->get('_route');
            $currentUrl = $urlGenerator->generate($currentRoute, array('_format' => 'json'), true);
            return new RedirectResponse('http://petstore.swagger.io/?url=' . $currentUrl);
        }

        $schema = $swagger->extract(json_encode($parameterBag->get("draw_swagger.schema")));
        $schema = $swagger->extract($container, $schema);
        $jsonSchema = $swagger->dump($schema);

        return new JsonResponse($jsonSchema, 200, [], true);
    }
}