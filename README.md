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
- Config of lara-routes-manager
    - root
        
        The directory of the route's files
    - namespace
        
        The namespace of route generator class we create
    - domains
        
        Domain's config array 
        - domain
            
            Domain name
        - request
            
            Request alias we used in this package
        - router
            
            Router alias we used in this package
        - providers
            
            Providers to be used under this domain when the application run
        - middleware
            
            Middleware to be used under this domain when the application run
        - auth
        
            The default auth guard  to be used under this domain if we do not config in gateway and route config 
        - gateways
        
            The gateway conception is a alias of prefix for laravel router's prefix. Gateways is a array which group routes by prefix.
            - gateway 
            
                The prefix of laravel route
            - providers
                
                Providers to be used under this gateway when the application run
            - middleware
            
                Middleware to be used under this domain when the application run
            - auth
            
               This auth guard will cover the auth which config in domain 
            - routes
                - generator
                
                    This is a class name which route's rules generate in. 
                - providers
                
                    Providers to be used under this Route when the application run
                - middleware
                
                    Middleware to be used under this Route when the application run
                - auth
                
                     This auth guard will cover the auth which config in gateway  config 
                - version
                - namespace
                
                    The namespace of controllers.
                
- Command of lara-routes-manager 

```php
      // router the namespace of router to be used .  
      php artisan route-generator:create {name} {--router}
  
```

You can use this package like that, it so easy!
