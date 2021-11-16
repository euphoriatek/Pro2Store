<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Currency;

use Joomla\CMS\Factory;


class CurrentCurrency
{
// Hold the class instance.
	private static $instance = null;
	private $currency;



	private function __construct()
	{

		$currency_id = Factory::getApplication()->input->cookie->get('yps-currency');

		if (!$currency_id)
		{
			$this->currency = CurrencyFactory::initCurrency();

		} else {
			$db = Factory::getDbo();

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_currency'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($currency_id));

			$db->setQuery($query);

			$result = $db->loadObject();

			if ($result)
			{

				$this->currency = Currency($result);
			}

		}




	}

	/**
	 *
	 * @return CurrentCurrency|null
	 *
	 * @since 2.0
	 */

	public static function getInstance(): ?CurrentCurrency
	{
		if (!self::$instance)
		{
			self::$instance = new CurrentCurrency();
		}

		return self::$instance;
	}

	/**
	 *
	 * @return Currency|null
	 *
	 * @since 2.0
	 */

	public function getCurrecny(): ?Currency
	{
		return $this->currency;
	}
}
