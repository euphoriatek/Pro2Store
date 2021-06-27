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

use Brick\Math\BigDecimal;
use Brick\Money\Exception\UnknownCurrencyException;

use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Protostore\Currency\CurrencyFactory;


class ProductFactory
{


	/**
	 * @param $id
	 *
	 *
	 * @since 1.0
	 */

	public static function get(int $joomla_item_id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product'));
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			switch ($result->product_type)
			{
				case 0:
					return new PurchaseProduct($result);
				case 1:
					return new SubscriptionProduct($result);
			}

			return new Product($result);
		}

		return false;
	}

	/**
	 * @param   int          $limit
	 *
	 * @param   int          $offset
	 * @param   int          $category
	 * @param   string|null  $searchTerm
	 *
	 * @return array|false
	 * @since version
	 */

	public static function getList(int $limit = 0, int $offset = 0, int $category = 0, string $searchTerm = null)
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__content'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
		}

		if ($category)
		{
			$query->where($db->quoteName('catid') . ' = ' . $db->quote($category));
		}


		$db->setQuery($query, $offset, $limit);

		$results = $db->loadColumn();

		if ($results)
		{
			foreach ($results as $result)
			{
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_product'));
				$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($result));
				$db->setQuery($query);

				$result = $db->loadObject();

				if ($result)
				{
					$products[] = new Product($result);
				}


			}


			return $products;
		}


		return false;
	}

	/**
	 * @param $joomla_item_id
	 *
	 * @return false|JoomlaItem
	 *
	 * @since 1.6
	 */


	public static function getJoomlaItem($joomla_item_id): ?JoomlaItem
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			return new JoomlaItem($result);
		}

		return false;

	}

	/**
	 * @param   string  $type
	 * @param   int     $joomla_item_id
	 * @param   int     $catid
	 *
	 * @return string|null
	 *
	 * @since 1.6
	 */

	public static function getRoute(string $type, int $joomla_item_id, int $catid): string
	{

		switch ($type)
		{
			case 'item':
				return Route::_('index.php?option=com_content&view=article&id=' . $joomla_item_id . '&catid=' . $catid);
			case 'category':
				return Route::_('index.php?option=com_content&view=category&layout=blog&id=' . $catid);
		}


	}

	/**
	 * @param $price
	 *
	 * @return BigDecimal
	 *
	 * @throws UnknownCurrencyException
	 * @since version
	 */

	public static function getFloat($price): BigDecimal
	{

		return CurrencyFactory::toFloat($price);

	}

	/**
	 * @param $price
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */


	public static function getFormattedPrice($price): string
	{

		return CurrencyFactory::formatNumberWithCurrency($price);

	}

	/**
	 * @param $category_id
	 *
	 * @return string|null
	 *
	 * @since 1.6
	 */

	public static function getCategoryName($category_id): ?string
	{

		$categories   = Categories::getInstance('content');
		$categoryNode = $categories->get($category_id);   // returns the category node for category with id=12

		return $categoryNode->title;

	}

	/**
	 * @param $joomla_item_id
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	public static function getTags($joomla_item_id): array
	{

		$tagsHelper = new TagsHelper();

		$currentTags = $tagsHelper->getTagIds($joomla_item_id, "com_content.article");

		return $tagsHelper->getTagNames(explode(',', $currentTags));

	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 *
	 * @since 1.6
	 */


	public static function togglePublished($id)
	{
		$db = Factory::getDbo();

		$query = 'UPDATE `#__content` SET `state` = IF(`state`=1, 0, 1) WHERE id = ' . $id . ';';
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$item = $db->loadObject();

		return $item->state;


	}

	/**
	 * @param $image
	 *
	 * @return false|string
	 *
	 * @since version
	 */

	public static function getImagePath($image): ?string
	{

		if ($image)
		{
			return Uri::root() . $image;
		}

		return false;


	}

	/**
	 * @param $product_id
	 *
	 * @return false|Variant
	 *
	 * @since 1.6
	 */


	public static function getVariantData($product_id): ?Variant
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($product_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			return new Variant($result);
		}

		return null;


	}

}
