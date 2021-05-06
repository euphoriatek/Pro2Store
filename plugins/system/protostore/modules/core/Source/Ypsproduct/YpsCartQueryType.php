<?php

namespace YpsApp\Source\Ypsproduct;

use Protostore\Cart\Cart;

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

        $currentCartId = Cart::getCurrentCartId();
        $cart = new Cart($currentCartId);
        $cartItems = $cart->cartItems;


        return $cartItems;


    }
}
