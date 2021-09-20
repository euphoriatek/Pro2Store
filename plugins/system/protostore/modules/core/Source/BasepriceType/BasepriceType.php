<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

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
