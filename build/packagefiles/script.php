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

		$db = Factory::getDbo();

		if ($type == 'install')
		{


			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_shortcodes';
			$plugin->folder  = (string) 'content';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_ajaxhelper';
			$plugin->folder  = (string) 'ajax';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'offlinepay';
			$plugin->folder  = (string) 'protostorepayment';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore';
			$plugin->folder  = (string) 'user';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_fields';
			$plugin->folder  = (string) 'content';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_emailer';
			$plugin->folder  = (string) 'protostoresystem';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore_offlinepay';
			$plugin->folder  = (string) 'system';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'defaultshipping';
			$plugin->folder  = (string) 'protostoreshipping';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore';
			$plugin->folder  = (string) 'system';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));

			$plugin          = new stdClass();
			$plugin->type    = 'plugin';
			$plugin->element = 'protostore';
			$plugin->folder  = (string) 'quickicon';
			$plugin->enabled = 1;
			$db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


		}


		if ($type == 'update')
		{

			if (version_compare($this->oldversion, '2.0.0', '<='))
			{
				// now do all the work needed to update the system from V1 to V2



				// for email... first add the language col
				//then move the value of 'params' into the new col
				// then we can remove 'params' col
				$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_email') . ' ADD COLUMN  `language` char(7) DEFAULT \'*\',';
				$db->setQuery($query);
				$db->execute();


				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_email'));

				$db->setQuery($query);

				$emails = $db->loadObjectList();

				foreach ($emails as $email)
				{
					$object = new stdClass();
					$object->language = $email->params;

					$db->updateObject('#__protostore_email', $object, 'id');
				}



				$this->removeUnneededColumns();


				// add hash column
				$query = "SHOW COLUMNS FROM " . $db->quoteName('#__protostore_order') . " LIKE 'hash'";
				$db->setQuery($query);
				$res = $db->loadResult();
				if (!$res)
				{
					$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_order') . ' ADD COLUMN `hash` varchar(255) DEFAULT NULL;';
					$db->setQuery($query);
					$db->execute();
				}

				// add variant_id column
				$query = "SHOW COLUMNS FROM " . $db->quoteName('#__protostore_order_products') . " LIKE 'variant_id'";
				$db->setQuery($query);
				$res = $db->loadResult();
				if (!$res)
				{
					$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_order_products') . ' ADD COLUMN `variant_id` int(11) DEFAULT NULL;';
					$db->setQuery($query);
					$db->execute();
				}

				// add tracking_provider column
				$query = "SHOW COLUMNS FROM " . $db->quoteName('#__protostore_order_tracking') . " LIKE 'tracking_provider'";
				$db->setQuery($query);
				$res = $db->loadResult();
				if (!$res)
				{
					$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_order_tracking') . ' ADD COLUMN `tracking_provider` varchar(255) DEFAULT NULL;';
					$db->setQuery($query);
					$db->execute();
				}

				// add maxPerOrder column
				$query = "SHOW COLUMNS FROM " . $db->quoteName('#__protostore_product') . " LIKE 'maxPerOrder'";
				$db->setQuery($query);
				$res = $db->loadResult();
				if (!$res)
				{
					$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_product') . ' ADD COLUMN `maxPerOrder` int(11) DEFAULT NULL;';
					$db->setQuery($query);
					$db->execute();
				}




				$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_order') . ' CHANGE `donation` `donation_total` int(11) NULL DEFAULT NULL;';
				$db->setQuery($query);
				$db->execute();


				// fix customer_id column
				$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_order') . ' CHANGE `customer` `customer_id` int(11) NOT NULL DEFAULT \'0\';';
				$db->setQuery($query);
				$db->execute();


				/**
				 * Do discount table
				 */


				// add percentage column


				$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_discount') . ' ADD COLUMN `percentage` float DEFAULT NULL;';
				$db->setQuery($query);
				$db->execute();


				// now go through the discount table and make the data updates required
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_discount'));

				$db->setQuery($query);

				$currentDiscounts = $db->loadObjectList();

				foreach ($currentDiscounts as $discount)
				{

					switch ($discount->discount_type)
					{
						case 'amount':
							$discount->discount_type = 1;
						case 'perc':
							$discount->discount_type = 2;
							$discount->percentage    = $discount->amount;
						case 'freeship':
							$discount->discount_type = 3;

					}

					$db->updateObject('#__protostore_discount', $discount, 'id');

				}


// get this fixed ideally but not 100% required... the system will still work without the change.
				// fix discount_type column
//				$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_discount') . ' ALTER COLUMN `discount_type` tinyint(4) DEFAULT NULL;';
//				$db->setQuery($query);
//				$db->execute();
//
//				// fix amount column
//				$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_discount') . ' ALTER COLUMN `amount` int(11) DEFAULT NULL;';
//				$db->setQuery($query);
//				$db->execute();


				// do product options

				//first just get rid of the old table
//				$query = 'DROP TABLE ' . $db->quoteName('#__protostore_product_option') . ';';
//				$db->setQuery($query);
//				$db->execute();


//				// now recreate the table in the form we need.
//				$query = 'CREATE TABLE `#__protostore_product_option` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `product_id` int(11) DEFAULT NULL,
//  `option_name` varchar(255) DEFAULT NULL,
//  `modifier_value` int(11) DEFAULT NULL,
//  `modifier_type` char(6) DEFAULT NULL,
//  PRIMARY KEY (`id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;';
//				$db->setQuery($query);
//				$db->execute();
//
//
//				// now iterate through the option values table and insert the data
//				$query = $db->getQuery(true);
//
//				$query->select('*');
//				$query->from($db->quoteName('#__protostore_product_option_values'));
//
//				$db->setQuery($query);
//
//				$results = $db->loadObjectList();
//
//				if ($results)
//				{
//					foreach ($results as $result)
//					{
//
//						$object = new stdClass();
//						$object->id = 0;
//						$object->product_id = $result->product_id;
//						$object->option_name = $result->optionname;
//						$object->modifier_value = $result->modifiervalue;
//						$object->modifier_type = $result->modifiertype;
//
//						$db->insertObject('#__protostore_product_option', $object);
//
//					}
//				}


			} // end if
		}


	}


	private function getParam($name)
	{
		$db = Factory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "protostore"');
		$manifest = json_decode($db->loadResult(), true);

		return $manifest[$name];
	}

	private function removeUnneededColumns()
	{
		$customerColumns = array(
			'checked_out',
			'checked_out_time',
			'version',
			'hits',
			'access',
			'ordering',
			'params',
			'asset_id'
		);

		foreach ($customerColumns as $column)
		{
			$this->dropColumn('customer', $column);
		}


		$option_presetColumns = array(
			'checked_out',
			'checked_out_time',
			'version',
			'hits',
			'access',
			'ordering',
			'params',
			'asset_id'
		);

		foreach ($option_presetColumns as $column)
		{
			$this->dropColumn('option_preset', $column);
		}


		$productColumns = array(
			'asset_id'
		);

		foreach ($productColumns as $column)
		{
			$this->dropColumn('product', $column);
		}


		$zoneColumns = array(
			'default'
		);

		foreach ($zoneColumns as $column)
		{
			$this->dropColumn('zone', $column);
		}


		$orderColumns = array(
			'asset_id'
		);

		foreach ($orderColumns as $column)
		{
			$this->dropColumn('order', $column);
		}

		$discountColumns = array(
			'asset_id',
			'ordering',
			'access',
			'hits',
			'version',
			'checked_out_time',
			'checked_out'
		);

		foreach ($discountColumns as $column)
		{
			$this->dropColumn('discount', $column);
		}

		$customerAddressColumns = array('asset_id');

		foreach ($customerAddressColumns as $column)
		{
			$this->dropColumn('customer_address', $column);
		}

		$emailColumns = array('asset_id', 'params');

		foreach ($emailColumns as $column)
		{
			$this->dropColumn('email', $column);
		}

	}


	private function dropColumn($tableSuffix, $column)
	{
		$db    = Factory::getDbo();
		$query = "SHOW COLUMNS FROM " . $db->quoteName('#__protostore_' . $tableSuffix) . " LIKE '" . $column . "'";
		$db->setQuery($query);
		$res = $db->loadResult();
		if ($res)
		{
			$query = 'ALTER TABLE ' . $db->quoteName('#__protostore_' . $tableSuffix) . ' DROP COLUMN ' . $db->quoteName($column);
			$db->setQuery($query);
			$db->execute();
		}
	}


}


