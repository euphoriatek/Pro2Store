<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Factory;
use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;

return [

	// Define transforms for the element node
	'transforms' => [

		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {

			$node->props['item_id'] = Utilities::getCurrentItemId();

			$product = ProductFactory::get($node->props['item_id']);

			if (!$product || $product->published == 0)
			{
				return false;
			}

			$doc = Factory::getDocument();

			$doc->addCustomTag('<script id="yps_options_data" type="application/json">' . json_encode($product->options) . '</script>');

		},

	]

];
