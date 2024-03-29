<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Country;

use Joomla\CMS\Factory;


class DefaultCountry
{
// Hold the class instance.
	private static $instance = null;
	private $country;


	private function __construct()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_country'));
		$query->where($db->quoteName('default') . ' = 1');

		$db->setQuery($query);

		$id = $db->loadResult();

		$this->country = CountryFactory::get($id);


	}

	/**
	 *
	 * @return DefaultCountry|null
	 *
	 * @since 2.0
	 */

	public static function getInstance(): ?DefaultCountry
	{
		if (!self::$instance)
		{
			self::$instance = new DefaultCountry();
		}

		return self::$instance;
	}

	/**
	 *
	 * @return Country|null
	 *
	 * @since 2.0
	 */

	public function getCountry(): ?Country
	{
		return $this->country;
	}
}
