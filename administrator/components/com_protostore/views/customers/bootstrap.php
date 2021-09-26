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
use Protostore\Render\Render;
use Protostore\Customer\CustomerFactory;
use Protostore\Utilities\Utilities;

/**
 *
 * @since 2.0
 */
class bootstrap implements listView
{

	public $vars;


	public function __construct()
	{
		$this->init();
		$this->setVars();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();


		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/customers/customers.php', $this->vars);

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

		$this->vars['items']      = $this->getItems();
		$this->vars['list_limit'] = Factory::getConfig()->get('list_limit', '25');

	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 2.0
	 */

	public function getItems(): ?array
	{


		return CustomerFactory::getList();

	}


	/**
	 *
	 *
	 * @since 2.0
	 */

	public function addScripts(): void
	{


		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/customers/customers.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		// include prime
		Utilities::includePrime(array('inputswitch'));


	}

	/**
	 *
	 *
	 * @since 2.0
	 */	
	
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
		// TODO: Implement addTranslationStrings() method.
	}

}

