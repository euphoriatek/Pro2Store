<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Config;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

use Exception;


/**
 * @package     Protostore\Config
 *
 *              Wrapper for the native Joomla path for retrieving component config. Serves only to keep client code neat!
 *              Simple call:
 *              $config = ConfigFactory::get();
 *              $config->get('node');
 *
 * @since       1.6
 */
class ConfigFactory
{

	/**
	 * @return Registry
	 * @throws Exception
	 * @since 2.0
	 */

	public static function get(): Registry
	{

		return ComponentHelper::getParams('com_protostore');

	}


	public static function getVersion()
	{
		$component = ComponentHelper::getComponent('com_protostore');
		$extension = Table::getInstance('extension');
		$extension->load($component->id);
		$manifest = new Registry($extension->manifest_cache);


		return $manifest->get('version');

	}


}
