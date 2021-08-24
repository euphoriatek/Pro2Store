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

use Protostore\Cart\CartFactory;


class protostoreTask_startover
{

	/**
	 * @param $data
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */
	public function getResponse($data)
	{

		return CartFactory::destroyCartAddress();
	}

}
