<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
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

			// check if we are managing stock on this product
			// set 'instock' to true even if we are not managing stock
			$node->props['instock'] = true;
			if ($product->manage_stock == 1)
			{
				// if we have stock... fine...
				if ($product->stock > 0)
				{

				}
				else
				{
					$node->props['instock'] = false;
				}
			}

//TODO - GET CHECKOUT LINK
			$node->props['checkoutlink'] = Route::_('index.php?Itemid=');
			$node->props['baseUrl']      = Uri::base();

		},

	]

];

?>
