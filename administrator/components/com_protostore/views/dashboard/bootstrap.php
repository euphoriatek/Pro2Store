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
		$vars = $this->setVars();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/dashboard/dashboard.php', $vars);

	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function init()
	{

		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/product/product.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		// include prime
		Utilities::includePrime(array());

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


		$vars['items'] = $this->getItems();


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

	}

}

