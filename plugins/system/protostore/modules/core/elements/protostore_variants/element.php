<?php

/**
 * @package     Pro2Store - Product Variants
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Joomla\CMS\Factory;

use Joomla\CMS\Document\HtmlDocument;

use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;

use stdClass;

return [

	// Define transforms for the element node
	'transforms' => [

		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {

			\Protostore\Language\LanguageFactory::load();

			$product = ProductFactory::get(Utilities::getCurrentItemId());

			if ($product->published == 0)
			{
				return false;
			}
			if (!$product->variants)
			{
				return false;
			}

			$node->props['joomla_item_id'] = $product->joomla_item_id;
			$node->props['variants']       = $product->variants;
			$node->props['variantDefault'] = $product->variantDefault;
//			$node->props['unavailableMessage'] = \Joomla\CMS\Language\Text::_('COM_PROTOSTORE_VARIANT_UNAVAILABLE');


			$doc = Factory::getDocument();

			$doc->addCustomTag('<script id="yps_joomla_item_id_data" type="application/json">' . $product->joomla_item_id . '</script>');
			$doc->addCustomTag('<script id="yps_variants_data" type="application/json">' . json_encode($product->variants) . '</script>');
			$doc->addCustomTag('<script id="yps_variantLabels_data" type="application/json">' . json_encode($product->variantDefault) . '</script>');


		},

	]

];
