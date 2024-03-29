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
use Joomla\CMS\Version;
use Joomla\CMS\Uri\Uri;

use Protostore\Currency\CurrencyFactory;
use Protostore\Render\Render;


/**
 *
 * @since 2.0
 */
class bootstrapWrapper
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
	public static $view = 'wrapper';

	public function __construct()
	{
		$this->init();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/'.self::$view.'/'.self::$view.'.php', $this->vars);

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
	 * @since 2.0
	 */


	private function addScripts()
	{

		$doc = Factory::getDocument();

		$doc->addScript('../media/com_protostore/js/bundle.min.js', array('type' => 'text/javascript'));


		$doc->addCustomTag('<script id="base_url" type="application/json">' . Uri::base() . '</script>');
		$doc->addCustomTag(' <script id="currency" type="application/json">' . json_encode($this->vars['currency']) . '</script>');
		$doc->addCustomTag(' <script id="locale" type="application/json">' . $this->vars['locale'] . '</script>');


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

		$doc = Factory::getDocument();
		$doc->addStyleSheet("../media/com_protostore/css/bundle.min.css");
		$doc->addStyleSheet("https://unpkg.com/primeicons@5.0.0/primeicons.css");

	}


}

