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
use Joomla\CMS\MVC\Model\AdminModel;

use Protostore\Render\Render;
use Protostore\Currency\CurrencyFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       1.6
 */
class bootstrap extends AdminModel
{


	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$vars = $this->init($id);

		if ($vars['item'])
		{
			echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/currency/currency.php', $vars);
		}
		else
		{
			echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/currency/add_currency.php', $vars);
		}


	}

	/**
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	private function init($id)
	{


		$vars         = array();
		$vars['item'] = false;
		if ($id)
		{
			$vars['item'] = $this->getTheItem($id);

			$this->addScripts();
			$this->addStylesheets();

		}
		else
		{
			$this->addScripts(true);
			$this->addStylesheets(true);
		}

		$vars['form'] = $this->getForm(array('item' => $vars['item']), true);


		return $vars;


	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public function getTheItem($id)
	{
		return CurrencyFactory::get($id);
	}

	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return bool|JForm
	 *
	 * @since version
	 */

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.

		$item = $data['item'];

		$form = $this->loadForm('com_protostore.currency', 'currency', array('control' => 'jform', 'load_data' => $loadData));

		if ($item)
		{

			$form->setValue('name', null, $item->name);
			$form->setValue('iso', null, $item->iso);
			$form->setValue('currencysymbol', null, $item->currencysymbol);
			$form->setValue('rate', null, $item->rate);
			$form->setValue('default', null, $item->default);
			$form->setValue('published', null, $item->published);


		}

		return $form;
	}

	/**
	 *
	 *
	 * @since version
	 */

	private function addScripts($add = false)
	{

		if ($add)
		{
			// include the vue script - defer
			Factory::getDocument()->addScript('../media/com_protostore/js/vue/currency/add_currency.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		}
		else
		{
			// include the vue script - defer
			Factory::getDocument()->addScript('../media/com_protostore/js/vue/currency/currency.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));
		}

		// include prime
		Utilities::includePrime(array('inputswitch'));


	}

	/**
	 *
	 *
	 * @since version
	 */

	private function addStylesheets()
	{
	}

}

