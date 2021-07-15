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
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\MVC\Model\AdminModel;

use Joomla\CMS\Uri\Uri;
use Protostore\Render\Render;
use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;
use Protostore\Currency\CurrencyFactory;

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

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/product/product.php', $this->vars);


	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	private function init($id)
	{

		$this->vars['item']              = false;
		$this->vars['available_options'] = ProductFactory::getOptionList();
		$this->vars['available_tags']    = ProductFactory::getAvailableTags();
		$this->vars['currency']          = CurrencyFactory::getDefault();
		$this->vars['locale']            = Factory::getLanguage()->get('tag');

		if ($id)
		{
			$this->vars['item'] = $this->getTheItem($id);
//			echo json_encode($this->vars['item']);
		}


		$this->addScripts();
		$this->addStylesheets();

		$this->vars['form'] = $this->getForm(array('item' => $this->vars['item']), true);


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
			$form->setValue('teaserimage', null, $item->images['image_intro']);
			$form->setValue('fullimage', null, $item->images['image_fulltext']);
			$form->setValue('state', null, $item->joomlaItem->state);
			$form->setValue('publish_up_date', null, $item->joomlaItem->publish_up);
			$form->setValue('taxable', null, $item->taxable);
			$form->setValue('discount', null, $item->discount);
			$form->setValue('base_price', null, $item->basepriceFloat);
			$form->setValue('manage_stock', null, $item->manage_stock);

			$tagsHelper = new TagsHelper();
			$form->setValue('tags', null, $tagsHelper->getTagIds($item->joomlaItem->id, "com_content.article"));


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

	private function addScripts()
	{

		$doc = Factory::getDocument();

		// include the vue script - defer
		$doc->addScript('../media/com_protostore/js/vue/product/product.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));

//		$doc->addScript('https://unpkg.com/primevue@3.5.1/api/api.min.js', array('type' => 'text/javascript'));
//		$doc->addScript('https://unpkg.com/primevue@3.5.1/utils/utils.min.js', array('type' => 'text/javascript'));
//		$doc->addScript('https://unpkg.com/primevue@3.5.1/config/config.min.js', array('type' => 'text/javascript'));
//		$doc->addScript('https://unpkg.com/primevue@3.5.1/overlayeventbus/overlayeventbus.min.js', array('type' => 'text/javascript'));
//		$doc->addScript('https://unpkg.com/primevue@3.5.1/ripple/ripple.min.js', array('type' => 'text/javascript'));
//		$doc->addScript('https://unpkg.com/primevue@3.5.1/multiselect/multiselect.js', array('type' => 'text/javascript'));

		// include prime
		Utilities::includePrime(array('inputswitch', 'chips', 'inputtext', 'inputnumber'));

		if ($this->vars['item'])
		{

			foreach ($this->vars['item'] as $key => $value)
			{
				if (is_string($value))
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . $value . '</script>');
				}
				else
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . json_encode($value) . '</script>');
				}


			}
			foreach ($this->vars['item']->joomlaItem as $key => $value)
			{
				if (is_string($value))
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . $value . '</script>');
				}
				else
				{
					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . json_encode($value) . '</script>');
				}

			}

		}
		$doc->addCustomTag(' <script id="base_url" type="application/json">' . Uri::base() . '</script>');
		$doc->addCustomTag(' <script id="p2s_currency" type="application/json">' . json_encode($this->vars['currency']) . '</script>');
		$doc->addCustomTag(' <script id="p2s_locale" type="application/json">' . json_encode($this->vars['locale']) . '</script>');


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

