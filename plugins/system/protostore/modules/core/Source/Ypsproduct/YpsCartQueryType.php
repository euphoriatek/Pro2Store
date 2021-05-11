<?php

namespace YpsApp\Source\Ypsproduct;

use Protostore\Cart\Cart;
use Protostore\Cart\CartFactory;

class YpsCartQueryType
{
    public static function config()
    {
        return [

            'fields' => [

                'ypsproductlist' => [
                    'type' => [
                        'listOf' => 'Ypsproduct',
                    ],
                    'args' => [

                    ],
                    'metadata' => [
                        'label' => 'Products In Cart',
                        'group' => 'Pro2Store',

                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],

        ];
    }

    public static function resolve($root, array $args)
    {

        $cart = CartFactory::get();
        $cartItems = $cart->cartItems;


        return $cartItems;


    }
}
