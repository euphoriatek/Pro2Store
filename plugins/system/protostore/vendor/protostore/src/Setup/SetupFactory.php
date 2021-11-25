<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Setup;
// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use Joomla\Input\Input;

use Protostore\Config\ConfigFactory;
use Protostore\Country\CountryFactory;
use Protostore\Language\LanguageFactory;
use Protostore\Utilities\Utilities;

use stdClass;
use Exception;

class SetupFactory
{


	/**
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function isSetup(): bool
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_setup'));
		$query->where($db->quoteName('id') . ' = 1');
		$query->where($db->quoteName('value') . ' = 1');

		$db->setQuery($query);

		if ($db->loadResult())
		{
			return true;
		}

		return false;
	}

	/**
	 * @param   Input  $data
	 *
	 *
	 * @return bool
	 * @throws Exception
	 * @since 2.0
	 */

	public static function init(Input $data)
	{

		// init
		$db = Factory::getDbo();

		$shopName           = $data->json->getString('shopName');
		$shopEmail          = $data->json->getString('shopEmail');
		$defaultCurrencyId  = $data->json->getInt('selectedCurrency');
		$defaultCountryId   = $data->json->getInt('selectedCountry');
		$createCheckout     = $data->json->getBool('createCheckout');
		$createConfirmation = $data->json->getBool('createConfirmation');
		$createTandcs       = $data->json->getBool('createTandcs');
		$createCancel       = $data->json->getBool('createCancel');


		// First do currency
		// First set all items to 0
		$query  = $db->getQuery(true);
		$fields = $db->quoteName('default') . ' = 0';
		$query->update($db->quoteName('#__protostore_currency'))->set($fields);
		$db->setQuery($query);
		$db->execute();


		// Now update the row we need to be the default
		$object            = new stdClass();
		$object->id        = $defaultCurrencyId;
		$object->default   = 1;
		$object->rate      = 1;
		$object->published = 1;

		$db->updateObject('#__protostore_currency', $object, 'id');


		// now do country
		// First set all items to 0
		$query  = $db->getQuery(true);
		$fields = array($db->quoteName('default') . ' = 0');
		$query->update($db->quoteName('#__protostore_country'))->set($fields);
		$db->setQuery($query);
		$db->execute();

		// Now update the row we need to be the default
		$object            = new stdClass();
		$object->id        = $defaultCountryId;
		$object->default   = 1;
		$object->published = 1;

		$db->updateObject('#__protostore_country', $object, 'id');

		$country = array();
		$country['id'] = $defaultCountryId;
		$country['published'] = 1;

		// now do the zones:
		CountryFactory::updateZoneList($country);

		// now do Shop Name etc

		$setParams = new stdClass();

		$setParams->shop_name    = $shopName;
		$setParams->supportemail = $shopEmail;
		$paramUpdate             = new stdClass();
		$paramUpdate->element    = 'com_protostore';
		$paramUpdate->params     = json_encode($setParams);

		$db->updateObject('#__extensions', $paramUpdate, 'element');

		// now do page creation

		if ($createCheckout)
		{
			self::createPage('checkout');
		}
		if ($createConfirmation)
		{
			self::createPage('confirmation');
		}
		if ($createTandcs)
		{
			self::createPage('tandcs');
		}
		if ($createCancel)
		{
			self::createPage('cancel');
		}


		$object        = new stdClass();
		$object->id    = 1;
		$object->value = 1;

		$result = $db->updateObject('#__protostore_setup', $object, 'id');

		if ($result)
		{
			return true;
		}

		return false;


	}

	/**
	 * @param   string  $pageType
	 *
	 *
	 * @since 2.0
	 */

