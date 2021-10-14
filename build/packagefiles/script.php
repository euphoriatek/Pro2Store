<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;


/**
 * Script File of Protostore Component
 * @since 1.0
 */
class pkg_protostoreInstallerScript
{
	/**
	 * Constructor
	 *
	 * @param     $parent  The object responsible for running this script
	 *
	 * @since 1.0
	 */
	private $db;
	private $release;
	private $oldversion;

	public function __construct($parent)
	{

		$ext = get_loaded_extensions();

		if (in_array('intl', $ext))
		{

			$this->release    = $parent->getManifest()->version;
			$this->oldversion = $this->getParam('version');

			$this->db = Factory::getDbo();

		}
		else
		{
			Factory::getApplication()->enqueueMessage('', 'error');

			die('The INTL extension is not enabled on your PHP version, Pro2Store requires INTL. Please enable the extension in your PHP and try again. ');
		}


	}

	/**
	 * Called on installation
	 *
	 * @param     $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 * @since 1.0
	 */
	public function install($parent)
	{


	}

	/**
	 * Called on uninstallation
	 *
	 * @param     $parent  The object responsible for running this script
	 *
	 * @since 1.0
	 */
	public function uninstall($parent)
	{


	}

	/**
	 * Called on update
	 *
	 * @param     $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 * @since 1.0
	 */
	public function update($parent)
	{

	}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $type    Which action is happening (install|uninstall|discover_install|update)
	 * @param           $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 * @since 1.0
	 */
	public function preflight($type, $parent)
	{


	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $type    Which action is happening (install|uninstall|discover_install|update)
	 * @param           $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 * @since 1.0
	 */
	public function postflight($type, $parent)
	{


		if ($type == 'install')
		{

//			$this->initSetup();
//			$this->importCountries();
//			$this->importZones();

			$db = Factory::getDbo();

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_shortcodes';
			$plugin->folder  = (string) 'content';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


			// Prepare plugin object
			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_ajaxhelper';
			$plugin->folder  = (string) 'ajax';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'offlinepay';
			$plugin->folder  = (string) 'protostorepayment';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore';
			$plugin->folder  = (string) 'user';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_fields';
			$plugin->folder  = (string) 'content';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_emailer';
			$plugin->folder  = (string) 'protostoresystem';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_offlinepay';
			$plugin->folder  = (string) 'system';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'defaultshipping';
			$plugin->folder  = (string) 'protostoreshipping';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore';
			$plugin->folder  = (string) 'system';
			$plugin->enabled = 1;
			// Update record
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


		}

	}


	private function getParam($name)
	{
		$db = Factory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "protostore"');
		$manifest = json_decode($db->loadResult(), true);

		return $manifest[$name];
	}

//	private function initSetup()
//	{
//
//		$db    = Factory::getDbo();
//		$query = 'TRUNCATE TABLE ' . $db->quoteName('#__protostore_setup');
//		$db->setQuery($query);
//		$db->execute();
//
//		$object        = new stdClass();
//		$object->id    = 0;
//		$object->value = 'false';
//
//		$db->insertObject('#__protostore_setup', $object);
//
//	}
//
//	private function importCountries()
//	{
//
//		$countries = file_get_contents(JPATH_ROOT . '/administrator/components/com_protostore/data/countries.json');
//		$countries = json_decode($countries, true);
//
//		$db = Factory::getDbo();
//
//		$query = 'TRUNCATE TABLE ' . $db->quoteName('#__protostore_country');
//		$db->setQuery($query);
//		$db->execute();
//
//
//		foreach ($countries as $country)
//		{
//			$object                    = new stdClass();
//			$object->id                = $country['id'];
//			$object->country_name      = $country['country_name'];
//			$object->country_isocode_2 = $country['country_isocode_2'];
//			$object->country_isocode_3 = $country['country_isocode_3'];
//			$object->requires_vat      = $country['requires_vat'];
//			$object->taxrate           = $country['taxrate'];
//			$object->default           = $country['default'];
//			$object->published         = $country['published'];
//
//			$db->insertObject('#__protostore_country', $object);
//		}
//
//	}
//
//	private function importZones()
//	{
//
//		$zones = file_get_contents(JPATH_ROOT . '/administrator/components/com_protostore/data/zones.json', '', '', '', '');
//		$zones = mb_convert_encoding($zones, "HTML-ENTITIES", "UTF-8");
//		$zones = json_decode($zones, true);
//
//		$db = Factory::getDbo();
//
//		$query = 'TRUNCATE TABLE ' . $db->quoteName('#__protostore_zone');
//		$db->setQuery($query);
//		$db->execute();
//
//
//		foreach ($zones as $zone)
//		{
//			$object               = new stdClass();
//			$object->id           = $zone['id'];
//			$object->country_id   = $zone['country_id'];
//			$object->zone_name    = $zone['zone_name'];
//			$object->zone_isocode = $zone['zone_isocode'];
//			$object->taxrate      = $zone['taxrate'];
//			$object->published    = $zone['published'];
//
//			$db->insertObject('#__protostore_zone', $object);
//		}
//
//	}



}


