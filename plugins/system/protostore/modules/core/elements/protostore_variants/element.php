<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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

			$doc = Factory::getDocument();

			$doc->addCustomTag('<script id="base_url_data" type="application/json">' . \Joomla\CMS\Uri\Uri::base() . '</script>');
			$doc->addCustomTag('<script id="yps_joomla_item_id_data" type="application/json">' . $product->joomla_item_id . '</script>');
			$doc->addCustomTag('<script id="yps_variants_data" type="application/json">' . json_encode($product->variants) . '</script>');
			$doc->addCustomTag('<script id="yps_variantDefault_data" type="application/json">' . json_encode($product->variantDefault) . '</script>');


		},

	]

];
