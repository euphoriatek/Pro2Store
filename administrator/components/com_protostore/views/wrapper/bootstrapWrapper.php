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

use Joomla\CMS\Uri\Uri;
use Protostore\Currency\CurrencyFactory;
use Protostore\Render\Render;


/**
 *
 * @since 2.0
 */
class bootstrapWrapper
{

	private $vars;

	public function __construct()
	{
		$this->init();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/wrapper/wrapper.php', $this->vars);

	}

	/**
	 * @return void
	 *
	 * @since 2.0
	 */

	private function init()
	{

		$this->setVars();

		$this->addScripts();
		$this->addStylesheets();

	}


	/**
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private function setVars()
	{


		$this->vars['currency']    = CurrencyFactory::getDefault();
		$this->vars['locale']      = Factory::getLanguage()->get('tag');
		$this->vars['breadcrumbs'] = $this->getBreadcrumbs();


	}

	private function getBreadcrumbs()
	{

		$breadcrumbs = array();


		$input = Factory::getApplication()->input;
		$view  = $input->getString('view');

		$breadcrumbs[] = $view;

//		if ($id = $input->get('id'))
//		{
//			$breadcrumbs[] = $this->getBreadcrumbItem($view, $id);
//		}

		return $breadcrumbs;


	}

	private function getBreadcrumbItem($view, $id)
	{

	}

	/**
	 * Function to add the scripts to the header
	 *
	 * @since 1.6
	 */


	private function addScripts()
	{

		$doc = Factory::getDocument();
		$doc->addScript("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js");
		$doc->addScript("https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit.min.js");
		$doc->addScript("https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit-icons.min.js");
		$doc->addScript("https://kit.fontawesome.com/6afbbf2d93.js");


		$doc->addScript('../media/com_protostore/js/vue/bundle.min.js', array('type' => 'text/javascript'));

		$doc->addCustomTag('<script id="base_url" type="application/json">' . Uri::base() . '</script>');
		$doc->addCustomTag(' <script id="currency" type="application/json">' . json_encode($this->vars['currency']) . '</script>');
		$doc->addCustomTag(' <script id="locale" type="application/json">' . $this->vars['locale'] . '</script>');


	}

	/**
	 * Function to add the styles to the header
	 *
	 * @return void
	 *
	 * @since 1.6
	 */


	private function addStylesheets()
	{

		$doc = Factory::getDocument();
		$doc->addStyleSheet("https://cdn.jsdelivr.net/npm/uikit@latest/dist/css/uikit.min.css");
		$doc->addStyleSheet("/media/com_protostore/css/theme.css");
		$doc->addStyleSheet("/media/com_protostore/css/style.css");
		$doc->addStyleSheet("https://unpkg.com/primevue/resources/primevue.min.css");
		$doc->addStyleSheet("https://unpkg.com/primevue/resources/themes/saga-blue/theme.css");
		$doc->addStyleSheet("https://unpkg.com/primeicons/primeicons.css");


	}


}

