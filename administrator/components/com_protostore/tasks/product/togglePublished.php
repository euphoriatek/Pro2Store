<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Protostore\Product\ProductFactory;

class protostoreTask_togglePublished
{

	public function getResponse($data)
	{

		// init
		$product_id = $data->get('product_id', false, 'INT');

		return ProductFactory::togglePublished($product_id);
	}

}
