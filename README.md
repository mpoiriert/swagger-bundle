# draw-swagger-bundle
Integration for draw/swagger into symfony 4 bundle

The first objective is to be able to generate a swagger documentation with minimum effort by the programmer.
The draw/swagger provide a multitude of extractor to get the information where it can (PHP for example).

The integration with symfony allow you to use must of the **Draw\Swagger\Schema** (alias @Swagger) as annotation above 
your controller method to document them.

The bundle also provide some tools to provide a rest api without the need of FOSRestBundle.

**FOSRestBundle integration is provided by the DrawSwaggerBundle but it will be removed to reduce the scope of this
bundle.**

## Configuration

Here is a example of the configuration:

```
draw_swagger: 
    enableFosRestSupport: null
    enableDoctrineSupport: null #null will auto detect if DoctrineBundle is install and consider it true
    convertQueryParameterToAttribute: false
    responseConverter: false
    definitionAliases:
        - class: App\Entity\MyUser #This will change reference to class App\Entity\MyUser as User in swagger
          alias: User
        - class: App\Entity\ #This will Remove App\Entity\ from namespace so the class name would be expose instead of the FQCN
          alias: ''
        
    schema: #The schema section is not validate but it must match swagger format and will be the starting point of the generated doc
        info:
            title: 'Documentation for Acme API'
            description: 'This is the descriptoin of the 'Acme API'
            termsOfService: 'N\A'
            contact: ~
            version: "5.0"
```

## Controller documentation

To document a controller for swagger you must use the @Swagger\Tag or @Swagger\Operation annotations.

```
/**
 * @Swagger\Tag("Acme")
 */
public function defaultAction()
{
   //...
}
```

```
/**
 * @Swagger\Operation(
 *     operationId="default",
 *     tags={"Acme"}
 * )
 */
public function defaultAction()
{
   //...
}
```

If you plan to use the swagger codegen we recommend using the @Swagger\Operation since it will give you control
over the **operationId**, otherwise it will use the route name.

### Query Parameters

If you want to inject configured query parameters in a controller you must set the **convertQueryParameterToAttribute**
to true in the configuration.

````YAML
draw_swagger:
  convertQueryParameterToAttribute: true
````

You must also add the annotation **Draw\Swagger\Schema\QueryParameter** to your controller. This will provide the documentation
information for swagger and also configure which query parameters should be injected.

```
/**
 * @Swagger\QueryParameter(name="param1")
 *
 * @param string $param1
 */
public function createAction($param1 = 'default')
{
   //...
}
```

### View

To allow the automatic serialization of response you must active it:

````YAML
draw_swagger:
  responseConverter: true
````

The will detect if the return value of your controller is not a response and will serialized it according
to the **Draw\SwaggerBundle\View\View** annotation.

By default if there is not annotation the serializer context will not have any value and the response will be 200.
Using the view allow to override the serializer groups and version, the status code of the response.
The View annotation is also use for the swagger documentation, the headers attribute is use for that.

If your controller return null the status code will be set to 204 by default (not content).

```
/**
 * @Draw\SwaggerBundle\View\View(
 *     statusCode=201,
 *     serializerGroups={"MyGroup"},
 *     headers={
 *       "X-Acme-CustomHeader"=@Swagger\Header(type="string", description="The is a custom header")
 *     }
 * )
 */
public function createAction()
{
   //...
}
```

The **Draw\SwaggerBundle\View\View** extends from **Sensio\Bundle\FrameworkExtraBundle\Configuration\Template**
so you can access it the same way by using the ```$request->attributes->get('_template');```.

Instead of putting a **serializerVersion** on each header you can create a listener that will set the
version base on something else. Here is a example of a listener that will take from the url path
**/api/v{number}/....**:

```PHP
<?php namespace App\Listener;

use Draw\SwaggerBundle\View\View;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VersionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
             //It must be after reading __template attribute but before the serializer listener pass
            KernelEvents::VIEW => ['onKernelView', 31] 
        ];
    }

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();

        $sections = explode('/', $pathInfo, 4);

        if(!isset($sections[2])) {
            return;
        }

        $version = trim($sections[2], 'v');

        if($sections[2] != ('v' . $version)) {
            return;
        }

        $view = $request->attributes->get('_template', new View([]));

        if($view instanceof View && is_null($view->getSerializerVersion())) {
            $view->setSerializerVersion($version);
        }

        $request->attributes->set('_template', $view);
    }
}
```