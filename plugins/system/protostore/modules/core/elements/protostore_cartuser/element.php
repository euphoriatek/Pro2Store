<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

use Protostore\Cart\CartFactory;
use Protostore\Config\ConfigFactory;
use Protostore\Zone\Zone;


return [

	// Define transforms for the element node
	'transforms' => [


		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {



			if (Factory::getUser()->guest)
			{
				$node->props['guestaddressset'] = CartFactory::isGuestAddressSet();

				if (!$node->props['guestaddressset'])
				{

					//if the user is logged in, attempt to assign an address to the order.
//					Address::assignDefaultAddressToOrder($cart);
				}

				$node->props['shown'] = array();

				if ($node->props['hideregister'])
				{
					$node->props['shown'][] = $node->props['hideregister'];
				}
				if ($node->props['hidelogin'])
				{
					$node->props['shown'][] = $node->props['hidelogin'];
				}
				if ($node->props['hideguest'])
				{
					$node->props['shown'][] = $node->props['hideguest'];
				}


				if (count($node->props['shown']) === 3)
				{
					return false;
				}

			} else {
				$params = ConfigFactory::get();
				if ($params->get('address_show') == '0') {
					return false;
				}
			}

			$node->props['baseUrl']   = Uri::base();
			$node->props['countries'] = Zone::getAllCountries();
		}
	]
];
