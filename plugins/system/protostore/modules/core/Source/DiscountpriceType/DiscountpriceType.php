<?php

namespace YpsApp\Source\DiscountpriceType;

use Protostore\Resolver\Resolver;

class DiscountpriceType
{
    public static function config()
    {
        return [

            'fields' => [

                'discountprice' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Discounted Price - Pro2Store',
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

        return Resolver::getDiscountedPrice($obj->id);

    }
}
