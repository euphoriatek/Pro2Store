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
use Protostore\Country\CountryFactory;
use Protostore\Utilities\Utilities;

/**
 *
 * @since 1.6
 */
class bootstrap implements listView
{


	public $vars;

	public function __construct()
	{
		$this->init();
		$this->addScripts();
		$this->setVars();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/countries/countries.php', $this->vars);

	}

	/**
	 *
	 * @return void
	 *
	 * @since 1.6
	 */

	public function init(): void
	{

		return;

	}


	/**
	 *
	 * @return void
	 *
	 * @since 1.6
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
	 * @since 1.6
	 */

	public function getItems(): ?array
	{

		return CountryFactory::getList(0, 0, false, '', 'published', 'desc');

	}


	/**
	 *
	 * @return void
	 * @since 1.6
	 */

	public function addScripts(): void
	{


		// include the vue script - defer
		Factory::getDocument()->addScript('../media/com_protostore/js/vue/countries/countries.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		// include prime
		Utilities::includePrime(array('inputswitch'));


	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	public function addStylesheets(): void
	{
		return;
	}

	/**
	 *
	 *
	 * @since version
	 */
	public function addTranslationStrings(): void
	{
		return;
	}

}

