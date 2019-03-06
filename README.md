# lara-routes-manager
lara-routes-manager is designed to make laravel framework works by a easy way to manage and create it's router system.All of your application routes will implement the RouteGenerator abstract class's auth and normal methods to organize. The auth method is used to organize the routes which need authenticate, and the normal method not need authenticate.
Example:
````php
    use ZoranWang\LaraRoutesManager\RouteGenerator;
    class WebRouteGenerator extends RouteGenerator
     {
        protected function auth($router)
        {
            $router->get('/', ['as' => 'index', 'uses' => 'IndexController@index']);
        }
    }
```` 
You can use this package like that, it so easy!
