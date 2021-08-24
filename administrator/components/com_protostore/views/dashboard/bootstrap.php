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
use Protostore\Utilities\Utilities;


/**
 *
 * @since       1.6
 */
class bootstrap
{

	private $vars;

	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$this->init($id);

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/dashboard/dashboard.php', $this->vars);


	}

	/**
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	private function init($id)
	{

		$this->vars = array();
		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();

	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public function getTheItem($id)
	{

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

