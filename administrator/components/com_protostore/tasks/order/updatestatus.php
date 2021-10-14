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


/**
 *
 * @since       2.0
 */

class protostoreTask_updatestatus
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public function getResponse(Input $data): bool
	{

		return OrderFactory::updateStatus($data->getString('status'), $data->getString('order_id'), $data->getBool('sendEmail'));

	}

}
