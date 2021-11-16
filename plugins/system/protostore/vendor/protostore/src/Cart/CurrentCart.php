<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Cart;

use Exception;
use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;

/**
 *
 * Singleton to grab the current cart... it may be "anti-pattern" but it saves a TON of DB queries.
 *
 * @package     Protostore\Currency
 *
 * @since       2.0
 */
class CurrentCart
{
	// Hold the class instance.
	private static $instance = null;
	private $cart;


	/**
	 * @throws Exception
	 * @since 2.0
	 */

	private function __construct()
	{

		$db   = Factory::getDbo();
		$user = Factory::getUser();

		// now check if there is already a cart for this cookie
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_cart'));

		if ($user->guest)
		{
			$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote(Utilities::getCookieID()));
		}
		else
		{
			$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		}

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			$this->cart = new Cart($result);
		}

		else
		{
			$this->cart = CartFactory::init();
		}


	}

	/**
	 *
	 * @return CurrentCart|null
	 *
	 * @since 2.0
	 */

	public static function getInstance(): ?CurrentCart
	{
		if (!self::$instance)
		{
			self::$instance = new CurrentCart();
		}

		return self::$instance;
	}

	/**
	 *
	 * @return Cart|null
	 *
	 * @since 2.0
	 */

	public function getCart(): ?Cart
	{
		return $this->cart;
	}
}
