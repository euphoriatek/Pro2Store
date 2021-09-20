<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
