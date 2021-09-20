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

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

use Protostore\Render\Render;
use Protostore\Order\OrderFactory;
use Protostore\Dashboard\DashboardFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Product\ProductFactory;


/**
 *
 * @since       1.6
 */
class bootstrap
{

	private $vars;

	public function __construct()
	{
		$this->init();
		$this->setVars();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();


		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/dashboard/dashboard.php', $this->vars);


	}

	/**
	 *
	 * @return array
	 *
	 * @since 1.6
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

	public function setVars(): void
	{

		$this->vars['currencysymbol'] = CurrencyFactory::getDefault()->currencysymbol;
		$this->vars['orders'] = OrderFactory::getList(5);
		$this->vars['products'] = ProductFactory::getList();
		$this->vars['now'] = new Date('now');
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');


		// Bestsellers list
		$this->vars['bestSellers'] = DashboardFactory::getBestsellers();

		// order stats grid
		$this->vars['orderStats'] = DashboardFactory::getOrderStats();


		// Sales Chart
		$this->vars['months'] = DashboardFactory::getMonths();
		$this->vars['monthsSales'] = DashboardFactory::getMonthsSales();

		// Donut chart
		$donutData = DashboardFactory::getDataForCategoryDonut();
		$this->vars['categories'] = $donutData['categories'];
		$this->vars['categorySales'] = $donutData['categorySales'];
		$this->vars['colours'] = $donutData['colours'];

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
		$doc->addScript('/media/com_protostore/js/vue/dashboard/dashboard.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		$doc->addCustomTag('<script id="orders_data" type="application/json">' . json_encode($this->vars['orders']) . '</script>');

		// include prime
//		Utilities::includePrime(array('chart'));


	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	private function addStylesheets()
	{
	}

	/**
	 *
	 *
	 * @since 1.6
	 */


	private function addTranslationStrings()
	{

		$doc = Factory::getDocument();


		$doc->addCustomTag('<script id="successMessage" type="application/json">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED') . '</script>');

	}

}

