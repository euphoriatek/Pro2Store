<?php
/**
 * @package     Pro2Store - Cash on Delievery
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

use Protostore\Cart\Cart;


class plgProtostorepaymentOfflinepay extends JPlugin
{


	/**
	 *
	 * Function called by the protostore_ajaxhelper plugin via checkout AJAX
	 *
	 * This function should ALWAYS call Cart::convertToOrder(NAME OF PAYMENT METHOD);
	 *
	 * NOTES @param '$paymentMethod' must allow PHP's strtolower function to always match the plugin name. so "Offline Pay" will become "offlinepay"
	 *
	 * @return mixed
	 *
	 */


	public function onInitPaymentOfflinepay()
	{

		//first create the order in the Pro2StoreDB
		$orderid = Cart::convertToOrder('Offline Pay', '', '', true);

		return array('orderid' => $orderid);

	}


}
