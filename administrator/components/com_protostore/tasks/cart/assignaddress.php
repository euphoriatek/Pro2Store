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
use Protostore\Cart\CartFactory;


class protostoreTask_assignaddress
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */
	public function getResponse(Input $data): bool
	{

		$address_id	= $data->json->getInt('shipping_address_id');
		$type	= $data->json->getString('shipping_type');


		return CartFactory::setCartAddress($address_id, $type);
	}

}
