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
use Joomla\CMS\Language\Text;

use Protostore\Address\AddressFactory;
use Protostore\Cart\CartFactory;


class protostoreTask_saveguestshipping
{

	/**
	 * @param   Input  $data
	 *
	 * @return array|void
	 *
	 * @throws Exception
	 * @since 2.0
	 */
	public function getResponse(Input $data)
	{
		$response = array();

		// save the given address
		if ($address = AddressFactory::saveFromInputData($data))
		{
			// now that we have the address, apply it to the guest checkout


			if (!CartFactory::setCartAddress($address->id, 'shipping'))
			{

				$response['status']  = 'ko';
				$response['message'] = Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM');

				return $response;
			}

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
