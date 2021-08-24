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

class protostoreTask_addtocart
{

	public function getResponse($data)
	{

		// init
		$amount = $data->json->getInt('amount', false);
		$itemid = $data->json->getInt('contentitemid', null, 'INT');

		$itemOptions = $data->json->getString('itemoptions');
		$itemOptions = json_decode($itemOptions, true);


		return CartFactory::addToCart($itemid, $amount, $itemOptions);
	}

}
