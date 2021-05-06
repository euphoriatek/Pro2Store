<?php

/**
 * @package     Pro2Store - Product Options
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Protostore\Productoptions\Productoptions;
use Protostore\Product\Product;
use Protostore\Utilities\Utilities;

use stdClass;

return [

    // Define transforms for the element node
    'transforms' => [

        // The function is executed before the template is rendered
        'render' => function ($node, array $params) {


            $optionsClass = new Productoptions();

            $node->props['options'] = $optionsClass->getOptions();

            if ($node->props['options'] == '') {
                return false;
            }

            $selectedOptions = array();

            foreach ($node->props['options'] as $dbkey => $optionGroup) {
                foreach ($optionGroup['option'] as $optionkey => $option) {
                    if($optionkey == 'option0') {
                        $theOption = new stdClass();
                        $theOption->optionid = $option['optionid'];
                        $selectedOptions[] =  $theOption;
                    }
                }
            }

            $node->props['selectedOptions'] = $selectedOptions;



            $product = new Product(Utilities::getCurrentItemId());

            if ($product->published == 0) {
                return false;
            }

        },

    ]

];
