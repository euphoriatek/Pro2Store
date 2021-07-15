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
use Protostore\Product\ProductFactory;
use Protostore\Render\Render;



/**
 *
 * @since       2.0
 */
class bootstrap
{


	public function __construct()
	{
		$vars = $this->init();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/productoptions/productoptions.php', $vars);

	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	private function init()
	{

		$vars = array();


		$vars['items']      = $this->getItems();
		$vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');
		$this->addScripts();

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
		return ProductFactory::getOptionList();
	}


	/**
	 *
	 *
	 * @since 1.6
	 */

	private function addScripts($add = false)
	{

		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/productoptions/productoptions.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));



	}


}

