<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace YpsApp\Source\ShippingFlatFeeType;

use Protostore\Resolver\Resolver;

class ShippingFlatFeeType
{
    public static function config()
    {
        return [

            'fields' => [

                'flatfee' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Shipping Flat Fee - Pro2Store',
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

        return Resolver::getFlatFee($obj->id);

    }
}
