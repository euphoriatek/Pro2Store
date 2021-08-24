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

use Joomla\Input\Input;
use Joomla\CMS\Language\Text;

use Protostore\Address\AddressFactory;
use Protostore\Cart\CartFactory;


class protostoreTask_saveguestbilling
{

	/**
	 * @param   Input  $data
	 *
	 * @return array|void
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function getResponse(Input $data)
	{
		$response = array();

		// save the given address
		if ($address = AddressFactory::saveFromInputData($data))
		{
			// now that we have the address, apply it to the guest checkout

			if (!CartFactory::setCartAddress($address->id, 'billing'))
			{

				$response['status']  = 'ko';
				$response['message'] = Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM');

				return $response;
			}


			$response['status']  = 'ok';
			$response['message'] = Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED');

			return $response;
		}


		$response['status']  = 'ko';
		$response['message'] = Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_ALERT_ERROR');

		return $response;


	}

}
