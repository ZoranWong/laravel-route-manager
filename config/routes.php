<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 1:08 PM
 */
return [
    [
        'domain' => env('API_DOMAIN', 'api'),
        'router' => env('API_ROUTER', 'router'),
        'request' => env('API_REQUEST', 'request'),
        'gateways' =>[
            [
                'gateway' => env('EXAMPLE_API_GATEWAY', 'example'),
                'route' => [
                    'generator' => '',
                    'version' => '',
                    'namespace' => null,
                    'auth' => null,
                    'middleware' => null
                ]
            ]
        ]
    ],
    [
        'domain' => env('WEB_DOMAIN', 'web'),
        'router' => env('WEB_ROUTER', 'router'),
        'request' => env('WEB_REQUEST', 'request'),
        'gateways' => [
            [
                'gateway' => env('EXAMPLE_WEB_GATEWAY', 'example'),
                'route' => [
                    'generator' => '',
                    'version' => '',
                    'namespace' => null,
                    'auth' => null,
                    'middleware' => null
                ]
            ]
        ]
    ]
];
