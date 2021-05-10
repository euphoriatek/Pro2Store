<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Currency;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;


class CurrencyFactory
{

	public static function get($id)
	{

	}

	public static function getCurrent()
	{

	}

	public static function getDefault()
	{

	}


	public static function setCurrency($id)
	{

	}

	public static function initCurrency($id)
	{

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   int          $published
	 * @param   string|null  $searchTerm
	 * @param                $orderBy
	 * @param                $orderDir
	 *
	 *
	 * @since 1.5
	 */

	public static function getList(int $limit = 25, int $offset = 0, int $published = 1, string $searchTerm = null, string $orderBy = 'name', string $orderDir = 'ASC')
	{

	}


	public static function format($number, $currency) {

	}

	public static function translate($number, $selectedCurrency) {

	}

	public static function translateByISO($number, $iso) {

	}

	public static function translateToInt($number, $iso) {

	}

	public static function getConversionRate($currency) {

	}



}
