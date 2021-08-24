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

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Input\Input;


class protostoreTask_initPayment
{

	public function getResponse(Input $data)
	{

		$paymentType = $data->getString('paymentType');

		PluginHelper::importPlugin('system');

		return Factory::getApplication()->triggerEvent('onInitP2SPayment' . $paymentType, array('post' => $data));

	}

}
