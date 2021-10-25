<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Checkout;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;

use Joomla\Input\Input;
use Protostore\Address\AddressFactory;
use Protostore\Cart\CartFactory;
use Protostore\Config\ConfigFactory;
use Protostore\Coupon\CouponFactory;
use Protostore\Price\PriceFactory;
use Protostore\Product\ProductFactory;
use Protostore\Productoption\ProductoptionFactory;
use Protostore\Shipping\Shipping;
use Protostore\Tandcs\TandcsFactory;
use Protostore\Utilities\Utilities;

use stdClass;
use Exception;

class CheckoutFactory
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function validateStatus(Input $data): bool
	{


		// int
		$response = true;

		$params = ConfigFactory::get();


		if ($params->get('requiretandcs') == '1')
		{
			if (TandcsFactory::isChecked())
			{
				$response = true;
			}
			else
			{
				$response = false;
			}
		}

		if ($params->get('address_show') == '1')
		{

			if (!Utilities::isShippingAssigned())
			{
				$response = false;
			}
			if (!Utilities::isBillingAssigned())
			{
				$response = false;
			}


		}


		if (CartFactory::get()->count == 0)
		{
			$response = false;
		}


		return $response;

	}


}
