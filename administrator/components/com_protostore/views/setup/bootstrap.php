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
use Joomla\CMS\MVC\Model\AdminModel;

use Protostore\Render\Render;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       1.6
 */
class bootstrap extends AdminModel
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
	public static $view = 'setup';


	public function __construct()
	{


		$this->init();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/' . self::$view . '/' . self::$view . '.php', $this->vars);


	}

	/**
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private function init()
	{
		$this->vars = array();

		$this->setVars();

		$this->addScripts();
		$this->addStylesheets();


	}

	private function setVars(): void
	{

		$this->vars['currencies'] = \Protostore\Currency\CurrencyFactory::getList();
		$this->vars['countries'] = \Protostore\Country\CountryFactory::getList();

	}


	/**
	 *
	 * Gets the form and populates it.
	 *
	 *
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return bool|JForm
	 *
	 * @since version
	 */

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.

		return $this->loadForm('com_protostore.' . self::$view, self::$view, array('control' => 'jform', 'load_data' => $loadData));

	}

	/**
	 * Function to add the scripts and data to the header
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private function addScripts()
	{

		$doc = Factory::getDocument();


		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/' . self::$view . '/' . self::$view . '.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="currencies_data" type="application/json">' . json_encode($this->vars['currencies'] ). '</script>');
		$doc->addCustomTag('<script id="countries_data" type="application/json">' . json_encode($this->vars['countries'] ). '</script>');

		// include whatever PrimeVue component scripts we need
		Utilities::includePrime(array('inputswitch'));


	}

	/**
	 * Function to add the styles to the header
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private function addStylesheets()
	{
	}

}