	private static function createPage(string $pageType)
	{

		LanguageFactory::load();

		$db = Factory::getDbo();

		$title = Text::_('PROTOSTORE_' . strtoupper($pageType) . '_PAGE_TITLE');

		$object              = new stdClass();
		$object->id          = 0;
		$object->title       = $title;
		$object->alias       = Utilities::generateUniqueAlias(OutputFilter::stringURLSafe($title));
		$object->state       = 1;
		$object->access      = 1;
		$object->catid       = 2;
		$object->introtext   = "";
		$object->fulltext    = "";
		$object->created     = Utilities::getDate();
		$object->publish_up  = Utilities::getDate();
		$object->created_by  = Factory::getUser()->id;
		$object->modified    = Utilities::getDate();
		$object->modified_by = Factory::getUser()->id;
		$object->images      = '{"image_intro":"","image_intro_alt":"","float_intro":"","image_intro_caption":"","image_fulltext":"","image_fulltext_alt":"","float_fulltext":"","image_fulltext_caption":""}';
		$object->urls        = '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}';
		$object->attribs     = '{"article_layout":"","show_title":"","link_titles":"","show_tags":"","show_intro":"","info_block_position":"","info_block_show_title":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_hits":"","show_noauth":"","urls_position":"","alternative_readmore":"","article_page_title":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}';
		$object->version     = 1;
		$object->language    = '*';
		$object->metakey     = '';
		$object->metadesc    = '';
		$object->hits        = 0;
		$object->metadata    = '{"robots":"","author":"","rights":""}';
		$object->featured    = 0;
		$object->language    = '*';

		$result    = $db->insertObject('#__content', $object);
		$newItemid = $db->insertid();

		//J4 workflows

		if (Version::MAJOR_VERSION === 4)
		{
			$object            = new stdClass();
			$object->item_id   = $newItemid;
			$object->stage_id  = 1;
			$object->extension = 'com_content.article';

			$db->insertObject('#__workflow_associations', $object);
		}

		if ($result)
		{

			//add menu
			$menuid = self::createPro2StoreMenuIfNotExists();

			$object               = new stdClass();
			$object->id           = 0;
			$object->menutype     = 'protostore';
			$object->title        = Text::_('PROTOSTORE_' . strtoupper($pageType) . '_PAGE_TITLE');
			$object->alias        = Utilities::generateUniqueAlias(OutputFilter::stringURLSafe($title), 'menu');
			$object->path         = $object->alias;
			$object->link         = 'index.php?option=com_content&view=article&id=' . $newItemid;
			$object->type         = 'component';
			$object->published    = 1;
			$object->parent_id    = 1;
			$object->level        = 1;
			$object->img          = "";
			$object->params       = "";
			$object->client_id    = 0;
			$object->access       = 1;
			$object->component_id = self::getComponentId();
			$object->language     = '*';
			$object->home         = 0;

			$result      = $db->insertObject('#__menu', $object);
			$newMenuItem = $db->insertid();

			if ($result)
			{

				//set component config
				$params = ConfigFactory::get();

				$setParams = new stdClass();

				$setParams->shop_name                = $params->get('shop_name');
				$setParams->shop_logo                = $params->get('shop_logo');
				$setParams->shop_brandcolour         = $params->get('shop_brandcolour');
				$setParams->supportemail             = $params->get('supportemail');
				$setParams->terms_and_conditions_url = $params->get('terms_and_conditions_url');
				$setParams->checkout_page_url        = $params->get('checkout_page_url');
				$setParams->confirmation_page_url    = $params->get('confirmation_page_url');
				$setParams->cancellation_page_url    = $params->get('cancellation_page_url');


				switch ($pageType)
				{
					case 'checkout':
						$setParams->checkout_page_url = $newMenuItem;
						break;
					case 'confirmation':
						$setParams->confirmation_page_url = $newMenuItem;
						break;
					case 'tandcs':
						$setParams->terms_and_conditions_url = $newMenuItem;
						break;
					case 'cancel':
						$setParams->cancellation_page_url = $newMenuItem;
						break;
				}

				$paramUpdate          = new stdClass();
				$paramUpdate->element = 'com_protostore';
				$paramUpdate->params  = json_encode($setParams);

				$db->updateObject('#__extensions', $paramUpdate, 'element');

			}

		}
	}

	/**
	 *
	 * @return int
	 *
	 * @since 2.0
	 */

	private static function createPro2StoreMenuIfNotExists(): int
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__menu_types'));
		$query->where($db->quoteName('menutype') . ' = ' . $db->quote('protostore'));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return $result->id;
		}
		else
		{
			$object            = new stdClass();
			$object->id        = 0;
			$object->asset_id  = 0;
			$object->menutype  = 'protostore';
			$object->title     = 'Pro2Store Menu';
			$object->client_id = 0;

			$db->insertObject('#__menu_types', $object);

			return $db->insertid();
		}


	}

	private static function getComponentId()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('extension_id');
		$query->from($db->quoteName('#__extensions'));
		$query->where($db->quoteName('name') . ' = ' . $db->quote('com_content'));

		$db->setQuery($query);

		return $db->loadResult();

	}


}
