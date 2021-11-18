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
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Language\Text;

use Protostore\Country\Zone;
use Protostore\Render\Render;
use Protostore\Country\CountryFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       1.6
 */
class bootstrap extends AdminModel
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
	public static $view = 'zone';

	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$this->init($id);
		$this->addScripts();
		$this->addStylesheets();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/'.self::$view.'/'.self::$view.'.php', $this->vars);


	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	private function init($id)
	{


		$this->vars['item'] = false;
		$this->vars['successMessage'] = Text::_('COM_PROTOSTORE_COUNTRIES_SAVED');

		if($id) {
			$this->vars['item'] = $this->getTheItem($id);
		}



		$this->vars['form'] = $this->getForm(array('item' => $this->vars['item']), true);


	}

	/**
	 *
	 * @return Zone|null
	 *
	 * @since 2.0
	 */

	public function getTheItem($id): ?Zone
	{
		return CountryFactory::getZone($id);
	}

	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return JForm
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public function getForm($data = array(), $loadData = true): JForm
	{
		// Get the form.

		$form = $this->loadForm('com_protostore.'.self::$view, self::$view, array('control' => 'jform', 'load_data' => $loadData));

		return $form;
	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function addScripts($add = false): void
	{


		$doc = Factory::getDocument();

		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/'.self::$view.'/'.self::$view.'.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));
		$doc->addCustomTag('<script id="successMessage" type="application/json">' . $this->vars['successMessage'] . '</script>');

		// include prime
		Utilities::includePrime(array('inputswitch'));

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


	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function addStylesheets()
	{
	}

}

