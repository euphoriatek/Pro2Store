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
use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;


/**
 *
 * @since       2.0
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
			echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/product/product.php', $vars);
		}
		else
		{
			echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/product/add_product.php', $vars);
		}


	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
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
	 * @since 2.0
	 */

	public function getTheItem($id)
	{
		return ProductFactory::get($id);
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

		// default to physical
		$form_type = 'physical';

		if ($item)
		{
			switch ($item->product_type)
			{
				case 0:
					$form_type = 'physical';
					break;
				case 1:
					$form_type = 'digital';
					break;
			}
		}

		$form = $this->loadForm('com_protostore.' . $form_type . '_product', $form_type . '_product', array('control' => 'jform', 'load_data' => $loadData));

		if ($item)
		{

			$form->setValue('title', null, $item->joomlaItem->title);
			$form->setValue('short_description', null, $item->joomlaItem->introtext);
			$form->setValue('long_description', null, $item->joomlaItem->fulltext);
			$form->setValue('teaserimage', null, $item->getImage_intro()->path);
			$form->setValue('fullimage', null, $item->getImage_fulltext()->path);
			$form->setValue('publish_up_date', null, $item->joomlaItem->publish_up);
			$form->setValue('taxable', null, $item->taxable);
			$form->setValue('discount', null, $item->discount);
			$form->setValue('base_price', null, $item->basepricefloat);


			switch ($item->product_type)
			{
				case 0:
					// add physical only form stuff here.
					$form->setValue('shipping_mode', null, $item->shipping_mode);
					break;
				case 1:
					// add digital only form stuff here.
					break;
			}

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
			Factory::getDocument()->addScript('../media/com_protostore/js/vue/product/add_product.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

		}
		else
		{
			// include the vue script - defer
			Factory::getDocument()->addScript('../media/com_protostore/js/vue/product/product.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));
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

