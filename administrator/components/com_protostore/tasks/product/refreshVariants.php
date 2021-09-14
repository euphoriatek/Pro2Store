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
use Joomla\Input\Input;

class protostoreTask_refreshVariants
{

	/**
	 * @param   Input  $data
	 *
	 * @return array
	 *
	 * @since 1.6
	 */
	public function getResponse(Input $data): array
	{


		return ProductFactory::getRefreshedVariantData($data);


	}

}