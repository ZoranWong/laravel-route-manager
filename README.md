# lara-routes-manager
lara-routes-manager is designed to make laravel framework works by a easy way to manage and create it's router system.All of your application routes which will implement the RouteGenerator's auth and normal abstract methods to be organized. The auth method is used to organize routes which need authenticate, and the normal method organize routes which not need authenticate.
Example:
````php
    use ZoranWang\LaraRoutesManager\RouteGenerator;
    class WebRouteGenerator extends RouteGenerator
     {
        protected function auth($router)
        {
            $router->get('/user', ['as' => 'index', 'uses' => 'IndexController@user']);
        }
        
        protected function normal($router)
        {
            $router->get('/', ['as' => 'index', 'uses' => 'IndexController@index']);
        }
    }
```` 
You can use this package like that, it so easy!
