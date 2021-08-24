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

use Protostore\Bootstrap\listView;
use Protostore\Render\Render;
use Protostore\Email\EmailFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since 1.6
 */
class bootstrap implements listView
{

	private $vars;

	public function __construct()
	{
		$this->init();
		$this->setVars();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/emailmanager/emailmanager.php', $this->vars);

	}

	/**
	 *
	 * @return void
	 *
	 * @since 1.6
	 */

	public function init(): void
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


		return EmailFactory::getList();

	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	public function addScripts(): void
	{

		$doc = Factory::getDocument();


		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/emailmanager/emailmanager.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		$doc->addCustomTag('<script id="items_data" type="application/json">' . json_encode($this->vars['items']) . '</script>');
		$doc->addCustomTag('<script id="page_size" type="application/json">' . $this->vars['list_limit'] . '</script>');


		// include prime
		Utilities::includePrime(array('inputswitch'));


	}

	/**
	 *
	 *
	 *
	 * @since 1.6
	 */


	public function addTranslationStrings(): void
	{

		$doc = Factory::getDocument();


		$doc->addCustomTag('<script id="confirmLangString" type="application/json">' . Text::_('COM_PROTOSTORE_ORDER_ATTACH_CUSTOMER_CONFIRM_MODAL') . '</script>');

	}

	/**
	 *
	 *
	 * @since 1.6
	 */

	public function addStylesheets(): void
	{
	}
}

