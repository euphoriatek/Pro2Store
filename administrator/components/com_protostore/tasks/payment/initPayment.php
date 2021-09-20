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


class protostoreTask_initPayment
{

	public function getResponse(Input $data)
	{

		$paymentType = $data->getString('paymentType');

		PluginHelper::importPlugin('system');

		return Factory::getApplication()->triggerEvent('onInitP2SPayment' . $paymentType, array('post' => $data));

	}

}
