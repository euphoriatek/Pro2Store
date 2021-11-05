<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Tag;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Exception;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

use stdClass;

class TagFactory
{


	public static function getTags(int $joomla_item_id): ?array
	{


		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('b.title');
		$query->from($db->quoteName('#__contentitem_tag_map', 'a'));
		$query->where($db->quoteName('content_item_id') . ' = ' . $db->quote($joomla_item_id));
		$query->join('INNER', $db->quoteName('#__tags', 'b') . ' ON ' . $db->quoteName('a.tag_id') . ' = ' . $db->quoteName('b.id'));

		$db->setQuery($query);

		$results = $db->loadObjectList();


		return ArrayHelper::getColumn($results, 'title');

	}


	/**
	 *
	 * Gets the available tags.
	 * If the ID of the item is supplied, then the function removes the currently selected tags and returns the remaining, unselected tags.
	 *
	 * @param   null  $id
	 *
	 * @return array|null
	 *
	 * @since 2.0
	 */

	public static function getAvailableTags($id = null): ?array
	{

		$tags = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('title');
		$query->from($db->quoteName('#__tags'));
		$query->where($db->quoteName('published') . ' = 1');
		$query->where($db->quoteName('title') . ' != ' . $db->quote('ROOT'));

		$db->setQuery($query);

		$results = $db->loadObjectList();
		if ($results)
		{
			foreach ($results as $result)
			{

				$tags[] = new Tag($result);


			}

			$allTags = ArrayHelper::getColumn($tags, 'title');

			if ($id)
			{
				$diffs = array_diff($allTags, self::getTags($id));

				$newArray = array();

				foreach ($diffs as $key => $diff)
				{

					$newArray[] = $allTags[$key];
				}

				return $newArray;

			}
			else
			{
				return $allTags;
			}


		}


		return null;


	}


}
