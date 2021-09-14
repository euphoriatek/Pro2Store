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
use Joomla\CMS\Language\Text;


use Protostore\Country\CountryFactory;
use Protostore\Customer\Customer;
use Protostore\Render\Render;
use Protostore\Customer\CustomerFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       1.6
 */
class bootstrap extends AdminModel
{

	/**
	 * @var array $vars
	 * @since 1.6
	 */
	public $vars;

	/**
	 * @var string $view
	 * @since 1.6
	 */
	public static $view = 'customer';


	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$this->init($id);

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/' . self::$view . '/' . self::$view . '.php', $this->vars);


	}

	/**
	 *
	 * @param $id
	 *
	 * @return void
	 *
	 * @since 1.6
	 */

	private function init($id)
	{


		$this->vars['item'] = false;

		if ($id)
		{
			$this->vars['item'] = $this->getTheItem($id);
		}

		$this->vars['countries'] = CountryFactory::getList(0, 0, true);


		$this->vars['form']                 = $this->getForm(array('item' => $this->vars['item']), true);
		$this->vars['deleteConfirmMessage'] = Text::_('COM_PROTOSTORE_CUSTOMER_DELETE_CONFIRM');


		$this->addScripts();
		$this->addStylesheets();


	}

	/**
	 *
	 * Get the items for edit
	 *
	 * @return false|Customer
	 *
	 * @since 1.6
	 */

	public function getTheItem($id)
	{
		return CustomerFactory::get($id);
	}

	/**
	 *
	 * Gets the form and populates it.
	 *
	 *
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


		$form = $this->loadForm('com_protostore.' . self::$view, self::$view, array('control' => 'jform', 'load_data' => $loadData));

		if ($item)
		{

			$form->setValue('name', null, $item->name);
			$form->setValue('email', null, $item->email);
			$form->setValue('j_user_id', null, $item->j_user_id);
			$form->setValue('published', null, $item->published);


		}

		return $form;
	}

	/**
	 * Function to add the scripts and data to the header
	 *
	 * @return void
	 *
	 * @since 1.6
	 */

	private function addScripts()
	{

		$doc = Factory::getDocument();


		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/' . self::$view . '/' . self::$view . '.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		//set up data for vue:
		if ($this->vars['item'])
		{

			foreach ($this->vars['item'] as $key => $value)
			{
				if (is_string($value))
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . $value . '</script>');
				}

				if (is_integer($value))
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . $value . '</script>');
				}

				if (is_array($value))
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . json_encode($value) . '</script>');
				}
				if (is_object($value))
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . json_encode($value) . '</script>');
				}
			}

		}

		$doc->addCustomTag(' <script id="deleteConfirmMessage" type="application/json">' . $this->vars['deleteConfirmMessage'] . '</script>');


		// include whatever PrimeVue component scripts we need
		Utilities::includePrime(array('inputswitch'));


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
	}

}

