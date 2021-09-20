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

use Protostore\Order\OrderFactory;
use Joomla\Input\Input;

class protostoreTask_updatetracking
{

	public function getResponse(Input $data)
	{


		return OrderFactory::updateTracking($data->getString('tracking_code'), $data->getString('tracking_link'), $data->getString('tracking_provider'), $data->getString('order_id'), $data->getBool('sendEmail'));

	}

}
