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

class protostoreTask_sendemail
{

	public function getResponse(Input $data)
	{

		// init
		$response = array();

		PluginHelper::importPlugin('protostoresystem');
		Factory::getApplication()->triggerEvent('onSendProtoStoreEmail', array($data->getString('emailtype', 'thankyou'), $data->getInt('orderid')));

		$response['sent'] = 'ok';

		return $response;

	}

}
