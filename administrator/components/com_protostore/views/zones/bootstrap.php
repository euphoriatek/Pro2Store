<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

use Joomla\CMS\Language\Text;
use Protostore\Bootstrap\listView;
use Protostore\Render\Render;
use Protostore\Country\CountryFactory;
use Protostore\Utilities\Utilities;

/**
 *
 * @since 2.0
 */
class bootstrap implements listView
{

	/**
	 * @var array $vars
	 * @since 2.0
	 */
	public $vars;

	/**
	 * @var string $view
	 * @since 2.0
	 */
	public static $view = 'zones';

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
	 *
	 * @since 2.0
	 */

	public function init(): void
	{


	}


	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public function setVars(): void
	{

		$this->vars['items']      = $this->getItems();
		$this->vars['countries']  = CountryFactory::getList(0, 0, true);
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');

	}

	/**
	 *
	 * @return array|null
	 *
	 * @since 2.0
	 */

	public function getItems(): ?array
	{

		return CountryFactory::getZoneList(0, 0, true, '', null, 'published', 'desc');

	}


	/**
	 *
	 *
	 * @since 2.0
	 */

	public function addScripts(): void
	{
		$doc = Factory::getDocument();

		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/' . self::$view . '/' . self::$view . '.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="countries_data" type="application/json">' . json_encode($this->vars['countries']) . '</script>');
		$doc->addCustomTag('<script id="items_data" type="application/json">' . json_encode($this->vars['items']) . '</script>');
		$doc->addCustomTag('<script id="page_size" type="application/json">' . $this->vars['list_limit'] . '</script>');

		// include prime
		Utilities::includePrime(array('inputswitch'));

	}

	public function addStylesheets(): void
	{
		// TODO: Implement addStylesheets() method.
	}

	/**
	 *
	 *
	 * @since 2.0
	 */
	public function addTranslationStrings(): void
	{

		$doc = Factory::getDocument();


		$doc->addCustomTag('<script id="confirmLangString" type="application/json">' . Text::_('COM_PROTOSTORE_COUNTRIES_DELETE_CONFIRM') . '</script>');

	}
}

