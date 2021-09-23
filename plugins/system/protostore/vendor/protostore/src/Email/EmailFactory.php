<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Email;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Input\Input;

use Protostore\Config\ConfigFactory;
use Protostore\Customer\Customer;
use Protostore\Customer\CustomerFactory;
use Protostore\Emaillog\EmaillogFactory;
use Protostore\Language\LanguageFactory;
use Protostore\Order\Order;
use Protostore\Order\OrderFactory;
use Protostore\Utilities\Utilities;

use stdClass;


class EmailFactory
{


	/**
	 *
	 * Gets the discount based on the given ID.
	 *
	 * @param   int  $id
	 *
	 * @return Email
	 *
	 * @since 1.6
	 */

	public static function get(int $id): ?Email
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
	 * @param   string       $language
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 *
	 * @return array
	 * @since 1.6
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $type = null, string $language = '*', string $orderBy = 'id', string $orderDir = 'DESC'): ?array
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
			$query->where($db->quoteName('emailtype') . ' = ' . $db->quote($type));

		}

		if ($language)
		{
			$query->where('(' . $db->quoteName('language') . ' = ' . $db->quote($language) . 'OR ' . $db->quoteName('language') . ' = ' . $db->quote('*') . ')');

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
	 * @param   string  $language
	 *
	 * @return string
	 *
	 * @since 1.6
	 */

	public static function getLanguageImageString(string $language): string
	{

		return strtolower(str_replace('-', "_", $language));

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
			$current->language    = $data->json->getString('language', $current->language);
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
		$insert->language    = $item->language;
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
		$item->language    = $data->json->getString('language');
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



	/**
	 * @param   Input  $data
	 *
	 *
	 * @return bool
	 * @since 1.6
	 */

	public static function trashFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');


		foreach ($items as $item)
		{
			$query      = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($item['id'])
			);
			$query->delete($db->quoteName('#__protostore_email'));
			$query->where($conditions);
			$db->setQuery($query);
			$db->execute();

		}

		return true;

	}

	/**
	 * @param   string  $type
	 * @param   int     $order_id
	 *
	 * @param   string  $layout
	 * @param   string  $plugin
	 *
	 * @return void
	 *
	 * @throws \Exception
	 * @since 1.6
	 */


	public static function send(string $type, int $order_id, string $layout, string $plugin)
	{

		// init the stuff we need.

		// init the mailer
		$mailer = Factory::getMailer();
		$config = Factory::getConfig();

		// get the current language
		$language = Factory::getLanguage();
		$language->load('com_protostore', JPATH_ADMINISTRATOR);

		$languageTag = $language->get('tag');

		// grab the order and the customer
		$order    = OrderFactory::get($order_id);
		$customer = CustomerFactory::get($order->customer_id);


		// get the emails that needs to be sent
		$emails = self::getList(0, 0, null, $type, $languageTag);

		if ($emails)
		{


			foreach ($emails as $email)
			{

				$sender = array(
					$config->get('mailfrom'),
					$config->get('fromname')
				);

				$mailer->setSender($sender);

				if ($email->to)
				{
					$emailto = explode(',', $email->to);
					$mailer->addRecipient($emailto);
				}
				else
				{
					$emailto = $order->billing_address->email;
					$emailto = array($emailto);

					$mailer->addRecipient($emailto);
				}


				$text = self::processReplacements($email->body, $order, $customer);

				$params = ConfigFactory::get();

				$displayData = array('order' => $order, 'body' => $text, 'config' => $params);

				$body = LayoutHelper::render($layout, $displayData, JPATH_PLUGINS . '/protostoresystem/' . $plugin . '/tmpl');

				$mailer->setSubject(self::processReplacements($email->subject, $order, $customer));
				$mailer->isHtml(true);
				$mailer->setBody($body);

				$send = $mailer->Send();

				if ($send)
				{
					// Log everything
					EmaillogFactory::log(implode(',', $emailto), $type, $order->customer_id, $order->id);

					OrderFactory::log(
						$order->id,
						Text::sprintf('COM_PROTOSTORE_ORDER_EMAIL_SENT_LOG', ucfirst($type), implode(',', $emailto), (Factory::getUser()->name ? Factory::getUser()->name : 'Joomla'))
					);

				}
			}


		}


	}

	/**
	 * @param   string    $text
	 * @param   Order     $order
	 * @param   Customer  $customer
	 *
	 * @return string
	 *
	 * @since 1.6
	 */


	private static function processReplacements(string $text, Order $order, Customer $customer): string
	{

		$config = Factory::getConfig();

		// global
		$text = str_replace('{site_name}', $config->get('fromname'), $text);

		// order
		$text = str_replace('{order_number}', $order->order_number, $text);
		$text = str_replace('{order_grand_total}', $order->order_total_formatted, $text);
		$text = str_replace('{order_subtotal}', $order->order_subtotal_formatted, $text);
		if ($order->shipping_total)
		{
			$text = str_replace('{order_shipping_total}', $order->shipping_total_formatted, $text);
		}
		$text = str_replace('{order_payment_method}', $order->payment_method, $text);


		// tracking
		$text = str_replace('{tracking_code}', $order->tracking_code, $text);
		$text = str_replace('{tracking_url}', $order->tracking_link, $text);


		// customer
		if ($customer->name)
		{
			$text = str_replace('{customer_name}', $customer->name, $text);
		}
		else
		{
			$text = str_replace('{customer_name}', $order->shipping_address->name, $text);
		}
		if ($customer->email)
		{
			$text = str_replace('{customer_email}', $customer->email, $text);
		}
		else
		{
			$text = str_replace('{customer_email}', $order->shipping_address->email, $text);
		}

		if ($customer->total_orders)
		{
			$text = str_replace('{customer_order_count}', $customer->total_orders, $text);
		}

		if ($order->shipping_address)
		{
			// shipping
			$text = str_replace('{shipping_name}', $order->shipping_address->name, $text);
			$text = str_replace('{shipping_address1}', $order->shipping_address->address1, $text);
			$text = str_replace('{shipping_address2}', $order->shipping_address->address2, $text);
			$text = str_replace('{shipping_address3}', $order->shipping_address->address3, $text);
			$text = str_replace('{shipping_town}', $order->shipping_address->town, $text);
			$text = str_replace('{shipping_state}', $order->shipping_address->zone_name, $text);
			$text = str_replace('{shipping_country}', $order->shipping_address->country_name, $text);
			$text = str_replace('{shipping_postcode}', $order->shipping_address->postcode, $text);
			$text = str_replace('{shipping_email}', $order->shipping_address->email, $text);
			$text = str_replace('{shipping_postcode}', $order->shipping_address->postcode, $text);
			$text = str_replace('{shipping_mobile}', $order->shipping_address->mobile_phone, $text);
			$text = str_replace('{shipping_phone}', $order->shipping_address->phone, $text);
		}


		if ($order->billing_address)
		{
			// billing
			$text = str_replace('{billing_name}', $order->billing_address->name, $text);
			$text = str_replace('{billing_address1}', $order->billing_address->address1, $text);
			$text = str_replace('{billing_address2}', $order->billing_address->address2, $text);
			$text = str_replace('{billing_address3}', $order->billing_address->address3, $text);
			$text = str_replace('{billing_town}', $order->billing_address->town, $text);
			$text = str_replace('{billing_state}', $order->billing_address->zone_name, $text);
			$text = str_replace('{billing_country}', $order->billing_address->country_name, $text);
			$text = str_replace('{billing_postcode}', $order->billing_address->postcode, $text);
			$text = str_replace('{billing_email}', $order->billing_address->email, $text);
			$text = str_replace('{billing_postcode}', $order->billing_address->postcode, $text);
			$text = str_replace('{billing_mobile}', $order->billing_address->mobile_phone, $text);
			$text = str_replace('{billing_phone}', $order->billing_address->phone, $text);
		}


		return $text;

	}


}
