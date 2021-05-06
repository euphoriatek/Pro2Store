<?php

namespace YpsApp\Source\SkuType;

use Protostore\Resolver\Resolver;

class SkuType
{
    public static function config()
    {
        return [

            'fields' => [

                'sku' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'SKU - Pro2Store',
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

        return Resolver::getSKU($obj->id);

    }
}
