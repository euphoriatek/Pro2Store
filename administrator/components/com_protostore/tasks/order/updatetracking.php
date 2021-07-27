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

use Protostore\Order\OrderFactory;
use Joomla\Input\Input;

class protostoreTask_updatetracking
{

	public function getResponse(Input $data)
	{


		return OrderFactory::updateTracking($data->getString('tracking_code'), $data->getString('tracking_link'), $data->getString('tracking_provider'), $data->getString('order_id'), $data->getBool('sendEmail'));

	}

}
