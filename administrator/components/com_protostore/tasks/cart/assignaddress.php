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
use Protostore\Cart\CartFactory;


class protostoreTask_assignaddress
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */
	public function getResponse(Input $data): bool
	{

		$address_id	= $data->json->getInt('shipping_address_id');
		$type	= $data->json->getString('shipping_type');


		return CartFactory::setCartAddress($address_id, $type);
	}

}
