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

use Protostore\Bootstrap\listView;
use Protostore\Product\ProductFactory;
use Protostore\Render\Render;



/**
 *
 * @since       1.6
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
	public static $view = 'productoptions';

	public function __construct()
	{
		$this->init();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();
		$this->setVars();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/'.self::$view.'/'.self::$view.'.php', $this->vars);

	}

	/**
	 *
	 * @return void
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
		$this->vars['items']      = $this->getItems();
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');
	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public function getItems(): ?array
	{
		return ProductFactory::getOptionList();
	}


	/**
	 *
	 *
	 * @since 2.0
	 */

	public function addScripts(): void
	{

		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/'.self::$view.'/'.self::$view.'.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));



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

