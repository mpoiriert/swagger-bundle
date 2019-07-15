# draw-swagger-bundle
Integration for draw/swagger into symfony 4 bundle

The first objective is to be able to generate a swagger documentation with minimum effort by the programmer.
The draw/swagger provide a multitude of extractor to get the information where it can (PHP for example).

The integration with symfony allow you to use must of the **Draw\Swagger\Schema** (alias @Swagger) as annotation above 
your controller method to document them.

The bundle also provide some tools to provide a rest api without the need of FOSRestBundle.

FOSRestBundle integration is provided by the DrawSwaggerBundle but it will be removed to reduce the scope of this
bundle.

## Controller documentation

To document a controller for swagger you must use the @Swagger\Tag or @Swagger\Operation annotations.

````
/**
 * @Swagger\Tag("Acme")
 */
public function defaultAction()
{
   //...
}
````

````
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
````

If you plan to use the swagger codegen we recommend using the @Swagger\Operation since it will give you control
over the **operationId**, otherwise it will use the route name.

### Query Parameters

If you want to inject configured query parameters in a controller you must set the **convertQueryParameterToAttribute**
to true in the configuration.

````YAML
draw_swagger:
  convertQueryParameterToAttribute: true
````

You must also add the annotation @Draw\Swagger\Schema\QueryParameter to your controller. This will provide the documentation
information for swagger and also configure which query parameters should be injected.

````
/**
 * @Swagger\QueryParameter(name="param1")
 *
 * @param string $param1
 */
public function createAction($param1 = 'default')
{
   //...
}
````

To use swagger with the api_key as the authorization token for lexik jwt authentication just enable the query_parameter:

```YAML
security:
    firewalls:
        api:
            pattern:   ^/api/
            stateless: true
            lexik_jwt:
                query_parameter:
                    enabled: true
                    name: api_key

```
