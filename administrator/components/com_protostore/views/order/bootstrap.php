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
use Protostore\Order\OrderFactory;
use Protostore\Render\Render;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       2.0
 */
class bootstrap extends AdminModel
{

	private array $vars;


	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$this->init($id);

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/order/order.php', $this->vars);


	}

	/**
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private function init($id): void
	{


		$this->vars['item']     = false;
		$this->vars['currency'] = CurrencyFactory::getDefault();
		$this->vars['locale']   = Factory::getLanguage()->get('tag');

		if ($id)
		{
			$this->vars['item'] = OrderFactory::get($id);
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


		$form = $this->loadForm('com_protostore.order', 'order', array('control' => 'jform', 'load_data' => $loadData));

		if ($item)
		{
//
//			$form->setValue('name', null, $item->name);
//			$form->setValue('coupon_code', null, $item->coupon_code);
//			$form->setValue('amount', null, $item->amount);
//			$form->setValue('percentage', null, $item->percentage);
//			$form->setValue('expiry_date', null, $item->expiry_date);
//			$form->setValue('published', null, $item->published);


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
		$doc->addScript('../media/com_protostore/js/vue/order/order.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		//set up data for vue:
		if ($this->vars['item'])
		{


				$doc->addCustomTag('<script id="p2s_order" type="application/json">' . json_encode($this->vars['item']) . '</script>');


		}


		// include whatever PrimeVue component scripts we need
		Utilities::includePrime(array('inputswitch', 'timeline', 'avatar'));


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

