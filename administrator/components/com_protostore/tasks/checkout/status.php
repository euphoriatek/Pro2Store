<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access


use Protostore\Checkout\CheckoutFactory;

defined('_JEXEC') or die('Restricted access');



class protostoreTask_status
{

	public function getResponse($data)
	{


		return CheckoutFactory::validateStatus($data);



	}

}
