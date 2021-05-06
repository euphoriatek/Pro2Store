<?php

namespace YpsApp\Source\StockType;

use Protostore\Resolver\Resolver;

class StockType
{
    public static function config()
    {
        return [

            'fields' => [

                'stock' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Stock - Pro2Store',
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

        return (string)Resolver::getStock($obj->id);

    }
}
