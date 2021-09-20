<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

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
