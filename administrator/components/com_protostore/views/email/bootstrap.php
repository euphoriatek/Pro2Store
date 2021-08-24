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

use Protostore\Currency\CurrencyFactory;
use Protostore\Email\EmailFactory;
use Protostore\Render\Render;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       2.0
 */
class bootstrap extends AdminModel
{

	private $vars;


	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$this->init($id);

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/email/email.php', $this->vars);


	}

	/**
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private function init($id)
	{


		$this->vars['item']     = false;
		$this->vars['currency'] = CurrencyFactory::getDefault();
		$this->vars['locale']   = Factory::getLanguage()->get('tag');

		if ($id)
		{
			$this->vars['item'] = EmailFactory::get($id);
		}

		$this->addScripts();
		$this->addStylesheets();


		$this->vars['form'] = $this->getForm(array('item' => $this->vars['item']), true);


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


		$form = $this->loadForm('com_protostore.email', 'email', array('control' => 'jform', 'load_data' => $loadData));

		if ($item)
		{

			$form->setValue('body', null, $item->body);


		}

		return $form;
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
		$doc->addScript('../media/com_protostore/js/vue/email/email.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		//set up data for vue:
		if ($this->vars['item'])
		{


			$doc->addCustomTag('<script id="p2s_email" type="application/json">' . json_encode($this->vars['item']) . '</script>');


		}


		// include whatever PrimeVue component scripts we need
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

