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

use Protostore\Bootstrap\genericView;
use Protostore\Render\Render;
use Protostore\Utilities\Utilities;

/**
 *
 * @since 2.0
 */
class bootstrap implements genericView
{

	public  $vars;
	public static $view = 'io';


	public function __construct()
	{


		$this->init();
		$this->setVars();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();

		echo Render::render(JPATH_PLUGINS . '/protostore_extended/'.self::$view.'/views/'.self::$view.'/'.self::$view.'.php', $this->vars);

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
	 * @return void
	 *
	 * @since 2.0
	 */

	public function setVars(): void
	{

		$this->vars = array();
		$this->vars['test'] = "TEST";

	}


	/**
	 *
	 *
	 * @since 2.0
	 */

	public function addScripts(): void
	{


		// include prime
		Utilities::includePrime(array('inputswitch'));


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

