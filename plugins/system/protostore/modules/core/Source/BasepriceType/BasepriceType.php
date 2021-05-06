<?php

namespace YpsApp\Source\BasepriceType;

use Protostore\Resolver\Resolver;

class BasepriceType
{
    public static function config()
    {
        return [

            'fields' => [

                'baseprice' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Base Price - Pro2Store',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve'
                        ]
                    ]

                ]

            ],

        ];
    }

    public static function resolve($obj, $args, $context, $info)
    {

        return Resolver::getBasePrice($obj->id);

    }
}
