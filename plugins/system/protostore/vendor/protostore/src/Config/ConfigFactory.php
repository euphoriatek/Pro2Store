<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Config;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;

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
	 * @return mixed
	 * @throws Exception
	 * @since 1.6
	 */

	public static function get()
	{
		$app = Factory::getApplication();
		return $app->getParams('com_protostore');
	}


}
