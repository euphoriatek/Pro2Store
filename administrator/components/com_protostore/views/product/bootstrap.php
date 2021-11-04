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
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Language\Text;

use Protostore\MediaManager\MediaManagerFactory;
use Protostore\Product\Product;
use Protostore\Render\Render;
use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;
use Protostore\Config\ConfigFactory;

/**
 *
 * @since      1.6
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
	public static $view = 'product';

	public function __construct()
	{


		$input = Factory::getApplication()->input;
		$id    = $input->get('id');

		$this->init($id);

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/' . self::$view . '/' . self::$view . '.php', $this->vars);


	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	private function init($id)
	{

		$config = ConfigFactory::get();

		$this->vars['item']             = false;
		$this->vars['default_category'] = $config->get('defaultproductcategory');
		$this->vars['available_tags']   = ProductFactory::getAvailableTags();
		$this->vars['custom_fields']    = [];
		$this->vars['folderTree']       = MediaManagerFactory::getFolderTree();
		$this->vars['currentPath']      = MediaManagerFactory::getHomePath();
		if ($id)
		{
			$this->vars['item']           = $this->getTheItem($id);
			$this->vars['available_tags'] = ProductFactory::getAvailableTags($id);
			$this->vars['custom_fields']  = ProductFactory::getAvailableCustomFields($id, $this->vars['item']->joomlaItem->catid);
		}


		$this->addScripts();
		$this->addStylesheets();
		$this->addTranslationStrings();

		$this->vars['form'] = $this->getForm(array('item' => $this->vars['item']), true);


	}

	/**
	 *
	 * @return Product|null
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
	 * @since 2.0
	 */

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.

		$item = $data['item'];


		$form = $this->loadForm('com_protostore.' . 'product', 'product', array('control' => 'jform', 'load_data' => $loadData));

		if ($item)
		{

			$form->setValue('title', null, $item->joomlaItem->title);
			$form->setValue('short_description', null, $item->joomlaItem->introtext);
			$form->setValue('long_description', null, $item->joomlaItem->fulltext);
			$form->setValue('access', null, $item->joomlaItem->access);
			$form->setValue('teaserimage', null, $item->images['image_intro']);
			$form->setValue('fullimage', null, $item->images['image_fulltext']);
			$form->setValue('state', null, $item->joomlaItem->state);
			$form->setValue('publish_up_date', null, $item->joomlaItem->publish_up);
			$form->setValue('taxable', null, $item->taxable);
			$form->setValue('discount', null, $item->discountFloat);
			$form->setValue('base_price', null, $item->basepriceFloat);
			$form->setValue('manage_stock', null, $item->manage_stock);
			$form->setValue('shipping_mode', null, $item->shipping_mode);
			$tagsHelper = new TagsHelper();
			$form->setValue('tags', null, $tagsHelper->getTagIds($item->joomlaItem->id, "com_content.article"));



		}

		return $form;
	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function addScripts()
	{

		$doc = Factory::getDocument();

		// include the vue script - defer
//		$doc->addScript('../media/com_protostore/js/vue/' . self::$view . '/' . self::$view . '.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


		// include prime
		Utilities::includePrime(array('inputswitch', 'button', 'chip', 'chips', 'inputtext', 'inputnumber'));

		if ($this->vars['item'])
		{

			foreach ($this->vars['item'] as $key => $value)
			{
				if (is_string($value))
				{
					if ($key === 'id')
					{
						$key = 'product_id';
					}

					$doc->addCustomTag('<script id="jform_' . $key . '_data" type="application/json">' . $value . '</script>');
				}
				else
				{
					if ($key === 'id')
					{
						$key = 'product_id';
					}

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

		$doc->addCustomTag(' <script id="custom_fields_data" type="application/json">' . json_encode($this->vars['custom_fields']) . '</script>');
		$doc->addCustomTag(' <script id="available_tags_data" type="application/json">' . json_encode($this->vars['available_tags']) . '</script>');
		$doc->addCustomTag(' <script id="default_category_data" type="application/json">' . $this->vars['default_category'] . '</script>');
		$doc->addCustomTag(' <script id="folderTree_data" type="application/json">' . json_encode($this->vars['folderTree']) . '</script>');
		$doc->addCustomTag(' <script id="currentPath_data" type="application/json">' . json_encode($this->vars['currentPath']) . '</script>');


	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function addStylesheets()
	{
	}

	/**
	 *
	 *
	 * @since 2.0
	 */


	private function addTranslationStrings()
	{

		$doc = Factory::getDocument();


		$doc->addCustomTag('<script id="COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED" type="application/json">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED') . '</script>');
		$doc->addCustomTag(' <script id="COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT" type="application/json">' . Text::_('COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT') . '</script>');
		$doc->addCustomTag(' <script id="COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE" type="application/json">' . Text::_('COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE') . '</script>');
		$doc->addCustomTag(' <script id="COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT" type="application/json">' . Text::_('COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT') . '</script>');
		$doc->addCustomTag(' <script id="COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL" type="application/json">' . Text::_('COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL') . '</script>');
		$doc->addCustomTag(' <script id="COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL" type="application/json">' . Text::_('COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL') . '</script>');
		$doc->addCustomTag(' <script id="COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE" type="application/json">' . Text::_('COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE') . '</script>');

	}

}

