<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

use Protostore\Render\Render;
use Protostore\Customer\CustomerFactory;
use Protostore\Utilities\Utilities;

/**
 *
 * @since 2.0
 */
class bootstrap
{


	public function __construct()
	{
		$this->init();
		$this->addScripts();

		$vars = $this->setVars();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/customers/customers.php', $vars);

	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function init()
	{


	}


	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	private function setVars()
	{

		$vars = array();

		$vars['items']      = $this->getItems();
		$vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');

		return $vars;


	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 2.0
	 */

	private function getItems()
	{


		return CustomerFactory::getList();

	}


	/**
	 *
	 *
	 * @since version
	 */

	private function addScripts()
	{


		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/customers/customers.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		// include prime
		Utilities::includePrime(array('inputswitch'));


	}

}

