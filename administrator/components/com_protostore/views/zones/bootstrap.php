<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

defined('_JEXEC') or die('Restricted access');

use Protostore\Render\Render;
use Protostore\Product\ProductFactory;


/**
 *
 * @since       2.0
 */

class bootstrap
{



	public function __construct()
	{
		$vars = $this->init();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/zones/zones.php', $vars);

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
		return ProductFactory::getList();
	}

}

