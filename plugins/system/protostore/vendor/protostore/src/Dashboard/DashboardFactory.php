<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Dashboard;

// no direct access
defined('_JEXEC') or die('Restricted access');


use DateTimeZone;
use Joomla\CMS\Factory;


use Joomla\CMS\Language\Text;
use Protostore\Language\LanguageFactory;
use stdClass;

class DashboardFactory
{

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */


	public static function getDataForCategoryDonut(): array
	{


		$response = array();

		$db = Factory::getDbo();


		$query = $db->getQuery(true);

		$query->select($db->quoteName('j_item_cat'));
		$query->select("COUNT(`j_item_cat`) as `value_occurrence`");
		$query->from($db->quoteName('#__protostore_order_products'));
		$query->group($db->quoteName('j_item_cat'));
		$query->order('value_occurrence DESC');

		$db->setQuery($query);

		$categories = $db->loadObjectList();

		$bestsellingCategories = array();

		foreach ($categories as $category)
		{

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__categories'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($category->j_item_cat));

			$db->setQuery($query);

			$bestseller = $db->loadObject();

			if ($bestseller)
			{
				$bestseller->salestotal = $category->value_occurrence;

				$bestsellingCategories[] = $bestseller;
			}


		}


		$categories = array();

		foreach ($bestsellingCategories as $cat)
		{
			$categories[] = $cat->title;
		}

		$categorySales = array();

		foreach ($bestsellingCategories as $cat)
		{
			$categorySales[] = $cat->salestotal;
		}

		$count       = count($bestsellingCategories);
		$rand_colors = array();
		foreach ($bestsellingCategories as $cat)
		{
			$rand_colors[] = '#' . substr(md5($cat->salestotal), 0, 6);
		}


		$response['categories']    = $categories;
		$response['categorySales'] = $categorySales;
		$response['colours']       = $rand_colors;

		return $response;

	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getMonths(string $format = 'F'): array
	{

		$months = array();

		$config   = Factory::getConfig();
		$timezone = $config->get('offset');

		$timezone = new DateTimeZone($timezone);


		$months[] = Factory::getDate('now -5 month')->setTimezone($timezone)->format($format);
		$months[] = Factory::getDate('now -4 month')->setTimezone($timezone)->format($format);
		$months[] = Factory::getDate('now -3 month')->setTimezone($timezone)->format($format);
		$months[] = Factory::getDate('now -2 month')->setTimezone($timezone)->format($format);
		$months[] = Factory::getDate('now -1 month')->setTimezone($timezone)->format($format);
		$months[] = Factory::getDate()->format($format);

		return $months;

	}

	/**
	 *
	 * @return array
	 *
	 * @since v1.6
	 */

	public static function getMonthsSales(): array
	{
		// init
		$db = Factory::getDbo();

		$months = self::getMonths('Y-m');
		$totals = array();

		foreach ($months as $date)
		{


			$query = $db->getQuery(true);

			$query->select('order_total');
			$query->from($db->quoteName('#__protostore_order'));
			$query->where($db->quoteName('order_date') . ' LIKE \'%' . $date . '%\'');

			$db->setQuery($query);


			$ordersThisMonth = $db->loadColumn();
			$thisMonthsTotal = 0;
			foreach ($ordersThisMonth as $order_total)
			{

				$thisMonthsTotal += $order_total;
			}
			$totals[] = ($thisMonthsTotal / 100);


		}

		return $totals;

	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getBestsellers(): array
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName('j_item'));
		$query->select("COUNT(`j_item`) as `value_occurrence`");
		$query->from($db->quoteName('#__protostore_order_products'));
		$query->group($db->quoteName('j_item'));
		$query->order('value_occurrence DESC');
		$query->setLimit('5');

		$db->setQuery($query);

		$items = $db->loadObjectList();

		$bestsellers = array();

		foreach ($items as $item)
		{

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__content'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($item->j_item));

			$db->setQuery($query);

			$bestseller             = $db->loadObject();

			if($bestseller) {
				$bestseller             = (object) $bestseller;
				$bestseller->salestotal = $item->value_occurrence;

				$bestsellers[] = $bestseller;
			}


		}

		return $bestsellers;


	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getOrderStats(): array
	{

        // init
		LanguageFactory::load();
		$db = Factory::getDbo();

		$response = array();

		// Get Pending
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('P'));

		$db->setQuery($query);

		$pending           = array();
		$pending['count']  = $db->loadResult();
		$pending['title']  = Text::_('COM_PROTOSTORE_ORDER_PENDING');
		$pending['status'] = 'p';
		$response[]        = $pending;


		// Get Confirmed
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('C'));

		$db->setQuery($query);

		$confirmed           = array();
		$confirmed['count']  = $db->loadResult();
		$confirmed['title']  = Text::_('COM_PROTOSTORE_ORDER_CONFIRMED');
		$confirmed['status'] = 'c';
		$response[]          = $confirmed;

		// Get Cancelled
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('X'));

		$db->setQuery($query);

		$cancelled           = array();
		$cancelled['count']  = $db->loadResult();
		$cancelled['title']  = Text::_('COM_PROTOSTORE_ORDER_CANCELLED');
		$cancelled['status'] = 'x';
		$response[]          = $cancelled;


		// Get refunded
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('R'));

		$db->setQuery($query);

		$refunded           = array();
		$refunded['count']  = $db->loadResult();
		$refunded['title']  = Text::_('COM_PROTOSTORE_ORDER_REFUNDED');
		$refunded['status'] = 'r';
		$response[]         = $refunded;


		// Get shipped
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('S'));

		$db->setQuery($query);

		$shipped           = array();
		$shipped['count']  = $db->loadResult();
		$shipped['title']  = Text::_('COM_PROTOSTORE_ORDER_SHIPPED');
		$shipped['status'] = 's';
		$response[]        = $shipped;


		// Get completed
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('F'));

		$db->setQuery($query);

		$completed           = array();
		$completed['count']  = $db->loadResult();
		$completed['title']  = Text::_('COM_PROTOSTORE_ORDER_COMPLETED');
		$completed['status'] = 'f';
		$response[]          = $completed;


		// Get denied
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('order_status') . ' = ' . $db->quote('D'));

		$db->setQuery($query);

		$denied           = array();
		$denied['count']  = $db->loadResult();
		$denied['title']  = Text::_('COM_PROTOSTORE_ORDER_DENIED');
		$denied['status'] = 'd';
		$response[]       = $denied;

		// Get all
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_order'));

		$db->setQuery($query);

		$all           = array();
		$all['count']  = $db->loadResult();
		$all['title']  = Text::_('COM_PROTOSTORE_ORDER_TOTAL');
		$all['status'] = 't';
		$response[]    = $all;

		return $response;

	}

}
