<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace YpsApp\Source\Ypsproduct;

use Protostore\Currency\Currency;
use Protostore\Product\Product;

class Ypsproduct
{
    public static function config()
    {
        return [

            'fields' => [

                'product_title' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Product Title',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::title'
                        ]
                    ]

                ],
                'sku' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'SKU',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::sku'
                        ]
                    ]

                ],

                'purchase_price' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Pruchase Price',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::purchaseprice'
                        ]
                    ]

                ],
                'base_price' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Base Price',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::baseprice'
                        ]
                    ]

                ],
                'options' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Chosen Options',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::options'
                        ]
                    ]

                ],
                'full_image' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Full Image',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::fullimage'
                        ]
                    ]

                ],
                'intro_image' => [

                    'type' => 'String',

                    'metadata' => [
                        'label' => 'Intro Image',
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::introimage'
                        ]
                    ]

                ],

            ],

            'metadata' => [
                'type' => true,
                'label' => 'Pro2Store Product'
            ],

        ];
    }

    public static function resolve($obj, $args, $context, $info)
    {

        return json_encode($obj);
    }


    public static function title($cartitem, $args, $context, $info)
    {

        return $cartitem->joomla_item_title;

    }

    public static function sku($cartitem, $args, $context, $info)
    {

        return $cartitem->product->sku;

    }


    public static function purchaseprice($cartitem, $args, $context, $info)
    {

        $currencyHelper = new Currency();

        return Currency::formatNumberWithCurrency($cartitem->bought_at_price, $currencyHelper->currency->iso, '', true);

    }


    public static function baseprice($cartitem, $args, $context, $info)
    {

        $currencyHelper = new Currency();

        return Currency::formatNumberWithCurrency($cartitem->product->base_price, $currencyHelper->currency->iso);

    }

    public static function options($cartitem, $args, $context, $info)
    {

        $options = $cartitem->selected_options;

        $html = array();

        foreach ($options as $option) {
            $html[] = $option->optiontypename . ': ' . $option->optionname;
        }

        return implode('<br/>', $html);
    }

    public static function fullimage($cartitem, $args, $context, $info)
    {

        return $cartitem->images->image_fulltext;
    }

    public static function introimage($cartitem, $args, $context, $info)
    {

        return $cartitem->images->image_intro;
    }
}
