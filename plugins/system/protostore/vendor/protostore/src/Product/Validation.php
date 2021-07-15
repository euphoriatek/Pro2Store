<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Product;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

use Brick\Math\BigDecimal;
use Brick\Money\Exception\UnknownCurrencyException;

use Protostore\Currency\CurrencyFactory;
use Protostore\Productoption\ProductoptionFactory;


class Validation
{

	public static function validate($type, $input)
	{

		switch ($type)
		{
			case 'title':
				return self::validateTitle($input);
			case 'category':
				return self::validateCategory($input);
		}

	}


}
