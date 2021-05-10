<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Productoption;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;


class ProductoptionFactory
{


	public static function get($id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option_values'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Productoption($result);
		}

		return false;
	}


	/**
	 * @param $optiontypeid
	 *
	 * @return mixed|null
	 *
	 * @since version
	 */


	public static function getOptionTypeName($optiontype)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('name');
		$query->from($db->quoteName('#__protostore_product_option'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($optiontype));

		$db->setQuery($query);

		return $db->loadResult();
	}


}
