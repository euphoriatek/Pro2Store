<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Email;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Input\Input;
use Protostore\Language\LanguageFactory;
use Protostore\Utilities\Utilities;

use stdClass;


class EmailFactory
{


	/**
	 *
	 * Gets the discount based on the given ID.
	 *
	 * @param $id
	 *
	 * @return Email
	 *
	 * @since 1.6
	 */

	public static function get($id): ?Email
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_email'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Email($result);
		}

		return null;

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   string|null  $type
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 *
	 * @return array
	 * @since 1.6
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $type = null, string $orderBy = 'id', string $orderDir = 'DESC'): ?array
	{

		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_email'));


		// if there is a search term, iterate over the columns looking for a match
		if ($searchTerm)
		{
			$query->where($db->quoteName('subject') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
		}

		if ($type)
		{
			$query->where($db->quoteName('type') . ' = ' . $db->quote($type));

		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		// only proceed if there's any rows
		if ($results)
		{
			// iterate over the array of objects, initiating the Class.
			foreach ($results as $result)
			{
				$items[] = new Email($result);

			}

			return $items;
		}

		return null;

	}


	/**
	 * @param $emailType
	 *
	 * @return string
	 *
	 * @since 1.6
	 */

	public static function emailTypeToString($emailType): string
	{

		LanguageFactory::load();

		switch ($emailType)
		{
			case 'thankyou':
				return Text::_('COM_PROTOSTORE_EMAILTYPE_THANK_YOU');
			case 'confirmed':
				return Text::_('COM_PROTOSTORE_EMAILTYPE_CONFIRMED');
			case 'created':
				return Text::_('COM_PROTOSTORE_EMAILTYPE_CREATED');
			case 'pending':
				return Text::_('COM_PROTOSTORE_EMAILTYPE_PENDING');
			case 'shipped':
				return Text::_('COM_PROTOSTORE_EMAILTYPE_SHIPPED');
			default:
				return Text::_('');
		}

	}


	/**
	 * @param   Input  $data
	 *
	 *
	 * @since 1.6
	 */

	public static function togglePublishedFromInputData(Input $data)
	{

		$db = Factory::getDbo();

		$items = json_decode($data->getString('items'));


		foreach ($items as $item)
		{

			$query = 'UPDATE ' . $db->quoteName('#__protostore_email') . ' SET ' . $db->quoteName('published') . ' = IF(' . $db->quoteName('published') . '=1, 0, 1) WHERE ' . $db->quoteName('id') . ' = ' . $db->quote($item->id) . ';';
			$db->setQuery($query);
			$db->execute();

		}

	}


	/**
	 * @param   Input  $data
	 *
	 *
	 * @return Email
	 * @since 1.6
	 */


	public static function saveFromInputData(Input $data)
	{


		if ($id = $data->json->getInt('itemid', null))
		{


			$current = self::get($id);

			$current->to          = $data->json->getString('to', $current->to);
			$current->subject     = $data->json->getString('subject', $current->subject);
			$current->body        = $data->json->get('body', $current->body, 'RAW');
			$current->emailtype   = $data->json->getString('emailtype', $current->emailtype);
			$current->published   = $data->json->getInt('published', $current->published);
			$current->modified    = Utilities::getDate();
			$current->modified_by = Factory::getUser()->id;

			if (self::commitToDatabase($current))
			{
				return $current;
			}

		}
		else
		{

			if ($item = self::createFromInputData($data))
			{
				return $item;
			}

		}

		return null;

	}

	/**
	 * @param   Email  $item
	 *
	 *
	 * @return bool
	 * @since 1.6
	 */


	private static function commitToDatabase(Email $item): bool
	{

		$db = Factory::getDbo();

		$insert = new stdClass();

		$insert->id          = $item->id;
		$insert->to          = $item->to;
		$insert->subject     = $item->subject;
		$insert->emailtype   = $item->emailtype;
		$insert->body        = $item->body;
		$insert->published   = $item->published;
		$insert->modified    = $item->modified;
		$insert->modified_by = $item->modified_by;

		$result = $db->updateObject('#__protostore_email', $insert, 'id');

		if ($result)
		{
			return true;
		}

		return false;

	}


	/**
	 * @param   Input  $data
	 *
	 * @return Email|bool
	 *
	 * @since 1.6
	 */


	private static function createFromInputData(Input $data): Email
	{

		$db = Factory::getDbo();

		$item              = new stdClass();
		$item->to          = $data->json->getString('to');
		$item->subject     = $data->json->getString('subject');
		$item->emailtype   = $data->json->getString('emailtype');
		$item->body        = $data->json->get('body', '', 'RAW');
		$item->published   = $data->json->getInt('published');
		$item->created     = Utilities::getDate();
		$item->created_by  = Factory::getUser()->id;
		$item->modified    = Utilities::getDate();
		$item->modified_by = Factory::getUser()->id;


		$result = $db->insertObject('#__protostore_email', $item);

		if ($result)
		{
			return self::get($db->insertid());
		}

		return false;


	}
}
