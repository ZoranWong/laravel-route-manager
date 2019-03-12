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
            'router' => env('API_ROUTER', 'router'),
            'request' => env('API_REQUEST', 'request'),
            'providers' => [],
            'middleware' => [],
            'auth' => null,
            'protocols' => null,
            'ports' => null,
            'gateways' =>[
                [
                    'gateway' => env('EXAMPLE_API_GATEWAY', 'example'),
                    'router' => env('API_ROUTER', 'router'),
                    'request' => env('API_REQUEST', 'request'),
                    'providers' => [],
                    'middleware' => ['gateway'],
                    'auth' => null,
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
    'namespace' => 'App\\Routes'
];
