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

use Protostore\Bootstrap\listView;
use Protostore\Render\Render;
use Protostore\Order\OrderFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since 1.6
 */
class bootstrap implements listView
{


	/**
	 * @var array $vars


	 * @since 1.6
	 */
	public $vars;

	/**
	 * @var string $view
	 * @since 1.6
	 */
	public static $view = 'orders';

	public function __construct()
	{
		$this->init();
		$this->setVars();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();



		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/'.self::$view.'/'.self::$view.'.php', $this->vars);

	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	public function init(): void
	{


	}


	/**
	 *
	 * @return void
	 *
	 * @since 1.6
	 */

	public function setVars(): void
	{
		
		$this->vars['items'] = $this->getItems();
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');
		$this->vars['statuses'] = OrderFactory::getStatuses();

	}

	/**
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	public function getItems(): ?array
	{


		return OrderFactory::getList();

	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	public function addScripts(): void
	{

		$doc = Factory::getDocument();


		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/'.self::$view.'/'.self::$view.'.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="statuses_data" type="application/json">' . json_encode($this->vars['statuses']) . '</script>');
		$doc->addCustomTag('<script id="items_data" type="application/json">' . json_encode($this->vars['items']) . '</script>');
		$doc->addCustomTag('<script id="page_size" type="application/json">' . $this->vars['list_limit'] . '</script>');

		// include prime
		Utilities::includePrime(array('inputswitch', 'dropdown'));


	}

	public function addStylesheets(): void
	{
		// TODO: Implement addStylesheets() method.
	}

	public function addTranslationStrings(): void
	{
		// TODO: Implement addTranslationStrings() method.
	}
}

