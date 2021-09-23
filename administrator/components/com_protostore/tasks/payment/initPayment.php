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

use Protostore\Order\Order;


class protostoreTask_initPayment
{

	public function getResponse(Input $data)
	{

		$paymentType = $data->json->getString('paymentType');

		PluginHelper::importPlugin('system');

		// "triggerEvent" returns an array of the triggered events. We know that we're only triggering one,
		// so pull out the first node of the array and return it. this should satisfy PHP 7's type casting for the "Order" type.
		$events = Factory::getApplication()->triggerEvent('onInitP2SPayment' . $paymentType);

		// $events[0] should be of type "Order"
		return $events[0];

	}

}
