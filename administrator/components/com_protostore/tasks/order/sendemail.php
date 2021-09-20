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
