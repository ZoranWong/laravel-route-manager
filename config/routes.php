<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 1:08 PM
 */
return [
    "domains" => [
        [
            'domain' => env('API_DOMAIN', 'api'),
            'providers' => [],
            'middleware' => [],
            'auth' => null,
            'protocols' => null,
            'ports' => null,
            'gateways' =>[
                [
                    'gateway' => env('EXAMPLE_API_GATEWAY', 'example'),
                    'providers' => [],
                    'middleware' => ['gateway'],
                    'routes' =>[
                        [
                            'router' => env('API_ROUTER', 'router'),
                            'request' => env('API_REQUEST', 'request'),
                            'generator' => '',
                            'namespace' => null,
                            'auth' => null,
                            'middleware' => [],
                            'providers' => [],
                            'version' => 'v1'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'root' => 'app/Routes',
    'namespace' => 'App\\Routes',
    'adapters' => [
        '\Illuminate\Routing\Router' => '\ZoranWang\LaraRoutesManager\Adapters\LaravelRouterAdapter'
    ]
];
