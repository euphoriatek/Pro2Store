<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Currency;
// no direct access
use Brick\Money\Money;
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');


class Currency
{

	public $id;
	public $name;
	public $currencysymbol;
	public $iso;
	public $rate;
	public $default;
	public $published;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
		}

	}

	private function hydrate($data)
	{
		foreach ($data as $key => $value)
		{

			if (property_exists($this, $key))
			{
				$this->{$key} = $value;
			}

		}
	}

	private function init($data)
	{

	}


	public static function formatNumberWithCurrency($number, $currency)
	{

		// cast given number to integer
		$number = (int)$number;

		// get the Joomla Locale
		$lang = Factory::getLanguage();
		$locales = $lang->getLocale();
		$locale = $locales[0];

		// use Brick to format the number
		$money = Money::ofMinor($number, 'GBP');
		return $money->formatTo($locale);


	}
}
