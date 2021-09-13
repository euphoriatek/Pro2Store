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
use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       1.6
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
	public static $view = 'products';


	public function __construct()
	{
		$this->init();
		$this->setVars();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();


		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/' . self::$view . '/' . self::$view . '.php', $this->vars);

	}

	/**
	 *
	 * @return void
	 *
	 * @since 1.6
	 */

	public function init(): void
	{


	}

	public function setVars(): void
	{

		$this->vars['items']      = $this->getItems();
		$this->vars['categories'] = $this->getCategories();
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');

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
		$doc->addScript('../media/com_protostore/js/vue/' . self::$view . '/' . self::$view . '.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="items_data" type="application/json">' . json_encode($this->vars['items']) . '</script>');
		$doc->addCustomTag('<script id="categories_data" type="application/json">' . json_encode($this->vars['categories']) . '</script>');
		$doc->addCustomTag('<script id="page_size" type="application/json">' . $this->vars['list_limit'] . '</script>');

		// include prime
		Utilities::includePrime(array('inputtext', 'inputnumber'));


	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public function getItems(): ?array
	{
		return ProductFactory::getList();
	}


	/**
	 *
	 *
	 * @since 1.6
	 */

	public function addStylesheets(): void
	{

	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	public function addTranslationStrings(): void
	{

	}

	/**
	 *
	 * @return array|mixed
	 *
	 * @since 1.6
	 */


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

