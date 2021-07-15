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
use Protostore\Order\OrderFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since 2.0
 */
class bootstrap
{

	private array $vars;

	public function __construct()
	{
		$this->init();
		$this->setVars();

		$this->addScripts();
		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/orders/orders.php', $this->vars);

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




		$this->vars['items'] = $this->getItems();
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');




	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 2.0
	 */

	private function getItems()
	{


		return OrderFactory::getList();

	}

	/**
	 *
	 *
	 * @since version
	 */

	private function addScripts()
	{

		$doc = Factory::getDocument();


		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/orders/orders.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="items_data" type="application/json">' . json_encode($this->vars['items']) . '</script>');
		$doc->addCustomTag('<script id="page_size" type="application/json">' . $this->vars['list_limit'] . '</script>');

		// include prime
		Utilities::includePrime(array('inputswitch'));


	}

}

