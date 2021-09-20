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

use Protostore\Address\AddressFactory;
use Joomla\Input\Input;

class protostoreTask_getCustomerAddressList
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function getResponse(Input $data)
	{



		return AddressFactory::getList(0, 0, '', 'name', 'DESC', $data->getInt('customer_id'));


	}

}
