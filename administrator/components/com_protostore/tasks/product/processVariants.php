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

use Joomla\Input\Input;
use Protostore\Product\ProductFactory;

class protostoreTask_processVariants
{

	public function getResponse(Input $data)
	{

		// init

		return ProductFactory::processVariants($data->json->getInt('product_id'), $data->json->getString('selected'));
	}

}
