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

use Joomla\CMS\Language\Text;
use Protostore\Render\Render;
use Protostore\Shippingrate\ShippingrateFactory;
use Protostore\Country\CountryFactory;
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
		$this->addTranslationStrings();


		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/shippingratescountry/shippingratescountry.php', $this->vars);

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
	 * @return void
	 *
	 * @since 1.6
	 */

	private function setVars(): void
	{

		$this->vars['items']      = ShippingrateFactory::getList();
		$this->vars['countries']  = CountryFactory::getList();
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');


	}


	/**
	 *
	 *
	 * @since 1.6
	 */

	private function addScripts(): void
	{

		$doc = Factory::getDocument();


		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/shippingratescountry/shippingratescountry.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="items_data" type="application/json">' . json_encode($this->vars['items']) . '</script>');
		$doc->addCustomTag('<script id="countries_data" type="application/json">' . json_encode($this->vars['countries']) . '</script>');
		$doc->addCustomTag('<script id="page_size" type="application/json">' . $this->vars['list_limit'] . '</script>');


		// include prime
		Utilities::includePrime(array('inputtext', 'inputnumber'));


	}

	/**
	 *
	 *
	 * @since 1.6
	 */


	private function addTranslationStrings(): void
	{

		$doc = Factory::getDocument();


		$doc->addCustomTag('<script id="confirmLangString" type="application/json">' . Text::_('COM_PROTOSTORE_ORDER_ATTACH_CUSTOMER_CONFIRM_MODAL') . '</script>');

	}
}

