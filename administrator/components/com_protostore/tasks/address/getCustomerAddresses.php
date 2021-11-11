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

use Joomla\Input\Input;

use Protostore\Address\AddressFactory;


class protostoreTask_getCustomerAddresses
{

	/**
	 * @param   Input  $data
	 *
	 * @return array
	 *
	 * @throws Exception
	 * @since 2.0
	 */
	public function getResponse(Input $data): ?array
	{

		// grab current customer info here on the server - not on the client!
		$customer = \Protostore\Customer\CustomerFactory::get();

		return AddressFactory::getList(0, 0, null, 'name', 'DESC', $customer->id);


	}

}
