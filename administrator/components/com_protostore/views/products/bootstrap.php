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

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/products/products.php', $vars);

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
		$vars['categories'] = $this->getCategories();
		$this->addScripts();

		return $vars;


	}

	/**
	 *
	 *
	 * @since version
	 */

	private function addScripts($add = false)
	{


		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/products/products.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		// include prime
//		Utilities::includePrime(array('inputswitch'));


	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 2.0
	 */

	private function getItems()
	{
		return ProductFactory::getList(25, 0);
	}

	private function getCategories()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'));

		$db->setQuery($query);

		return $db->loadObjectList();


	}

}

