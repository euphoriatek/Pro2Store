<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace YpsApp\Source\WeightType;

use Protostore\Resolver\Resolver;

class WeightType
{
    public static function config()
    {
        return [

            'fields' => [

                'weight' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Weight - Pro2Store',
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

        return Resolver::getWeight($obj->id);

    }
}
