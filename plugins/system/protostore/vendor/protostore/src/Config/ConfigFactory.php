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
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

use Exception;
use stdClass;


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


	/**
	 *
	 * @return stdClass
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public static function getSystemRedirectUrls(): stdClass
	{

		$urls = new stdClass();
		$base = 'index.php?Itemid=';
		$fullBase = Uri::base() . 'index.php?Itemid=';

		$config = self::get();

		$urls->checkout = new stdClass();
		$urls->checkout->short = $base . $config->get('checkout_page_url');
		$urls->checkout->full = $fullBase . $config->get('checkout_page_url');

		$urls->confirmation = new stdClass();
		$urls->confirmation->short = $base . $config->get('confirmation_page_url');
		$urls->confirmation->full = $fullBase . $config->get('confirmation_page_url');

		$urls->cancellation = new stdClass();
		$urls->cancellation->short = $base . $config->get('cancellation_page_url');
		$urls->cancellation->full = $fullBase . $config->get('cancellation_page_url');

		return $urls;



	}


}
