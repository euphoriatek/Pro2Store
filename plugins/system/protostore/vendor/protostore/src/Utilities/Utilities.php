<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Utilities;

defined('_JEXEC') or die('Restricted access');

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\String\StringHelper;

use Protostore\Address\Address;
use Protostore\Cart\Cart;
use Protostore\Cart\CartFactory;
use Protostore\Product\Product;

use DateTimeZone;


class Utilities
{


	public static function getUserListForSelect()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select(array('id', 'name'));
		$query->from($db->quoteName('#__users'));

		$db->setQuery($query);

		return $db->loadObjectList();

	}

	/**
	 *
	 * @return int|null
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function getCurrentItemId(): ?int
	{

		$input = Factory::getApplication()->input;

		if ($input->get('option') == 'com_content')
		{
			if ($input->get('view') == 'article')
			{
				return $input->getInt('id');
			}
		}

		return null;


	}

	public static function getUrlFromMenuItem($id)
	{
		$menuItem = Factory::getApplication()->getMenu()->getItem($id);

		if ($menuItem)
		{
			return $menuItem->link;
		}


	}

	public static function getFieldId($name)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__fields'));
		$query->where($db->quoteName('name') . ' = ' . $db->quote($name));

		$db->setQuery($query);

		return $db->loadResult();

	}

	public static function getFieldValue($fieldid, $itemid)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('value');
		$query->from($db->quoteName('#__fields_values'));
		$query->where($db->quoteName('field_id') . ' = ' . $db->quote($fieldid));
		$query->where($db->quoteName('item_id') . ' = ' . $db->quote($itemid));

		$db->setQuery($query);

		return $db->loadResult();

	}

	public static function getCategory($itemid)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select(array('a.catid', 'b.title'))
			->from($db->quoteName('#__content', 'a'))
			->join('INNER', $db->quoteName('#__categories', 'b') . ' ON ' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id'))
			->where($db->quoteName('a.id') . ' = ' . $db->quote($itemid));

		$db->setQuery($query);

		$result = $db->loadObject();

		return $result->title;

	}


	public static function getCategories()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'));
		$query->where($db->quoteName('published') . ' = 1');

		$db->setQuery($query);


		return $db->loadObjectList();


	}


	public static function getCategoriesForOptions()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select(array('id', 'title'));
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'));
		$query->where($db->quoteName('published') . ' = 1');

		$db->setQuery($query);

		$results = $db->loadObjectList();

		$categories = array();

		foreach ($results as $result)
		{
			$categories[$result->title] = $result->id;
		}

		return $categories;


	}

	public static function getCatList($parent = 'root', $showCount = 1)
	{
		$options               = array();
		$options['countItems'] = $showCount;

		$categories = \Joomla\CMS\Categories\Categories::getInstance('Content', $options);
		$category   = $categories->get($parent);

		if ($category !== null)
		{
			$items = $category->getChildren();

			$count = 0;

			if ($count > 0 && count($items) > $count)
			{
				$items = array_slice($items, 0, $count);
			}

			return $items;
		}
	}


	public static function getTags()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__tags'));
		$query->where($db->quoteName('published') . ' = 1');
		$query->where($db->quoteName('level') . ' > 0');

		$db->setQuery($query);


		return $db->loadObjectList();


	}


	public static function getAllProducts()
	{

		$products = array();

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('joomla_item_id');
		$query->from($db->quoteName('#__protostore_product'));

		$db->setQuery($query);

		$results = $db->loadColumn();

		foreach ($results as $result)
		{
			$products[] = new Product($result);
		}

		return $products;


	}

	public static function getProductsByCategory($catid)
	{

		$products = array();

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.joomla_item_id');
		$query->from($db->quoteName('#__protostore_product', 'a'));
		$query->join('INNER', $db->quoteName('#__content', 'b') . ' ON ' . $db->quoteName('a.joomla_item_id') . ' = ' . $db->quoteName('b.id'));
		$query->where($db->quoteName('b.catid') . ' = ' . $db->quote($catid));

		$db->setQuery($query);

		$results = $db->loadColumn();

		foreach ($results as $result)
		{
			$products[] = new Product($result);
		}

		return $products;


	}

	public static function getProductsByCategories($catids = array())
	{

		if (!is_array($catids))
		{
			$catids = array($catids);
		}

		$products = array();

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.joomla_item_id');
		$query->from($db->quoteName('#__protostore_product', 'a'));
		$query->join('INNER', $db->quoteName('#__content', 'b') . ' ON ' . $db->quoteName('a.joomla_item_id') . ' = ' . $db->quoteName('b.id'));
		$query->where($db->quoteName('b.catid') . ' IN ( ' . implode(',', $catids) . ')');
		$query->where($db->quoteName('b.state') . ' = 1');

		$db->setQuery($query);

		$results = $db->loadColumn();

		foreach ($results as $result)
		{
			$products[] = new Product($result);
		}

		return $products;


	}

	public static function getTitle($itemid)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select('*')
			->from($db->quoteName('#__content'))
			->where($db->quoteName('id') . ' = ' . $db->quote($itemid));

		$db->setQuery($query);

		$result = $db->loadObject();

		return $result->title;

	}


	public static function getItem($itemid)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select('*')
			->from($db->quoteName('#__content'))
			->where($db->quoteName('id') . ' = ' . $db->quote($itemid));

		$db->setQuery($query);

		return $db->loadObject();

	}


	public static function getJoomlaItemIdFromProductId($product_id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select('joomla_item_id')
			->from($db->quoteName('#__protostore_product'))
			->where($db->quoteName('id') . ' = ' . $db->quote($product_id));

		$db->setQuery($query);

		return $db->loadResult();

	}

	public static function getProductIdFromJoomlaItemId($joomla_item_id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select('id')
			->from($db->quoteName('#__protostore_product'))
			->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		return $db->loadResult();

	}

	/**
	 *
	 * @return string
	 *
	 * @since 1.0
	 */

	public static function getDate(): string
	{
		$config   = Factory::getConfig();
		$timezone = $config->get('offset');

		$timezone = new DateTimeZone($timezone);

		$date = Factory::getDate();
		$date->setTimezone($timezone);

		return $date->toSql(true);
	}

	/**
	 * @param           $email
	 * @param   int     $s
	 * @param   string  $d
	 * @param   string  $r
	 * @param   false   $img
	 * @param   array   $atts
	 *
	 * @return string
	 *
	 * @since 1.0
	 */

	public static function getGravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
	{
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email)));
		$url .= "?s=$s&d=$d&r=$r";
		if ($img)
		{
			$url = '<img src="' . $url . '"';
			foreach ($atts as $key => $val)
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}

		return $url;
	}


	public static function getUntranslatedOrderStatus($orderid)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('order_status');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($orderid));

		$db->setQuery($query);

		return $db->loadResult();

	}

	public static function getPresetValue($id)
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_option_preset'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		return $result->options;

	}


	public static function getOptionPresets()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_option_preset'));

		$db->setQuery($query);

		return $db->loadObjectList();

	}

	public static function getCookieID()
	{

		return Factory::getApplication()->input->cookie->get('yps-cart', null);

	}

	public static function getCustomerAddresses($customerId)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_customer_address'));
		$query->where($db->quoteName('customer_id') . ' = ' . $db->quote($customerId));
		$db->setQuery($query);

		$customerAddresses = $db->loadObjectList();


		$addresses = array();

		foreach ($customerAddresses as $addressid)
		{

			$addresses[] = new Address($addressid->id);

		}

		return $addresses;

	}

	public static function getCustomerIdByCurrentUserId($userid = null)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_customer'));
		if ($userid)
		{
			$query->where($db->quoteName('j_user_id') . ' = ' . $db->quote($userid));
		}
		else
		{
			$query->where($db->quoteName('j_user_id') . ' = ' . $db->quote(Factory::getUser()->id));
		}

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			return $result;
		}
		else
		{
			return 0;
		}

	}


	public static function selectionTranslation($value, $name)
	{
		// Array of order_status language strings
		if ($name === 'order_status')
		{
			$order_statusArray = array(
				'P' => Text::_('COM_PROTOSTORE_ORDER_PENDING'),
				'C' => Text::_('COM_PROTOSTORE_ORDER_CONFIRMED'),
				'X' => Text::_('COM_PROTOSTORE_ORDER_CANCELLED'),
				'R' => Text::_('COM_PROTOSTORE_ORDER_REFUNDED'),
				'S' => Text::_('COM_PROTOSTORE_ORDER_SHIPPED'),
				'F' => Text::_('COM_PROTOSTORE_ORDER_COMPLETED'),
				'D' => Text::_('COM_PROTOSTORE_ORDER_DENIED')
			);
			// Now check if value is found in this array
			if (isset($order_statusArray[$value]) && self::checkString($order_statusArray[$value]))
			{
				return $order_statusArray[$value];
			}
		}
		// Array of order_paid language strings
		if ($name === 'order_paid')
		{
			$order_paidArray = array(
				1 => Text::_('COM_PROTOSTORE_ORDER_YES'),
				0 => Text::_('COM_PROTOSTORE_ORDER_NO')
			);
			// Now check if value is found in this array
			if (isset($order_paidArray[$value]) && self::checkString($order_paidArray[$value]))
			{
				return $order_paidArray[$value];
			}
		}

		return $value;
	}


	public static function getOrderStatusFromCharacterCode($CharacterCode)
	{
		switch ($CharacterCode)
		{
			case 'P':
				return 'pending';
			case 'C':
				return 'confirmed';
			case 'X':
				return 'cancelled';
			case 'R':
				return 'refunded';
			case 'S':
				return 'shipped';
			case 'F':
				return 'completed';
			case 'D':
				return 'denied';

		}
	}


	public static function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}

		return false;
	}

	public static function getNewSku($itemid)
	{


		return self::getFieldValue(self::getFieldId('yps-sku'), $itemid);
	}


	public static function isShippingAssigned()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('shipping_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}

	}


	public static function isGuestCheckoutValid()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('shipping_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$shipping = $db->loadResult();

		if ($shipping)
		{
			$query = $db->getQuery(true);

			$query->select('shipping_address_id');
			$query->from($db->quoteName('#__protostore_cart'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

			$db->setQuery($query);

			$billing = $db->loadResult();

			if ($billing)
			{
				return true;
			}
		}
		else
		{
			return false;
		}

	}

	public static function isBillingAssigned()
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('billing_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			return true;
		}
		else
		{
			return false;

		}
	}


	public static function adjustBrightness($hex, $steps)
	{
		// Steps should be between -255 and 255. Negative = darker, positive = lighter
		$steps = max(-255, min(255, $steps));

		// Normalize into a six character long hex string
		$hex = str_replace('#', '', $hex);
		if (strlen($hex) == 3)
		{
			$hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
		}

// Split into three parts: R, G and B
		$color_parts = str_split($hex, 2);
		$return      = '#';

		foreach ($color_parts as $color)
		{
			$color  = hexdec($color); // Convert to decimal
			$color  = max(0, min(255, $color + $steps)); // Adjust color
			$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
		}

		return $return;
	}


	public static function processTagsByName($names)
	{


		$tagArray = array();

		$db = Factory::getDbo();

		foreach ($names as $name)
		{
			$query = $db->getQuery(true);

			$query->select('id');
			$query->from($db->quoteName('#__tags'));
			$query->where($db->quoteName('title') . ' = ' . $db->quote($name));

			$db->setQuery($query);

			$tag = $db->loadResult();

			if ($tag)
			{
				$tagArray[] = $tag;
			}
			else
			{
				$tagArray[] = '#new#' . $name;
			}

		}


		return $tagArray;


	}

	public static function isAliasOkForThisProduct($alias, $id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('alias');
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));
		$query->setLimit('1');

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result == $alias)
		{
			return true;
		}
		else
		{
			return false;
		}

	}


	public static function generateUniqueAlias($alias, $table = false)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('alias');

		if ($table)
		{
			$query->from($db->quoteName('#__' . $table));
		}
		else
		{
			$query->from($db->quoteName('#__content'));
		}


		$query->where($db->quoteName('alias') . ' = ' . $db->quote($alias));
		$query->setLimit('1');

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			// already exists - increment and return
			return StringHelper::increment($alias, 'dash');
		}
		else
		{
			return $alias;
		}


	}

	public static function includePrime(array $nodes)
	{

		if (!is_array($nodes))
		{
			return;
		}


		$document = Factory::getDocument();

		foreach ($nodes as $node)
		{
			self::loadPrimeNode($node, $document);
		}

	}

	private static function loadPrimeNode($node, $document)
	{

		switch ($node)
		{
			case "button":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.button=function(t,e){"use strict";function o(t){return t&&"object"==typeof t&&"default"in t?t:{default:t}}var i={name:"Button",props:{label:{type:String},icon:{type:String},iconPos:{type:String,default:"left"},badge:{type:String},badgeClass:{type:String,default:null},loading:{type:Boolean,default:!1},loadingIcon:{type:String,default:"pi pi-spinner pi-spin"}},computed:{buttonClass(){return{"p-button p-component":!0,"p-button-icon-only":this.icon&&!this.label,"p-button-vertical":("top"===this.iconPos||"bottom"===this.iconPos)&&this.label,"p-disabled":this.$attrs.disabled||this.loading,"p-button-loading":this.loading,"p-button-loading-label-only":this.loading&&!this.icon&&this.label}},iconClass(){return[this.loading?"p-button-loading-icon "+this.loadingIcon:this.icon,"p-button-icon",{"p-button-icon-left":"left"===this.iconPos&&this.label,"p-button-icon-right":"right"===this.iconPos&&this.label,"p-button-icon-top":"top"===this.iconPos&&this.label,"p-button-icon-bottom":"bottom"===this.iconPos&&this.label}]},badgeStyleClass(){return["p-badge p-component",this.badgeClass,{"p-badge-no-gutter":this.badge&&1===String(this.badge).length}]},disabled(){return this.$attrs.disabled||this.loading}},directives:{ripple:o(t).default}};const n={class:"p-button-label"};return i.render=function(t,o,i,l,s,a){const c=e.resolveDirective("ripple");return e.withDirectives((e.openBlock(),e.createBlock("button",{class:a.buttonClass,type:"button",disabled:a.disabled},[e.renderSlot(t.$slots,"default",{},(()=>[i.loading&&!i.icon?(e.openBlock(),e.createBlock("span",{key:0,class:a.iconClass},null,2)):e.createCommentVNode("",!0),i.icon?(e.openBlock(),e.createBlock("span",{key:1,class:a.iconClass},null,2)):e.createCommentVNode("",!0),e.createVNode("span",n,e.toDisplayString(i.label||" "),1),i.badge?(e.openBlock(),e.createBlock("span",{key:2,class:a.badgeStyleClass},e.toDisplayString(i.badge),3)):e.createCommentVNode("",!0)]))],10,["disabled"])),[[c]])},i}(primevue.ripple,Vue);');
				break;
			case "inputswitch":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputswitch=function(e){"use strict";var t={name:"InputSwitch",inheritAttrs:!1,emits:["click","update:modelValue","change","input"],props:{modelValue:{type:null,default:!1},class:null,style:null,trueValue:{type:null,default:!0},falseValue:{type:null,default:!1}},data:()=>({focused:!1}),methods:{onClick(e){if(!this.$attrs.disabled){const t=this.checked?this.falseValue:this.trueValue;this.$emit("click",e),this.$emit("update:modelValue",t),this.$emit("change",e),this.$emit("input",t),this.$refs.input.focus()}e.preventDefault()},onFocus(){this.focused=!0},onBlur(){this.focused=!1}},computed:{containerClass(){return["p-inputswitch p-component",this.class,{"p-inputswitch-checked":this.checked,"p-disabled":this.$attrs.disabled,"p-focus":this.focused}]},checked(){return this.modelValue===this.trueValue}}};const i={class:"p-hidden-accessible"},n=e.createVNode("span",{class:"p-inputswitch-slider"},null,-1);return function(e,t){void 0===t&&(t={});var i=t.insertAt;if(e&&"undefined"!=typeof document){var n=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===i&&n.firstChild?n.insertBefore(s,n.firstChild):n.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}(\'\n.p-inputswitch {\n    position: relative;\n    display: inline-block;\n}\n.p-inputswitch-slider {\n    position: absolute;\n    cursor: pointer;\n    top: 0;\n    left: 0;\n    right: 0;\n    bottom: 0;\n}\n.p-inputswitch-slider:before {\n    position: absolute;\n    content: "";\n    top: 50%;\n}\n\'),t.render=function(t,s,c,l,o,u){return e.openBlock(),e.createBlock("div",{class:u.containerClass,onClick:s[4]||(s[4]=e=>u.onClick(e)),style:c.style},[e.createVNode("div",i,[e.createVNode("input",e.mergeProps({ref:"input",type:"checkbox",checked:u.checked},t.$attrs,{onFocus:s[1]||(s[1]=e=>u.onFocus(e)),onBlur:s[2]||(s[2]=e=>u.onBlur(e)),onKeydown:s[3]||(s[3]=e.withKeys(e.withModifiers((e=>u.onClick(e)),["prevent"]),["enter"])),role:"switch","aria-checked":u.checked}),null,16,["checked","aria-checked"])]),n],6)},t}(Vue);');
				break;
			case "chips":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.chips=function(e){"use strict";var t={name:"Chips",inheritAttrs:!1,emits:["update:modelValue","add","remove"],props:{modelValue:{type:Array,default:null},max:{type:Number,default:null},separator:{type:String,default:null},addOnBlur:{type:Boolean,default:null},allowDuplicate:{type:Boolean,default:!0},class:null,style:null},data:()=>({inputValue:null,focused:!1}),methods:{onWrapperClick(){this.$refs.input.focus()},onInput(e){this.inputValue=e.target.value},onFocus(){this.focused=!0},onBlur(e){this.focused=!1,this.addOnBlur&&this.addItem(e,e.target.value,!1)},onKeyDown(e){const t=e.target.value;switch(e.which){case 8:0===t.length&&this.modelValue&&this.modelValue.length>0&&this.removeItem(e,this.modelValue.length-1);break;case 13:t&&t.trim().length&&!this.maxedOut&&this.addItem(e,t,!0);break;default:this.separator&&","===this.separator&&188===e.which&&this.addItem(e,t,!0)}},onPaste(e){if(this.separator){let t=(e.clipboardData||window.clipboardData).getData("Text");if(t){let n=this.modelValue||[],i=t.split(this.separator);i=i.filter((e=>this.allowDuplicate||-1===n.indexOf(e))),n=[...n,...i],this.updateModel(e,n,!0)}}},updateModel(e,t,n){this.$emit("update:modelValue",t),this.$emit("add",{originalEvent:e,value:t}),this.$refs.input.value="",this.inputValue="",n&&e.preventDefault()},addItem(e,t,n){if(t&&t.trim().length){let i=this.modelValue?[...this.modelValue]:[];(this.allowDuplicate||-1===i.indexOf(t))&&(i.push(t),this.updateModel(e,i,n))}},removeItem(e,t){if(this.$attrs.disabled)return;let n=[...this.modelValue];const i=n.splice(t,1);this.$emit("update:modelValue",n),this.$emit("remove",{originalEvent:e,value:i})}},computed:{maxedOut(){return this.max&&this.modelValue&&this.max===this.modelValue.length},containerClass(){return["p-chips p-component p-inputwrapper",this.class,{"p-inputwrapper-filled":this.modelValue&&this.modelValue.length||this.inputValue&&this.inputValue.length,"p-inputwrapper-focus":this.focused}]}}};const n={class:"p-chips-token-label"},i={class:"p-chips-input-token"};return function(e,t){void 0===t&&(t={});var n=t.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],l=document.createElement("style");l.type="text/css","top"===n&&i.firstChild?i.insertBefore(l,i.firstChild):i.appendChild(l),l.styleSheet?l.styleSheet.cssText=e:l.appendChild(document.createTextNode(e))}}("\n.p-chips {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n}\n.p-chips-multiple-container {\n    margin: 0;\n    padding: 0;\n    list-style-type: none;\n    cursor: text;\n    overflow: hidden;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -ms-flex-wrap: wrap;\n        flex-wrap: wrap;\n}\n.p-chips-token {\n    cursor: default;\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-flex: 0;\n        -ms-flex: 0 0 auto;\n            flex: 0 0 auto;\n}\n.p-chips-input-token {\n    -webkit-box-flex: 1;\n        -ms-flex: 1 1 auto;\n            flex: 1 1 auto;\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n}\n.p-chips-token-icon {\n    cursor: pointer;\n}\n.p-chips-input-token input {\n    border: 0 none;\n    outline: 0 none;\n    background-color: transparent;\n    margin: 0;\n    padding: 0;\n    -webkit-box-shadow: none;\n            box-shadow: none;\n    border-radius: 0;\n    width: 100%;\n}\n.p-fluid .p-chips {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n}\n"),t.render=function(t,l,a,s,o,p){return e.openBlock(),e.createBlock("div",{class:p.containerClass,style:a.style},[e.createVNode("ul",{class:["p-inputtext p-chips-multiple-container",{"p-disabled":t.$attrs.disabled,"p-focus":o.focused}],onClick:l[6]||(l[6]=e=>p.onWrapperClick())},[(e.openBlock(!0),e.createBlock(e.Fragment,null,e.renderList(a.modelValue,((i,l)=>(e.openBlock(),e.createBlock("li",{key:`${l}_${i}`,class:"p-chips-token"},[e.renderSlot(t.$slots,"chip",{value:i},(()=>[e.createVNode("span",n,e.toDisplayString(i),1)])),e.createVNode("span",{class:"p-chips-token-icon pi pi-times-circle",onClick:e=>p.removeItem(e,l)},null,8,["onClick"])])))),128)),e.createVNode("li",i,[e.createVNode("input",e.mergeProps({ref:"input",type:"text"},t.$attrs,{onFocus:l[1]||(l[1]=(...e)=>p.onFocus&&p.onFocus(...e)),onBlur:l[2]||(l[2]=e=>p.onBlur(e)),onInput:l[3]||(l[3]=(...e)=>p.onInput&&p.onInput(...e)),onKeydown:l[4]||(l[4]=e=>p.onKeyDown(e)),onPaste:l[5]||(l[5]=e=>p.onPaste(e)),disabled:t.$attrs.disabled||p.maxedOut}),null,16,["disabled"])])],2)],6)},t}(Vue);');
				break;
			case "chip":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.chip=function(e){"use strict";var n={name:"Chip",emits:["remove"],props:{label:{type:String,default:null},icon:{type:String,default:null},image:{type:String,default:null},removable:{type:Boolean,default:!1},removeIcon:{type:String,default:"pi pi-times-circle"}},data:()=>({visible:!0}),methods:{close(e){this.visible=!1,this.$emit("remove",e)}},computed:{containerClass(){return["p-chip p-component",{"p-chip-image":null!=this.image}]},iconClass(){return["p-chip-icon",this.icon]},removeIconClass(){return["p-chip-remove-icon",this.removeIcon]}}};const t={key:2,class:"p-chip-text"};return function(e,n){void 0===n&&(n={});var t=n.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],l=document.createElement("style");l.type="text/css","top"===t&&i.firstChild?i.insertBefore(l,i.firstChild):i.appendChild(l),l.styleSheet?l.styleSheet.cssText=e:l.appendChild(document.createTextNode(e))}}("\n.p-chip {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n}\n.p-chip-text {\n    line-height: 1.5;\n}\n.p-chip-icon.pi {\n    line-height: 1.5;\n}\n.p-chip-remove-icon {\n    line-height: 1.5;\n    cursor: pointer;\n}\n.p-chip img {\n    border-radius: 50%;\n}\n"),n.render=function(n,i,l,o,c,s){return c.visible?(e.openBlock(),e.createBlock("div",{key:0,class:s.containerClass},[e.renderSlot(n.$slots,"default",{},(()=>[l.image?(e.openBlock(),e.createBlock("img",{key:0,src:l.image},null,8,["src"])):l.icon?(e.openBlock(),e.createBlock("span",{key:1,class:s.iconClass},null,2)):e.createCommentVNode("",!0),l.label?(e.openBlock(),e.createBlock("div",t,e.toDisplayString(l.label),1)):e.createCommentVNode("",!0)])),l.removable?(e.openBlock(),e.createBlock("span",{key:0,tabindex:"0",class:s.removeIconClass,onClick:i[1]||(i[1]=(...e)=>s.close&&s.close(...e)),onKeydown:i[2]||(i[2]=e.withKeys(((...e)=>s.close&&s.close(...e)),["enter"]))},null,34)):e.createCommentVNode("",!0)],2)):e.createCommentVNode("",!0)},n}(Vue);');
				break;
			case "inputnumber":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputnumber=function(e,t,n){"use strict";function i(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var s=i(e),r=i(t),u={name:"InputNumber",inheritAttrs:!1,emits:["update:modelValue","input"],props:{modelValue:{type:Number,default:null},format:{type:Boolean,default:!0},showButtons:{type:Boolean,default:!1},buttonLayout:{type:String,default:"stacked"},incrementButtonClass:{type:String,default:null},decrementButtonClass:{type:String,default:null},incrementButtonIcon:{type:String,default:"pi pi-angle-up"},decrementButtonIcon:{type:String,default:"pi pi-angle-down"},locale:{type:String,default:void 0},localeMatcher:{type:String,default:void 0},mode:{type:String,default:"decimal"},prefix:{type:String,default:null},suffix:{type:String,default:null},currency:{type:String,default:void 0},currencyDisplay:{type:String,default:void 0},useGrouping:{type:Boolean,default:!0},minFractionDigits:{type:Number,default:void 0},maxFractionDigits:{type:Number,default:void 0},min:{type:Number,default:null},max:{type:Number,default:null},step:{type:Number,default:1},allowEmpty:{type:Boolean,default:!0},style:null,class:null,inputStyle:null,inputClass:null},numberFormat:null,_numeral:null,_decimal:null,_group:null,_minusSign:null,_currency:null,_suffix:null,_prefix:null,_index:null,groupChar:"",isSpecialChar:null,prefixChar:null,suffixChar:null,timer:null,data:()=>({focused:!1}),watch:{locale(e,t){this.updateConstructParser(e,t)},localeMatcher(e,t){this.updateConstructParser(e,t)},mode(e,t){this.updateConstructParser(e,t)},currency(e,t){this.updateConstructParser(e,t)},currencyDisplay(e,t){this.updateConstructParser(e,t)},useGrouping(e,t){this.updateConstructParser(e,t)},minFractionDigits(e,t){this.updateConstructParser(e,t)},maxFractionDigits(e,t){this.updateConstructParser(e,t)},suffix(e,t){this.updateConstructParser(e,t)},prefix(e,t){this.updateConstructParser(e,t)}},created(){this.constructParser()},methods:{getOptions(){return{localeMatcher:this.localeMatcher,style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay,useGrouping:this.useGrouping,minimumFractionDigits:this.minFractionDigits,maximumFractionDigits:this.maxFractionDigits}},constructParser(){this.numberFormat=new Intl.NumberFormat(this.locale,this.getOptions());const e=[...new Intl.NumberFormat(this.locale,{useGrouping:!1}).format(9876543210)].reverse(),t=new Map(e.map(((e,t)=>[e,t])));this._numeral=new RegExp(`[${e.join("")}]`,"g"),this._group=this.getGroupingExpression(),this._minusSign=this.getMinusSignExpression(),this._currency=this.getCurrencyExpression(),this._decimal=this.getDecimalExpression(),this._suffix=this.getSuffixExpression(),this._prefix=this.getPrefixExpression(),this._index=e=>t.get(e)},updateConstructParser(e,t){e!==t&&this.constructParser()},escapeRegExp:e=>e.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),getDecimalExpression(){const e=new Intl.NumberFormat(this.locale,{...this.getOptions(),useGrouping:!1});return new RegExp(`[${e.format(1.1).replace(this._currency,"").trim().replace(this._numeral,"")}]`,"g")},getGroupingExpression(){const e=new Intl.NumberFormat(this.locale,{useGrouping:!0});return this.groupChar=e.format(1e6).trim().replace(this._numeral,"").charAt(0),new RegExp(`[${this.groupChar}]`,"g")},getMinusSignExpression(){const e=new Intl.NumberFormat(this.locale,{useGrouping:!1});return new RegExp(`[${e.format(-1).trim().replace(this._numeral,"")}]`,"g")},getCurrencyExpression(){if(this.currency){const e=new Intl.NumberFormat(this.locale,{style:"currency",currency:this.currency,currencyDisplay:this.currencyDisplay,minimumFractionDigits:0,maximumFractionDigits:0});return new RegExp(`[${e.format(1).replace(/\s/g,"").replace(this._numeral,"").replace(this._group,"")}]`,"g")}return new RegExp("[]","g")},getPrefixExpression(){if(this.prefix)this.prefixChar=this.prefix;else{const e=new Intl.NumberFormat(this.locale,{style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay});this.prefixChar=e.format(1).split("1")[0]}return new RegExp(`${this.escapeRegExp(this.prefixChar||"")}`,"g")},getSuffixExpression(){if(this.suffix)this.suffixChar=this.suffix;else{const e=new Intl.NumberFormat(this.locale,{style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay,minimumFractionDigits:0,maximumFractionDigits:0});this.suffixChar=e.format(1).split("1")[1]}return new RegExp(`${this.escapeRegExp(this.suffixChar||"")}`,"g")},formatValue(e){if(null!=e){if("-"===e)return e;if(this.format){let t=new Intl.NumberFormat(this.locale,this.getOptions()).format(e);return this.prefix&&(t=this.prefix+t),this.suffix&&(t+=this.suffix),t}return e.toString()}return""},parseValue(e){let t=e.replace(this._suffix,"").replace(this._prefix,"").trim().replace(/\s/g,"").replace(this._currency,"").replace(this._group,"").replace(this._minusSign,"-").replace(this._decimal,".").replace(this._numeral,this._index);if(t){if("-"===t)return t;let e=+t;return isNaN(e)?null:e}return null},repeat(e,t,n){let i=t||500;this.clearTimer(),this.timer=setTimeout((()=>{this.repeat(e,40,n)}),i),this.spin(e,n)},spin(e,t){if(this.$refs.input){let n=this.step*t,i=this.parseValue(this.$refs.input.$el.value)||0,s=this.validateValue(i+n);this.updateInput(s,null,"spin"),this.updateModel(e,s),this.handleOnInput(e,i,s)}},onUpButtonMouseDown(e){this.$attrs.disabled||(this.$refs.input.$el.focus(),this.repeat(e,null,1),e.preventDefault())},onUpButtonMouseUp(){this.$attrs.disabled||this.clearTimer()},onUpButtonMouseLeave(){this.$attrs.disabled||this.clearTimer()},onUpButtonKeyUp(){this.$attrs.disabled||this.clearTimer()},onUpButtonKeyDown(e){32!==e.keyCode&&13!==e.keyCode||this.repeat(e,null,1)},onDownButtonMouseDown(e){this.$attrs.disabled||(this.$refs.input.$el.focus(),this.repeat(e,null,-1),e.preventDefault())},onDownButtonMouseUp(){this.$attrs.disabled||this.clearTimer()},onDownButtonMouseLeave(){this.$attrs.disabled||this.clearTimer()},onDownButtonKeyUp(){this.$attrs.disabled||this.clearTimer()},onDownButtonKeyDown(e){32!==e.keyCode&&13!==e.keyCode||this.repeat(e,null,-1)},onUserInput(){this.isSpecialChar&&(this.$refs.input.$el.value=this.lastValue),this.isSpecialChar=!1},onInputKeyDown(e){if(this.lastValue=e.target.value,e.shiftKey||e.altKey)return void(this.isSpecialChar=!0);let t=e.target.selectionStart,n=e.target.selectionEnd,i=e.target.value,s=null;switch(e.altKey&&e.preventDefault(),e.which){case 38:this.spin(e,1),e.preventDefault();break;case 40:this.spin(e,-1),e.preventDefault();break;case 37:this.isNumeralChar(i.charAt(t-1))||e.preventDefault();break;case 39:this.isNumeralChar(i.charAt(t))||e.preventDefault();break;case 13:s=this.validateValue(this.parseValue(i)),this.$refs.input.$el.value=this.formatValue(s),this.$refs.input.$el.setAttribute("aria-valuenow",s),this.updateModel(e,s);break;case 8:if(e.preventDefault(),t===n){const n=i.charAt(t-1),{decimalCharIndex:r,decimalCharIndexWithoutPrefix:u}=this.getDecimalCharIndexes(i);if(this.isNumeralChar(n)){const e=this.getDecimalLength(i);if(this._group.test(n))this._group.lastIndex=0,s=i.slice(0,t-2)+i.slice(t-1);else if(this._decimal.test(n))this._decimal.lastIndex=0,e?this.$refs.input.$el.setSelectionRange(t-1,t-1):s=i.slice(0,t-1)+i.slice(t);else if(r>0&&t>r){const n=this.isDecimalMode()&&(this.minFractionDigits||0)<e?"":"0";s=i.slice(0,t-1)+n+i.slice(t)}else 1===u?(s=i.slice(0,t-1)+"0"+i.slice(t),s=this.parseValue(s)>0?s:""):s=i.slice(0,t-1)+i.slice(t)}this.updateValue(e,s,null,"delete-single")}else s=this.deleteRange(i,t,n),this.updateValue(e,s,null,"delete-range");break;case 46:if(e.preventDefault(),t===n){const n=i.charAt(t),{decimalCharIndex:r,decimalCharIndexWithoutPrefix:u}=this.getDecimalCharIndexes(i);if(this.isNumeralChar(n)){const e=this.getDecimalLength(i);if(this._group.test(n))this._group.lastIndex=0,s=i.slice(0,t)+i.slice(t+2);else if(this._decimal.test(n))this._decimal.lastIndex=0,e?this.$refs.input.$el.setSelectionRange(t+1,t+1):s=i.slice(0,t)+i.slice(t+1);else if(r>0&&t>r){const n=this.isDecimalMode()&&(this.minFractionDigits||0)<e?"":"0";s=i.slice(0,t)+n+i.slice(t+1)}else 1===u?(s=i.slice(0,t)+"0"+i.slice(t+1),s=this.parseValue(s)>0?s:""):s=i.slice(0,t)+i.slice(t+1)}this.updateValue(e,s,null,"delete-back-single")}else s=this.deleteRange(i,t,n),this.updateValue(e,s,null,"delete-range")}},onInputKeyPress(e){e.preventDefault();let t=e.which||e.keyCode,n=String.fromCharCode(t);const i=this.isDecimalSign(n),s=this.isMinusSign(n);(48<=t&&t<=57||s||i)&&this.insert(e,n,{isDecimalSign:i,isMinusSign:s})},onPaste(e){e.preventDefault();let t=(e.clipboardData||window.clipboardData).getData("Text");if(t){let n=this.parseValue(t);null!=n&&this.insert(e,n.toString())}},allowMinusSign(){return null===this.min||this.min<0},isMinusSign(e){return!(!this._minusSign.test(e)&&"-"!==e)&&(this._minusSign.lastIndex=0,!0)},isDecimalSign(e){return!!this._decimal.test(e)&&(this._decimal.lastIndex=0,!0)},isDecimalMode(){return"decimal"===this.mode},getDecimalCharIndexes(e){let t=e.search(this._decimal);this._decimal.lastIndex=0;const n=e.replace(this._prefix,"").trim().replace(/\s/g,"").replace(this._currency,"").search(this._decimal);return this._decimal.lastIndex=0,{decimalCharIndex:t,decimalCharIndexWithoutPrefix:n}},getCharIndexes(e){const t=e.search(this._decimal);this._decimal.lastIndex=0;const n=e.search(this._minusSign);this._minusSign.lastIndex=0;const i=e.search(this._suffix);this._suffix.lastIndex=0;const s=e.search(this._currency);return this._currency.lastIndex=0,{decimalCharIndex:t,minusCharIndex:n,suffixCharIndex:i,currencyCharIndex:s}},insert(e,t,n={isDecimalSign:!1,isMinusSign:!1}){const i=t.search(this._minusSign);if(this._minusSign.lastIndex=0,!this.allowMinusSign()&&-1!==i)return;const s=this.$refs.input.$el.selectionStart,r=this.$refs.input.$el.selectionEnd;let u=this.$refs.input.$el.value.trim();const{decimalCharIndex:l,minusCharIndex:a,suffixCharIndex:o,currencyCharIndex:p}=this.getCharIndexes(u);let c;if(n.isMinusSign)0===s&&(c=u,-1!==a&&0===r||(c=this.insertText(u,t,0,r)),this.updateValue(e,c,t,"insert"));else if(n.isDecimalSign)l>0&&s===l?this.updateValue(e,u,t,"insert"):(l>s&&l<r||-1===l&&this.maxFractionDigits)&&(c=this.insertText(u,t,s,r),this.updateValue(e,c,t,"insert"));else{const n=this.numberFormat.resolvedOptions().maximumFractionDigits,i=s!==r?"range-insert":"insert";if(l>0&&s>l){if(s+t.length-(l+1)<=n){const n=p>=s?p-1:o>=s?o:u.length;c=u.slice(0,s)+t+u.slice(s+t.length,n)+u.slice(n),this.updateValue(e,c,t,i)}}else c=this.insertText(u,t,s,r),this.updateValue(e,c,t,i)}},insertText(e,t,n,i){if(2===("."===t?t:t.split(".")).length){const s=e.slice(n,i).search(this._decimal);return this._decimal.lastIndex=0,s>0?e.slice(0,n)+this.formatValue(t)+e.slice(i):e||this.formatValue(t)}return i-n===e.length?this.formatValue(t):0===n?t+e.slice(i):i===e.length?e.slice(0,n)+t:e.slice(0,n)+t+e.slice(i)},deleteRange(e,t,n){let i;return i=n-t===e.length?"":0===t?e.slice(n):n===e.length?e.slice(0,t):e.slice(0,t)+e.slice(n),i},initCursor(){let e=this.$refs.input.$el.selectionStart,t=this.$refs.input.$el.value,n=t.length,i=null,s=(this.prefixChar||"").length;t=t.replace(this._prefix,""),e-=s;let r=t.charAt(e);if(this.isNumeralChar(r))return e+s;let u=e-1;for(;u>=0;){if(r=t.charAt(u),this.isNumeralChar(r)){i=u+s;break}u--}if(null!==i)this.$refs.input.$el.setSelectionRange(i+1,i+1);else{for(u=e;u<n;){if(r=t.charAt(u),this.isNumeralChar(r)){i=u+s;break}u++}null!==i&&this.$refs.input.$el.setSelectionRange(i,i)}return i||0},onInputClick(){this.initCursor()},isNumeralChar(e){return!(1!==e.length||!(this._numeral.test(e)||this._decimal.test(e)||this._group.test(e)||this._minusSign.test(e)))&&(this.resetRegex(),!0)},resetRegex(){this._numeral.lastIndex=0,this._decimal.lastIndex=0,this._group.lastIndex=0,this._minusSign.lastIndex=0},updateValue(e,t,n,i){let s=this.$refs.input.$el.value,r=null;null!=t&&(r=this.parseValue(t),r=r||this.allowEmpty?r:0,this.updateInput(r,n,i,t),this.handleOnInput(e,s,r))},handleOnInput(e,t,n){this.isValueChanged(t,n)&&this.$emit("input",{originalEvent:e,value:n})},isValueChanged(e,t){if(null===t&&null!==e)return!0;if(null!=t){return t!==("string"==typeof e?this.parseValue(e):e)}return!1},validateValue(e){return"-"===e||null==e?null:null!=this.min&&e<this.min?this.min:null!=this.max&&e>this.max?this.max:e},updateInput(e,t,n,i){t=t||"";let s=this.$refs.input.$el.value,r=this.formatValue(e),u=s.length;if(r!==i&&(r=this.concatValues(r,i)),0===u){this.$refs.input.$el.value=r,this.$refs.input.$el.setSelectionRange(0,0);const e=this.initCursor()+t.length;this.$refs.input.$el.setSelectionRange(e,e)}else{let e=this.$refs.input.$el.selectionStart,i=this.$refs.input.$el.selectionEnd;this.$refs.input.$el.value=r;let l=r.length;if("range-insert"===n){const n=this.parseValue((s||"").slice(0,e)),u=(null!==n?n.toString():"").split("").join(`(${this.groupChar})?`),l=new RegExp(u,"g");l.test(r);const a=t.split("").join(`(${this.groupChar})?`),o=new RegExp(a,"g");o.test(r.slice(l.lastIndex)),i=l.lastIndex+o.lastIndex,this.$refs.input.$el.setSelectionRange(i,i)}else if(l===u)"insert"===n||"delete-back-single"===n?this.$refs.input.$el.setSelectionRange(i+1,i+1):"delete-single"===n?this.$refs.input.$el.setSelectionRange(i-1,i-1):"delete-range"!==n&&"spin"!==n||this.$refs.input.$el.setSelectionRange(i,i);else if("delete-back-single"===n){let e=s.charAt(i-1),t=s.charAt(i),n=u-l,r=this._group.test(t);r&&1===n?i+=1:!r&&this.isNumeralChar(e)&&(i+=-1*n+1),this._group.lastIndex=0,this.$refs.input.$el.setSelectionRange(i,i)}else if("-"===s&&"insert"===n){this.$refs.input.$el.setSelectionRange(0,0);const e=this.initCursor()+t.length+1;this.$refs.input.$el.setSelectionRange(e,e)}else i+=l-u,this.$refs.input.$el.setSelectionRange(i,i)}this.$refs.input.$el.setAttribute("aria-valuenow",e)},concatValues(e,t){if(e&&t){let n=t.search(this._decimal);return this._decimal.lastIndex=0,-1!==n?e.split(this._decimal)[0]+t.slice(n):e}return e},getDecimalLength(e){if(e){const t=e.split(this._decimal);if(2===t.length)return t[1].replace(this._suffix,"").trim().replace(/\s/g,"").replace(this._currency,"").length}return 0},updateModel(e,t){this.$emit("update:modelValue",t)},onInputFocus(){this.focused=!0},onInputBlur(e){this.focused=!1;let t=e.target,n=this.validateValue(this.parseValue(t.value));t.value=this.formatValue(n),t.setAttribute("aria-valuenow",n),this.updateModel(e,n)},clearTimer(){this.timer&&clearInterval(this.timer)}},computed:{containerClass(){return["p-inputnumber p-component p-inputwrapper",this.class,{"p-inputwrapper-filled":this.filled,"p-inputwrapper-focus":this.focused,"p-inputnumber-buttons-stacked":this.showButtons&&"stacked"===this.buttonLayout,"p-inputnumber-buttons-horizontal":this.showButtons&&"horizontal"===this.buttonLayout,"p-inputnumber-buttons-vertical":this.showButtons&&"vertical"===this.buttonLayout}]},upButtonClass(){return["p-inputnumber-button p-inputnumber-button-up",this.incrementButtonClass]},downButtonClass(){return["p-inputnumber-button p-inputnumber-button-down",this.decrementButtonClass]},filled(){return null!=this.modelValue&&this.modelValue.toString().length>0},upButtonListeners(){return{mousedown:e=>this.onUpButtonMouseDown(e),mouseup:e=>this.onUpButtonMouseUp(e),mouseleave:e=>this.onUpButtonMouseLeave(e),keydown:e=>this.onUpButtonKeyDown(e),keyup:e=>this.onUpButtonKeyUp(e)}},downButtonListeners(){return{mousedown:e=>this.onDownButtonMouseDown(e),mouseup:e=>this.onDownButtonMouseUp(e),mouseleave:e=>this.onDownButtonMouseLeave(e),keydown:e=>this.onDownButtonKeyDown(e),keyup:e=>this.onDownButtonKeyUp(e)}},formattedValue(){const e=this.modelValue||this.allowEmpty?this.modelValue:0;return this.formatValue(e)},getFormatter(){return this.numberFormat}},components:{INInputText:s.default,INButton:r.default}};const l={key:0,class:"p-inputnumber-button-group"};return function(e,t){void 0===t&&(t={});var n=t.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===n&&i.firstChild?i.insertBefore(s,i.firstChild):i.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}("\n.p-inputnumber {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n}\n.p-inputnumber-button {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-flex: 0;\n        -ms-flex: 0 0 auto;\n            flex: 0 0 auto;\n}\n.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button .p-button-label,\n.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button .p-button-label {\n    display: none;\n}\n.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button-up {\n    border-top-left-radius: 0;\n    border-bottom-left-radius: 0;\n    border-bottom-right-radius: 0;\n    padding: 0;\n}\n.p-inputnumber-buttons-stacked .p-inputnumber-input {\n    border-top-right-radius: 0;\n    border-bottom-right-radius: 0;\n}\n.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button-down {\n    border-top-left-radius: 0;\n    border-top-right-radius: 0;\n    border-bottom-left-radius: 0;\n    padding: 0;\n}\n.p-inputnumber-buttons-stacked .p-inputnumber-button-group {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-inputnumber-buttons-stacked .p-inputnumber-button-group .p-button.p-inputnumber-button {\n    -webkit-box-flex: 1;\n        -ms-flex: 1 1 auto;\n            flex: 1 1 auto;\n}\n.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button-up {\n    -webkit-box-ordinal-group: 4;\n        -ms-flex-order: 3;\n            order: 3;\n    border-top-left-radius: 0;\n    border-bottom-left-radius: 0;\n}\n.p-inputnumber-buttons-horizontal .p-inputnumber-input {\n    -webkit-box-ordinal-group: 3;\n        -ms-flex-order: 2;\n            order: 2;\n    border-radius: 0;\n}\n.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button-down {\n    -webkit-box-ordinal-group: 2;\n        -ms-flex-order: 1;\n            order: 1;\n    border-top-right-radius: 0;\n    border-bottom-right-radius: 0;\n}\n.p-inputnumber-buttons-vertical {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-inputnumber-buttons-vertical .p-button.p-inputnumber-button-up {\n    -webkit-box-ordinal-group: 2;\n        -ms-flex-order: 1;\n            order: 1;\n    border-bottom-left-radius: 0;\n    border-bottom-right-radius: 0;\n    width: 100%;\n}\n.p-inputnumber-buttons-vertical .p-inputnumber-input {\n    -webkit-box-ordinal-group: 3;\n        -ms-flex-order: 2;\n            order: 2;\n    border-radius: 0;\n    text-align: center;\n}\n.p-inputnumber-buttons-vertical .p-button.p-inputnumber-button-down {\n    -webkit-box-ordinal-group: 4;\n        -ms-flex-order: 3;\n            order: 3;\n    border-top-left-radius: 0;\n    border-top-right-radius: 0;\n    width: 100%;\n}\n.p-inputnumber-input {\n    -webkit-box-flex: 1;\n        -ms-flex: 1 1 auto;\n            flex: 1 1 auto;\n}\n.p-fluid .p-inputnumber {\n    width: 100%;\n}\n.p-fluid .p-inputnumber .p-inputnumber-input {\n    width: 1%;\n}\n.p-fluid .p-inputnumber-buttons-vertical .p-inputnumber-input {\n    width: 100%;\n}\n"),u.render=function(e,t,i,s,r,u){const a=n.resolveComponent("INInputText"),o=n.resolveComponent("INButton");return n.openBlock(),n.createBlock("span",{class:u.containerClass,style:i.style},[n.createVNode(a,n.mergeProps({ref:"input",class:["p-inputnumber-input",i.inputClass],style:i.inputStyle,value:u.formattedValue},e.$attrs,{"aria-valumin":i.min,"aria-valuemax":i.max,onInput:u.onUserInput,onKeydown:u.onInputKeyDown,onKeypress:u.onInputKeyPress,onPaste:u.onPaste,onClick:u.onInputClick,onFocus:u.onInputFocus,onBlur:u.onInputBlur}),null,16,["class","style","value","aria-valumin","aria-valuemax","onInput","onKeydown","onKeypress","onPaste","onClick","onFocus","onBlur"]),i.showButtons&&"stacked"===i.buttonLayout?(n.openBlock(),n.createBlock("span",l,[n.createVNode(o,n.mergeProps({class:u.upButtonClass,icon:i.incrementButtonIcon},n.toHandlers(u.upButtonListeners),{disabled:e.$attrs.disabled}),null,16,["class","icon","disabled"]),n.createVNode(o,n.mergeProps({class:u.downButtonClass,icon:i.decrementButtonIcon},n.toHandlers(u.downButtonListeners),{disabled:e.$attrs.disabled}),null,16,["class","icon","disabled"])])):n.createCommentVNode("",!0),i.showButtons&&"stacked"!==i.buttonLayout?(n.openBlock(),n.createBlock(o,n.mergeProps({key:1,class:u.upButtonClass,icon:i.incrementButtonIcon},n.toHandlers(u.upButtonListeners),{disabled:e.$attrs.disabled}),null,16,["class","icon","disabled"])):n.createCommentVNode("",!0),i.showButtons&&"stacked"!==i.buttonLayout?(n.openBlock(),n.createBlock(o,n.mergeProps({key:2,class:u.downButtonClass,icon:i.decrementButtonIcon},n.toHandlers(u.downButtonListeners),{disabled:e.$attrs.disabled}),null,16,["class","icon","disabled"])):n.createCommentVNode("",!0)],6)},u}(primevue.inputtext,primevue.button,Vue);');
				break;
			case "inputtext":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputtext=function(e){"use strict";var t={name:"InputText",emits:["update:modelValue"],props:{modelValue:null},methods:{onInput(e){this.$emit("update:modelValue",e.target.value)}},computed:{filled(){return null!=this.modelValue&&this.modelValue.toString().length>0}}};return t.render=function(t,u,l,n,i,o){return e.openBlock(),e.createBlock("input",{class:["p-inputtext p-component",{"p-filled":o.filled}],value:l.modelValue,onInput:u[1]||(u[1]=(...e)=>o.onInput&&o.onInput(...e))},null,42,["value"])},t}(Vue);');
				break;
			case "timeline":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.timeline=function(e,n){"use strict";var t={name:"Timeline",props:{value:null,align:{mode:String,default:"left"},layout:{mode:String,default:"vertical"},dataKey:null},methods:{getKey(n,t){return this.dataKey?e.ObjectUtils.resolveFieldData(n,this.dataKey):t}},computed:{containerClass(){return["p-timeline p-component","p-timeline-"+this.align,"p-timeline-"+this.layout]}}};const i={class:"p-timeline-event-opposite"},l={class:"p-timeline-event-separator"},o=n.createVNode("div",{class:"p-timeline-event-marker"},null,-1),r=n.createVNode("div",{class:"p-timeline-event-connector"},null,-1),m={class:"p-timeline-event-content"};return function(e,n){void 0===n&&(n={});var t=n.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],l=document.createElement("style");l.type="text/css","top"===t&&i.firstChild?i.insertBefore(l,i.firstChild):i.appendChild(l),l.styleSheet?l.styleSheet.cssText=e:l.appendChild(document.createTextNode(e))}}("\n.p-timeline {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-flex: 1;\n        -ms-flex-positive: 1;\n            flex-grow: 1;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-timeline-left .p-timeline-event-opposite {\n    text-align: right;\n}\n.p-timeline-left .p-timeline-event-content {\n    text-align: left;\n}\n.p-timeline-right .p-timeline-event {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: row-reverse;\n            flex-direction: row-reverse;\n}\n.p-timeline-right .p-timeline-event-opposite {\n    text-align: left;\n}\n.p-timeline-right .p-timeline-event-content {\n    text-align: right;\n}\n.p-timeline-vertical.p-timeline-alternate .p-timeline-event:nth-child(even) {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: row-reverse;\n            flex-direction: row-reverse;\n}\n.p-timeline-vertical.p-timeline-alternate .p-timeline-event:nth-child(odd) .p-timeline-event-opposite {\n    text-align: right;\n}\n.p-timeline-vertical.p-timeline-alternate .p-timeline-event:nth-child(odd) .p-timeline-event-content {\n    text-align: left;\n}\n.p-timeline-vertical.p-timeline-alternate .p-timeline-event:nth-child(even) .p-timeline-event-opposite {\n    text-align: left;\n}\n.p-timeline-vertical.p-timeline-alternate .p-timeline-event:nth-child(even) .p-timeline-event-content {\n    text-align: right;\n}\n.p-timeline-event {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    position: relative;\n    min-height: 70px;\n}\n.p-timeline-event:last-child {\n    min-height: 0;\n}\n.p-timeline-event-opposite {\n    -webkit-box-flex: 1;\n        -ms-flex: 1;\n            flex: 1;\n    padding: 0 1rem;\n}\n.p-timeline-event-content {\n    -webkit-box-flex: 1;\n        -ms-flex: 1;\n            flex: 1;\n    padding: 0 1rem;\n}\n.p-timeline-event-separator {\n    -webkit-box-flex: 0;\n        -ms-flex: 0;\n            flex: 0;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-timeline-event-marker {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -ms-flex-item-align: baseline;\n        align-self: baseline;\n}\n.p-timeline-event-connector {\n    -webkit-box-flex: 1;\n        -ms-flex-positive: 1;\n            flex-grow: 1;\n}\n.p-timeline-horizontal {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: row;\n            flex-direction: row;\n}\n.p-timeline-horizontal .p-timeline-event {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n    -webkit-box-flex: 1;\n        -ms-flex: 1;\n            flex: 1;\n}\n.p-timeline-horizontal .p-timeline-event:last-child {\n    -webkit-box-flex: 0;\n        -ms-flex: 0;\n            flex: 0;\n}\n.p-timeline-horizontal .p-timeline-event-separator {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: row;\n            flex-direction: row;\n}\n.p-timeline-horizontal .p-timeline-event-connector  {\n    width: 100%;\n}\n.p-timeline-bottom .p-timeline-event {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: column-reverse;\n            flex-direction: column-reverse;\n}\n.p-timeline-horizontal.p-timeline-alternate .p-timeline-event:nth-child(even) {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: column-reverse;\n            flex-direction: column-reverse;\n}\n"),t.render=function(e,t,a,s,c,p){return n.openBlock(),n.createBlock("div",{class:p.containerClass},[(n.openBlock(!0),n.createBlock(n.Fragment,null,n.renderList(a.value,((t,s)=>(n.openBlock(),n.createBlock("div",{key:p.getKey(t,s),class:"p-timeline-event"},[n.createVNode("div",i,[n.renderSlot(e.$slots,"opposite",{item:t,index:s})]),n.createVNode("div",l,[n.renderSlot(e.$slots,"marker",{item:t,index:s},(()=>[o])),s!==a.value.length-1?n.renderSlot(e.$slots,"connector",{key:0},(()=>[r])):n.createCommentVNode("",!0)]),n.createVNode("div",m,[n.renderSlot(e.$slots,"content",{item:t,index:s})])])))),128))],2)},t}(primevue.utils,Vue);');
				break;
			case "avatar":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.avatar=function(e){"use strict";var a={name:"Avatar",props:{label:{type:String,default:null},icon:{type:String,default:null},image:{type:String,default:null},size:{type:String,default:"normal"},shape:{type:String,default:"square"}},computed:{containerClass(){return["p-avatar p-component",{"p-avatar-image":null!=this.image,"p-avatar-circle":"circle"===this.shape,"p-avatar-lg":"large"===this.size,"p-avatar-xl":"xlarge"===this.size}]},iconClass(){return["p-avatar-icon",this.icon]}}};const t={key:0,class:"p-avatar-text"};return function(e,a){void 0===a&&(a={});var t=a.insertAt;if(e&&"undefined"!=typeof document){var n=document.head||document.getElementsByTagName("head")[0],r=document.createElement("style");r.type="text/css","top"===t&&n.firstChild?n.insertBefore(r,n.firstChild):n.appendChild(r),r.styleSheet?r.styleSheet.cssText=e:r.appendChild(document.createTextNode(e))}}("\n.p-avatar {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    width: 2rem;\n    height: 2rem;\n    font-size: 1rem;\n}\n.p-avatar.p-avatar-image {\n    background-color: transparent;\n}\n.p-avatar.p-avatar-circle {\n    border-radius: 50%;\n}\n.p-avatar-circle img {\n    border-radius: 50%;\n}\n.p-avatar .p-avatar-icon {\n    font-size: 1rem;\n}\n.p-avatar img {\n    width: 100%;\n    height: 100%;\n}\n"),a.render=function(a,n,r,i,l,s){return e.openBlock(),e.createBlock("div",{class:s.containerClass},[e.renderSlot(a.$slots,"default",{},(()=>[r.label?(e.openBlock(),e.createBlock("span",t,e.toDisplayString(r.label),1)):r.icon?(e.openBlock(),e.createBlock("span",{key:1,class:s.iconClass},null,2)):r.image?(e.openBlock(),e.createBlock("img",{key:2,src:r.image},null,8,["src"])):e.createCommentVNode("",!0)]))],2)},a}(Vue);');
				break;
			case "dropdown":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.dropdown=function(e,t,i,l,n,o){"use strict";function r(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var s=r(t),a=r(l),p=r(n),d={name:"Dropdown",emits:["update:modelValue","before-show","before-hide","show","hide","change","filter","focus","blur"],props:{modelValue:null,options:Array,optionLabel:null,optionValue:null,optionDisabled:null,optionGroupLabel:null,optionGroupChildren:null,scrollHeight:{type:String,default:"200px"},filter:Boolean,filterPlaceholder:String,filterLocale:String,filterMatchMode:{type:String,default:"contains"},filterFields:{type:Array,default:null},editable:Boolean,placeholder:String,disabled:Boolean,dataKey:null,showClear:Boolean,inputId:String,tabindex:String,ariaLabelledBy:null,appendTo:{type:String,default:"body"},emptyFilterMessage:{type:String,default:null},emptyMessage:{type:String,default:null},panelClass:null,loading:{type:Boolean,default:!1},loadingIcon:{type:String,default:"pi pi-spinner pi-spin"},virtualScrollerOptions:{type:Object,default:null}},data:()=>({focused:!1,filterValue:null,overlayVisible:!1}),watch:{modelValue(){this.isModelValueChanged=!0}},outsideClickListener:null,scrollHandler:null,resizeListener:null,searchTimeout:null,currentSearchChar:null,previousSearchChar:null,searchValue:null,overlay:null,itemsWrapper:null,virtualScroller:null,isModelValueChanged:!1,updated(){this.overlayVisible&&this.isModelValueChanged&&this.scrollValueInView(),this.isModelValueChanged=!1},beforeUnmount(){this.unbindOutsideClickListener(),this.unbindResizeListener(),this.scrollHandler&&(this.scrollHandler.destroy(),this.scrollHandler=null),this.itemsWrapper=null,this.overlay&&(e.ZIndexUtils.clear(this.overlay),this.overlay=null)},methods:{getOptionIndex(e,t){return this.virtualScrollerDisabled?e:t&&t(e).index},getOptionLabel(t){return this.optionLabel?e.ObjectUtils.resolveFieldData(t,this.optionLabel):t},getOptionValue(t){return this.optionValue?e.ObjectUtils.resolveFieldData(t,this.optionValue):t},getOptionRenderKey(t){return this.dataKey?e.ObjectUtils.resolveFieldData(t,this.dataKey):this.getOptionLabel(t)},isOptionDisabled(t){return!!this.optionDisabled&&e.ObjectUtils.resolveFieldData(t,this.optionDisabled)},getOptionGroupRenderKey(t){return e.ObjectUtils.resolveFieldData(t,this.optionGroupLabel)},getOptionGroupLabel(t){return e.ObjectUtils.resolveFieldData(t,this.optionGroupLabel)},getOptionGroupChildren(t){return e.ObjectUtils.resolveFieldData(t,this.optionGroupChildren)},getSelectedOption(){let e=this.getSelectedOptionIndex();return-1!==e?this.optionGroupLabel?this.getOptionGroupChildren(this.visibleOptions[e.group])[e.option]:this.visibleOptions[e]:null},getSelectedOptionIndex(){if(null!=this.modelValue&&this.visibleOptions){if(!this.optionGroupLabel)return this.findOptionIndexInList(this.modelValue,this.visibleOptions);for(let e=0;e<this.visibleOptions.length;e++){let t=this.findOptionIndexInList(this.modelValue,this.getOptionGroupChildren(this.visibleOptions[e]));if(-1!==t)return{group:e,option:t}}}return-1},findOptionIndexInList(t,i){for(let l=0;l<i.length;l++)if(e.ObjectUtils.equals(t,this.getOptionValue(i[l]),this.equalityKey))return l;return-1},isSelected(t){return e.ObjectUtils.equals(this.modelValue,this.getOptionValue(t),this.equalityKey)},show(){this.$emit("before-show"),this.overlayVisible=!0},hide(){this.$emit("before-hide"),this.overlayVisible=!1},onFocus(e){this.focused=!0,this.$emit("focus",e)},onBlur(e){this.focused=!1,this.$emit("blur",e)},onKeyDown(e){switch(e.which){case 40:this.onDownKey(e);break;case 38:this.onUpKey(e);break;case 32:this.overlayVisible||(this.show(),e.preventDefault());break;case 13:case 27:this.overlayVisible&&(this.hide(),e.preventDefault());break;case 9:this.hide();break;default:this.search(e)}},onFilterKeyDown(e){switch(e.which){case 40:this.onDownKey(e);break;case 38:this.onUpKey(e);break;case 13:case 27:this.overlayVisible=!1,e.preventDefault()}},onDownKey(e){if(this.visibleOptions)if(!this.overlayVisible&&e.altKey)this.show();else{let t=this.visibleOptions&&this.visibleOptions.length>0?this.findNextOption(this.getSelectedOptionIndex()):null;t&&this.updateModel(e,this.getOptionValue(t))}e.preventDefault()},onUpKey(e){if(this.visibleOptions){let t=this.findPrevOption(this.getSelectedOptionIndex());t&&this.updateModel(e,this.getOptionValue(t))}e.preventDefault()},findNextOption(e){if(this.optionGroupLabel){let t=-1===e?0:e.group,i=-1===e?-1:e.option,l=this.findNextOptionInList(this.getOptionGroupChildren(this.visibleOptions[t]),i);return l||(t+1!==this.visibleOptions.length?this.findNextOption({group:t+1,option:-1}):null)}return this.findNextOptionInList(this.visibleOptions,e)},findNextOptionInList(e,t){let i=t+1;if(i===e.length)return null;let l=e[i];return this.isOptionDisabled(l)?this.findNextOptionInList(i):l},findPrevOption(e){if(-1===e)return null;if(this.optionGroupLabel){let t=e.group,i=e.option,l=this.findPrevOptionInList(this.getOptionGroupChildren(this.visibleOptions[t]),i);return l||(t>0?this.findPrevOption({group:t-1,option:this.getOptionGroupChildren(this.visibleOptions[t-1]).length}):null)}return this.findPrevOptionInList(this.visibleOptions,e)},findPrevOptionInList(e,t){let i=t-1;if(i<0)return null;let l=e[i];return this.isOptionDisabled(l)?this.findPrevOption(i):l},onClearClick(e){this.updateModel(e,null)},onClick(t){this.disabled||this.loading||e.DomHandler.hasClass(t.target,"p-dropdown-clear-icon")||"INPUT"===t.target.tagName||this.overlay&&this.overlay.contains(t.target)||(this.overlayVisible?this.hide():this.show(),this.$refs.focusInput.focus())},onOptionSelect(e,t){let i=this.getOptionValue(t);this.updateModel(e,i),this.$refs.focusInput.focus(),setTimeout((()=>{this.hide()}),200)},onEditableInput(e){this.$emit("update:modelValue",e.target.value)},onOverlayEnter(t){if(e.ZIndexUtils.set("overlay",t,this.$primevue.config.zIndex.overlay),this.alignOverlay(),this.bindOutsideClickListener(),this.bindScrollListener(),this.bindResizeListener(),this.filter&&this.$refs.filterInput.focus(),!this.virtualScrollerDisabled){const e=this.getSelectedOptionIndex();-1!==e&&this.virtualScroller.scrollToIndex(e)}this.$emit("show")},onOverlayLeave(){this.unbindOutsideClickListener(),this.unbindScrollListener(),this.unbindResizeListener(),this.$emit("hide"),this.itemsWrapper=null,this.overlay=null},onOverlayAfterLeave(t){e.ZIndexUtils.clear(t)},alignOverlay(){this.appendDisabled?e.DomHandler.relativePosition(this.overlay,this.$el):(this.overlay.style.minWidth=e.DomHandler.getOuterWidth(this.$el)+"px",e.DomHandler.absolutePosition(this.overlay,this.$el))},updateModel(e,t){this.$emit("update:modelValue",t),this.$emit("change",{originalEvent:e,value:t})},bindOutsideClickListener(){this.outsideClickListener||(this.outsideClickListener=e=>{this.overlayVisible&&this.overlay&&!this.$el.contains(e.target)&&!this.overlay.contains(e.target)&&this.hide()},document.addEventListener("click",this.outsideClickListener))},unbindOutsideClickListener(){this.outsideClickListener&&(document.removeEventListener("click",this.outsideClickListener),this.outsideClickListener=null)},bindScrollListener(){this.scrollHandler||(this.scrollHandler=new e.ConnectedOverlayScrollHandler(this.$refs.container,(()=>{this.overlayVisible&&this.hide()}))),this.scrollHandler.bindScrollListener()},unbindScrollListener(){this.scrollHandler&&this.scrollHandler.unbindScrollListener()},bindResizeListener(){this.resizeListener||(this.resizeListener=()=>{this.overlayVisible&&!e.DomHandler.isTouchDevice()&&this.hide()},window.addEventListener("resize",this.resizeListener))},unbindResizeListener(){this.resizeListener&&(window.removeEventListener("resize",this.resizeListener),this.resizeListener=null)},search(e){if(!this.visibleOptions)return;this.searchTimeout&&clearTimeout(this.searchTimeout);const t=e.key;if(this.previousSearchChar=this.currentSearchChar,this.currentSearchChar=t,this.previousSearchChar===this.currentSearchChar?this.searchValue=this.currentSearchChar:this.searchValue=this.searchValue?this.searchValue+t:t,this.searchValue){let t=this.getSelectedOptionIndex(),i=this.optionGroupLabel?this.searchOptionInGroup(t):this.searchOption(++t);i&&this.updateModel(e,this.getOptionValue(i))}this.searchTimeout=setTimeout((()=>{this.searchValue=null}),250)},searchOption(e){let t;return this.searchValue&&(t=this.searchOptionInRange(e,this.visibleOptions.length),t||(t=this.searchOptionInRange(0,e))),t},searchOptionInRange(e,t){for(let i=e;i<t;i++){let e=this.visibleOptions[i];if(this.matchesSearchValue(e))return e}return null},searchOptionInGroup(e){let t=-1===e?{group:0,option:-1}:e;for(let e=t.group;e<this.visibleOptions.length;e++){let i=this.getOptionGroupChildren(this.visibleOptions[e]);for(let l=t.group===e?t.option+1:0;l<i.length;l++)if(this.matchesSearchValue(i[l]))return i[l]}for(let e=0;e<=t.group;e++){let i=this.getOptionGroupChildren(this.visibleOptions[e]);for(let l=0;l<(t.group===e?t.option:i.length);l++)if(this.matchesSearchValue(i[l]))return i[l]}return null},matchesSearchValue(e){return this.getOptionLabel(e).toLocaleLowerCase(this.filterLocale).startsWith(this.searchValue.toLocaleLowerCase(this.filterLocale))},onFilterChange(e){this.$emit("filter",{originalEvent:e,value:e.target.value})},onFilterUpdated(){this.overlayVisible&&this.alignOverlay()},overlayRef(e){this.overlay=e},itemsWrapperRef(e){this.itemsWrapper=e},virtualScrollerRef(e){this.virtualScroller=e},scrollValueInView(){if(this.overlay){let t=e.DomHandler.findSingle(this.overlay,"li.p-highlight");t&&t.scrollIntoView({block:"nearest",inline:"start"})}},onOverlayClick(e){s.default.emit("overlay-click",{originalEvent:e,target:this.$el})}},computed:{visibleOptions(){if(this.filterValue){if(this.optionGroupLabel){let e=[];for(let t of this.options){let l=i.FilterService.filter(this.getOptionGroupChildren(t),this.searchFields,this.filterValue,this.filterMatchMode,this.filterLocale);if(l&&l.length){let i={...t};i[this.optionGroupChildren]=l,e.push(i)}}return e}return i.FilterService.filter(this.options,this.searchFields,this.filterValue,this.filterMatchMode,this.filterLocale)}return this.options},containerClass(){return["p-dropdown p-component p-inputwrapper",{"p-disabled":this.disabled,"p-dropdown-clearable":this.showClear&&!this.disabled,"p-focus":this.focused,"p-inputwrapper-filled":this.modelValue,"p-inputwrapper-focus":this.focused||this.overlayVisible}]},labelClass(){return["p-dropdown-label p-inputtext",{"p-placeholder":this.label===this.placeholder,"p-dropdown-label-empty":!this.$slots.value&&("p-emptylabel"===this.label||0===this.label.length)}]},panelStyleClass(){return["p-dropdown-panel p-component",this.panelClass,{"p-input-filled":"filled"===this.$primevue.config.inputStyle,"p-ripple-disabled":!1===this.$primevue.config.ripple}]},label(){let e=this.getSelectedOption();return e?this.getOptionLabel(e):this.placeholder||"p-emptylabel"},editableInputValue(){let e=this.getSelectedOption();return e?this.getOptionLabel(e):this.modelValue},equalityKey(){return this.optionValue?null:this.dataKey},searchFields(){return this.filterFields||[this.optionLabel]},emptyFilterMessageText(){return this.emptyFilterMessage||this.$primevue.config.locale.emptyFilterMessage},emptyMessageText(){return this.emptyMessage||this.$primevue.config.locale.emptyMessage},appendDisabled(){return"self"===this.appendTo},virtualScrollerDisabled(){return!this.virtualScrollerOptions},appendTarget(){return this.appendDisabled?null:this.appendTo},dropdownIconClass(){return["p-dropdown-trigger-icon",this.loading?this.loadingIcon:"pi pi-chevron-down"]}},directives:{ripple:a.default},components:{VirtualScroller:p.default}};const h={class:"p-hidden-accessible"},u={key:0,class:"p-dropdown-header"},c={class:"p-dropdown-filter-container"},b=o.createVNode("span",{class:"p-dropdown-filter-icon pi pi-search"},null,-1),f={class:"p-dropdown-item-group"},v={key:2,class:"p-dropdown-empty-message"},g={key:3,class:"p-dropdown-empty-message"};return function(e,t){void 0===t&&(t={});var i=t.insertAt;if(e&&"undefined"!=typeof document){var l=document.head||document.getElementsByTagName("head")[0],n=document.createElement("style");n.type="text/css","top"===i&&l.firstChild?l.insertBefore(n,l.firstChild):l.appendChild(n),n.styleSheet?n.styleSheet.cssText=e:n.appendChild(document.createTextNode(e))}}("\n.p-dropdown {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    cursor: pointer;\n    position: relative;\n    -webkit-user-select: none;\n       -moz-user-select: none;\n        -ms-user-select: none;\n            user-select: none;\n}\n.p-dropdown-clear-icon {\n    position: absolute;\n    top: 50%;\n    margin-top: -.5rem;\n}\n.p-dropdown-trigger {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -ms-flex-negative: 0;\n        flex-shrink: 0;\n}\n.p-dropdown-label {\n    display: block;\n    white-space: nowrap;\n    overflow: hidden;\n    -webkit-box-flex: 1;\n        -ms-flex: 1 1 auto;\n            flex: 1 1 auto;\n    width: 1%;\n    text-overflow: ellipsis;\n    cursor: pointer;\n}\n.p-dropdown-label-empty {\n    overflow: hidden;\n    visibility: hidden;\n}\ninput.p-dropdown-label  {\n    cursor: default;\n}\n.p-dropdown .p-dropdown-panel {\n    min-width: 100%;\n}\n.p-dropdown-panel {\n    position: absolute;\n    top: 0;\n    left: 0;\n}\n.p-dropdown-items-wrapper {\n    overflow: auto;\n}\n.p-dropdown-item {\n    cursor: pointer;\n    font-weight: normal;\n    white-space: nowrap;\n    position: relative;\n    overflow: hidden;\n}\n.p-dropdown-item-group {\n    cursor: auto;\n}\n.p-dropdown-items {\n    margin: 0;\n    padding: 0;\n    list-style-type: none;\n}\n.p-dropdown-filter {\n    width: 100%;\n}\n.p-dropdown-filter-container {\n    position: relative;\n}\n.p-dropdown-filter-icon {\n    position: absolute;\n    top: 50%;\n    margin-top: -.5rem;\n}\n.p-fluid .p-dropdown {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n}\n.p-fluid .p-dropdown .p-dropdown-label {\n    width: 1%;\n}\n"),d.render=function(e,t,i,l,n,r){const s=o.resolveComponent("VirtualScroller"),a=o.resolveDirective("ripple");return o.openBlock(),o.createBlock("div",{ref:"container",class:r.containerClass,onClick:t[13]||(t[13]=e=>r.onClick(e))},[o.createVNode("div",h,[o.createVNode("input",{ref:"focusInput",type:"text",id:i.inputId,readonly:"",disabled:i.disabled,onFocus:t[1]||(t[1]=(...e)=>r.onFocus&&r.onFocus(...e)),onBlur:t[2]||(t[2]=(...e)=>r.onBlur&&r.onBlur(...e)),onKeydown:t[3]||(t[3]=(...e)=>r.onKeyDown&&r.onKeyDown(...e)),tabindex:i.tabindex,"aria-haspopup":"true","aria-expanded":n.overlayVisible,"aria-labelledby":i.ariaLabelledBy},null,40,["id","disabled","tabindex","aria-expanded","aria-labelledby"])]),i.editable?(o.openBlock(),o.createBlock("input",{key:0,type:"text",class:"p-dropdown-label p-inputtext",disabled:i.disabled,onFocus:t[4]||(t[4]=(...e)=>r.onFocus&&r.onFocus(...e)),onBlur:t[5]||(t[5]=(...e)=>r.onBlur&&r.onBlur(...e)),placeholder:i.placeholder,value:r.editableInputValue,onInput:t[6]||(t[6]=(...e)=>r.onEditableInput&&r.onEditableInput(...e)),"aria-haspopup":"listbox","aria-expanded":n.overlayVisible},null,40,["disabled","placeholder","value","aria-expanded"])):o.createCommentVNode("",!0),i.editable?o.createCommentVNode("",!0):(o.openBlock(),o.createBlock("span",{key:1,class:r.labelClass},[o.renderSlot(e.$slots,"value",{value:i.modelValue,placeholder:i.placeholder},(()=>[o.createTextVNode(o.toDisplayString(r.label||"empty"),1)]))],2)),i.showClear&&null!=i.modelValue?(o.openBlock(),o.createBlock("i",{key:2,class:"p-dropdown-clear-icon pi pi-times",onClick:t[7]||(t[7]=e=>r.onClearClick(e))})):o.createCommentVNode("",!0),o.createVNode("div",{class:"p-dropdown-trigger",role:"button","aria-haspopup":"listbox","aria-expanded":n.overlayVisible},[o.renderSlot(e.$slots,"indicator",{},(()=>[o.createVNode("span",{class:r.dropdownIconClass},null,2)]))],8,["aria-expanded"]),(o.openBlock(),o.createBlock(o.Teleport,{to:r.appendTarget,disabled:r.appendDisabled},[o.createVNode(o.Transition,{name:"p-connected-overlay",onEnter:r.onOverlayEnter,onLeave:r.onOverlayLeave,onAfterLeave:r.onOverlayAfterLeave},{default:o.withCtx((()=>[n.overlayVisible?(o.openBlock(),o.createBlock("div",{key:0,ref:r.overlayRef,class:r.panelStyleClass,onClick:t[12]||(t[12]=(...e)=>r.onOverlayClick&&r.onOverlayClick(...e))},[o.renderSlot(e.$slots,"header",{value:i.modelValue,options:r.visibleOptions}),i.filter?(o.openBlock(),o.createBlock("div",u,[o.createVNode("div",c,[o.withDirectives(o.createVNode("input",{type:"text",ref:"filterInput","onUpdate:modelValue":t[8]||(t[8]=e=>n.filterValue=e),onVnodeUpdated:t[9]||(t[9]=(...e)=>r.onFilterUpdated&&r.onFilterUpdated(...e)),autoComplete:"off",class:"p-dropdown-filter p-inputtext p-component",placeholder:i.filterPlaceholder,onKeydown:t[10]||(t[10]=(...e)=>r.onFilterKeyDown&&r.onFilterKeyDown(...e)),onInput:t[11]||(t[11]=(...e)=>r.onFilterChange&&r.onFilterChange(...e))},null,40,["placeholder"]),[[o.vModelText,n.filterValue]]),b])])):o.createCommentVNode("",!0),o.createVNode("div",{ref:r.itemsWrapperRef,class:"p-dropdown-items-wrapper",style:{"max-height":r.virtualScrollerDisabled?i.scrollHeight:""}},[o.createVNode(s,o.mergeProps({ref:r.virtualScrollerRef},i.virtualScrollerOptions,{items:r.visibleOptions,style:{height:i.scrollHeight},disabled:r.virtualScrollerDisabled}),o.createSlots({content:o.withCtx((({styleClass:t,contentRef:l,items:s,getItemOptions:p})=>[o.createVNode("ul",{ref:l,class:["p-dropdown-items",t],role:"listbox"},[i.optionGroupLabel?(o.openBlock(!0),o.createBlock(o.Fragment,{key:1},o.renderList(s,((t,i)=>(o.openBlock(),o.createBlock(o.Fragment,{key:r.getOptionGroupRenderKey(t)},[o.createVNode("li",f,[o.renderSlot(e.$slots,"optiongroup",{option:t,index:r.getOptionIndex(i,p)},(()=>[o.createTextVNode(o.toDisplayString(r.getOptionGroupLabel(t)),1)]))]),(o.openBlock(!0),o.createBlock(o.Fragment,null,o.renderList(r.getOptionGroupChildren(t),((t,i)=>o.withDirectives((o.openBlock(),o.createBlock("li",{class:["p-dropdown-item",{"p-highlight":r.isSelected(t),"p-disabled":r.isOptionDisabled(t)}],key:r.getOptionRenderKey(t),onClick:e=>r.onOptionSelect(e,t),role:"option","aria-label":r.getOptionLabel(t),"aria-selected":r.isSelected(t)},[o.renderSlot(e.$slots,"option",{option:t,index:r.getOptionIndex(i,p)},(()=>[o.createTextVNode(o.toDisplayString(r.getOptionLabel(t)),1)]))],10,["onClick","aria-label","aria-selected"])),[[a]]))),128))],64)))),128)):(o.openBlock(!0),o.createBlock(o.Fragment,{key:0},o.renderList(s,((t,i)=>o.withDirectives((o.openBlock(),o.createBlock("li",{class:["p-dropdown-item",{"p-highlight":r.isSelected(t),"p-disabled":r.isOptionDisabled(t)}],key:r.getOptionRenderKey(t),onClick:e=>r.onOptionSelect(e,t),role:"option","aria-label":r.getOptionLabel(t),"aria-selected":r.isSelected(t)},[o.renderSlot(e.$slots,"option",{option:t,index:r.getOptionIndex(i,p)},(()=>[o.createTextVNode(o.toDisplayString(r.getOptionLabel(t)),1)]))],10,["onClick","aria-label","aria-selected"])),[[a]]))),128)),n.filterValue&&(!s||s&&0===s.length)?(o.openBlock(),o.createBlock("li",v,[o.renderSlot(e.$slots,"emptyfilter",{},(()=>[o.createTextVNode(o.toDisplayString(r.emptyFilterMessageText),1)]))])):!i.options||i.options&&0===i.options.length?(o.openBlock(),o.createBlock("li",g,[o.renderSlot(e.$slots,"empty",{},(()=>[o.createTextVNode(o.toDisplayString(r.emptyMessageText),1)]))])):o.createCommentVNode("",!0)],2)])),_:2},[e.$slots.loader?{name:"loader",fn:o.withCtx((({options:t})=>[o.renderSlot(e.$slots,"loader",{options:t})]))}:void 0]),1040,["items","style","disabled"])],4),o.renderSlot(e.$slots,"footer",{value:i.modelValue,options:r.visibleOptions})],2)):o.createCommentVNode("",!0)])),_:3},8,["onEnter","onLeave","onAfterLeave"])],8,["to","disabled"]))],2)},d}(primevue.utils,primevue.overlayeventbus,primevue.api,primevue.ripple,primevue.virtualscroller,Vue);');
				break;
			case "speeddail":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.speeddial=function(e,i,t,n){"use strict";function s(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var o=s(e),l=s(i),r={name:"SpeedDial",props:{model:null,visible:{type:Boolean,default:!1},direction:{type:String,default:"up"},transitionDelay:{type:Number,default:30},type:{type:String,default:"linear"},radius:{type:Number,default:0},mask:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1},hideOnClickOutside:{type:Boolean,default:!0},buttonClass:null,maskStyle:null,maskClass:null,showIcon:{type:String,default:"pi pi-plus"},hideIcon:null,rotateAnimation:{type:Boolean,default:!0},tooltipOptions:null,style:null,class:null},documentClickListener:null,container:null,list:null,data(){return{d_visible:this.visible,isItemClicked:!1}},watch:{visible(e){this.d_visible=e}},mounted(){if("linear"!==this.type){const e=t.DomHandler.findSingle(this.container,".p-speeddial-button"),i=t.DomHandler.findSingle(this.list,".p-speeddial-item");if(e&&i){const t=Math.abs(e.offsetWidth-i.offsetWidth),n=Math.abs(e.offsetHeight-i.offsetHeight);this.list.style.setProperty("--item-diff-x",t/2+"px"),this.list.style.setProperty("--item-diff-y",n/2+"px")}}this.hideOnClickOutside&&this.bindDocumentClickListener()},beforeMount(){this.bindDocumentClickListener()},methods:{onItemClick(e,i){i.command&&i.command({originalEvent:e,item:i}),this.hide(),this.isItemClicked=!0,e.preventDefault()},onClick(e){this.d_visible?this.hide():this.show(),this.isItemClicked=!0,this.$emit("click",e)},show(){this.d_visible=!0,this.$emit("show")},hide(){this.d_visible=!1,this.$emit("hide")},calculateTransitionDelay(e){const i=this.model.length;return(this.d_visible?e:i-e-1)*this.transitionDelay},calculatePointStyle(e){const i=this.type;if("linear"!==i){const t=this.model.length,n=this.radius||20*t;if("circle"===i){const i=2*Math.PI/t;return{left:`calc(${n*Math.cos(i*e)}px + var(--item-diff-x, 0px))`,top:`calc(${n*Math.sin(i*e)}px + var(--item-diff-y, 0px))`}}if("semi-circle"===i){const i=this.direction,s=Math.PI/(t-1),o=`calc(${n*Math.cos(s*e)}px + var(--item-diff-x, 0px))`,l=`calc(${n*Math.sin(s*e)}px + var(--item-diff-y, 0px))`;if("up"===i)return{left:o,bottom:l};if("down"===i)return{left:o,top:l};if("left"===i)return{right:l,top:o};if("right"===i)return{left:l,top:o}}else if("quarter-circle"===i){const i=this.direction,s=Math.PI/(2*(t-1)),o=`calc(${n*Math.cos(s*e)}px + var(--item-diff-x, 0px))`,l=`calc(${n*Math.sin(s*e)}px + var(--item-diff-y, 0px))`;if("up-left"===i)return{right:o,bottom:l};if("up-right"===i)return{left:o,bottom:l};if("down-left"===i)return{right:l,top:o};if("down-right"===i)return{left:l,top:o}}}return{}},getItemStyle(e){return{transitionDelay:`${this.calculateTransitionDelay(e)}ms`,...this.calculatePointStyle(e)}},bindDocumentClickListener(){this.documentClickListener||(this.documentClickListener=e=>{this.d_visible&&this.isOutsideClicked(e)&&this.hide(),this.isItemClicked=!1},document.addEventListener("click",this.documentClickListener))},unbindDocumentClickListener(){this.documentClickListener&&(document.removeEventListener("click",this.documentClickListener),this.documentClickListener=null)},isOutsideClicked(e){return this.container&&!(this.container.isSameNode(e.target)||this.container.contains(e.target)||this.isItemClicked)},containerRef(e){this.container=e},listRef(e){this.list=e}},computed:{containerClass(){return[`p-speeddial p-component p-speeddial-${this.type}`,{[`p-speeddial-direction-${this.direction}`]:"circle"!==this.type,"p-speeddial-opened":this.d_visible,"p-disabled":this.disabled},this.class]},buttonClassName(){return["p-speeddial-button p-button-rounded",{"p-speeddial-rotate":this.rotateAnimation&&!this.hideIcon},this.buttonClass]},iconClassName(){return this.d_visible&&this.hideIcon?this.hideIcon:this.showIcon},maskClassName(){return["p-speeddial-mask",{"p-speeddial-mask-visible":this.d_visible},this.maskClass]}},components:{SDButton:o.default},directives:{ripple:l.default}};return function(e,i){void 0===i&&(i={});var t=i.insertAt;if(e&&"undefined"!=typeof document){var n=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===t&&n.firstChild?n.insertBefore(s,n.firstChild):n.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}("\n.p-speeddial {\n    position: absolute;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    z-index: 1;\n}\n.p-speeddial-list {\n    margin: 0;\n    padding: 0;\n    list-style: none;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-transition: top 0s linear 0.2s;\n    transition: top 0s linear 0.2s;\n    pointer-events: none;\n}\n.p-speeddial-item {\n    -webkit-transform: scale(0);\n            transform: scale(0);\n    opacity: 0;\n    -webkit-transition: opacity 0.8s, -webkit-transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: opacity 0.8s, -webkit-transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, opacity 0.8s;\n    transition: transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, opacity 0.8s, -webkit-transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    will-change: transform;\n}\n.p-speeddial-action {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    border-radius: 50%;\n    position: relative;\n    overflow: hidden;\n}\n.p-speeddial-circle .p-speeddial-item,\n.p-speeddial-semi-circle .p-speeddial-item,\n.p-speeddial-quarter-circle .p-speeddial-item {\n    position: absolute;\n}\n.p-speeddial-rotate {\n    -webkit-transition: -webkit-transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: -webkit-transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, -webkit-transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    will-change: transform;\n}\n.p-speeddial-mask {\n    position: absolute;\n    left: 0;\n    top: 0;\n    width: 100%;\n    height: 100%;\n    opacity: 0;\n    -webkit-transition: opacity 250ms cubic-bezier(0.25, 0.8, 0.25, 1);\n    transition: opacity 250ms cubic-bezier(0.25, 0.8, 0.25, 1);\n}\n.p-speeddial-mask-visible {\n    pointer-events: none;\n    opacity: 1;\n    -webkit-transition: opacity 400ms cubic-bezier(0.25, 0.8, 0.25, 1);\n    transition: opacity 400ms cubic-bezier(0.25, 0.8, 0.25, 1);\n}\n.p-speeddial-opened .p-speeddial-list {\n    pointer-events: auto;\n}\n.p-speeddial-opened .p-speeddial-item {\n    -webkit-transform: scale(1);\n            transform: scale(1);\n    opacity: 1;\n}\n.p-speeddial-opened .p-speeddial-rotate {\n    -webkit-transform: rotate(45deg);\n            transform: rotate(45deg);\n}\n\n/* Direction */\n.p-speeddial-direction-up {\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: column-reverse;\n            flex-direction: column-reverse;\n}\n.p-speeddial-direction-up .p-speeddial-list {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: column-reverse;\n            flex-direction: column-reverse;\n}\n.p-speeddial-direction-down {\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-speeddial-direction-down .p-speeddial-list {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-speeddial-direction-left {\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: row-reverse;\n            flex-direction: row-reverse;\n}\n.p-speeddial-direction-left .p-speeddial-list {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: row-reverse;\n            flex-direction: row-reverse;\n}\n.p-speeddial-direction-right {\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: row;\n            flex-direction: row;\n}\n.p-speeddial-direction-right .p-speeddial-list {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: row;\n            flex-direction: row;\n}\n"),r.render=function(e,i,t,s,o,l){const r=n.resolveComponent("SDButton"),a=n.resolveDirective("tooltip"),c=n.resolveDirective("ripple");return n.openBlock(),n.createBlock(n.Fragment,null,[n.createVNode("div",{ref:l.containerRef,class:l.containerClass,style:t.style},[n.renderSlot(e.$slots,"button",{toggle:l.onClick},(()=>[n.createVNode(r,{type:"button",class:l.buttonClassName,icon:l.iconClassName,onClick:i[1]||(i[1]=e=>l.onClick(e)),disabled:t.disabled},null,8,["class","icon","disabled"])])),n.createVNode("ul",{ref:l.listRef,class:"p-speeddial-list",role:"menu"},[(n.openBlock(!0),n.createBlock(n.Fragment,null,n.renderList(t.model,((i,s)=>(n.openBlock(),n.createBlock("li",{key:s,class:"p-speeddial-item",style:l.getItemStyle(s),role:"none"},[e.$slots.item?(n.openBlock(),n.createBlock(n.resolveDynamicComponent(e.$slots.item),{key:1,item:i},null,8,["item"])):n.withDirectives((n.openBlock(),n.createBlock("a",{key:0,href:i.url||"#",role:"menuitem",class:["p-speeddial-action",{"p-disabled":i.disabled}],target:i.target,onClick:e=>l.onItemClick(e,i)},[i.icon?(n.openBlock(),n.createBlock("span",{key:0,class:["p-speeddial-action-icon",i.icon]},null,2)):n.createCommentVNode("",!0)],10,["href","target","onClick"])),[[a,{value:i.label,disabled:!t.tooltipOptions},t.tooltipOptions],[c]])],4)))),128))],512)],6),t.mask?(n.openBlock(),n.createBlock("div",{key:0,class:l.maskClassName,style:t.maskStyle},null,6)):n.createCommentVNode("",!0)],64)},r}(primevue.button,primevue.ripple,primevue.utils,Vue);');
				break;
			case "config":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.config=function(e,t,a){"use strict";const o={ripple:!1,inputStyle:"outlined",locale:{startsWith:"Starts with",contains:"Contains",notContains:"Not contains",endsWith:"Ends with",equals:"Equals",notEquals:"Not equals",noFilter:"No Filter",lt:"Less than",lte:"Less than or equal to",gt:"Greater than",gte:"Greater than or equal to",dateIs:"Date is",dateIsNot:"Date is not",dateBefore:"Date is before",dateAfter:"Date is after",clear:"Clear",apply:"Apply",matchAll:"Match All",matchAny:"Match Any",addRule:"Add Rule",removeRule:"Remove Rule",accept:"Yes",reject:"No",choose:"Choose",upload:"Upload",cancel:"Cancel",dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],today:"Today",weekHeader:"Wk",firstDayOfWeek:0,dateFormat:"mm/dd/yy",weak:"Weak",medium:"Medium",strong:"Strong",passwordPrompt:"Enter a password",emptyFilterMessage:"No results found",emptyMessage:"No available options"},filterMatchModeOptions:{text:[a.FilterMatchMode.STARTS_WITH,a.FilterMatchMode.CONTAINS,a.FilterMatchMode.NOT_CONTAINS,a.FilterMatchMode.ENDS_WITH,a.FilterMatchMode.EQUALS,a.FilterMatchMode.NOT_EQUALS],numeric:[a.FilterMatchMode.EQUALS,a.FilterMatchMode.NOT_EQUALS,a.FilterMatchMode.LESS_THAN,a.FilterMatchMode.LESS_THAN_OR_EQUAL_TO,a.FilterMatchMode.GREATER_THAN,a.FilterMatchMode.GREATER_THAN_OR_EQUAL_TO],date:[a.FilterMatchMode.DATE_IS,a.FilterMatchMode.DATE_IS_NOT,a.FilterMatchMode.DATE_BEFORE,a.FilterMatchMode.DATE_AFTER]},zIndex:{modal:1100,overlay:1e3,menu:1e3,tooltip:1100}},r=Symbol();var i={install:(e,a)=>{let i=a?{...o,...a}:{...o};const l={config:t.reactive(i)};e.config.globalProperties.$primevue=l,e.provide(r,l)}};return e.default=i,e.usePrimeVue=function(){const e=t.inject(r);if(!e)throw new Error("PrimeVue is not installed!");return e},Object.defineProperty(e,"__esModule",{value:!0}),e}({},Vue,primevue.api);');
				break;
			case "calendar":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.calendar=function(e,t,n,i,a,s){"use strict";function o(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var r=o(t),l=o(n),c=o(i),h=o(a),d={name:"Calendar",inheritAttrs:!1,emits:["show","hide","month-change","year-change","date-select","update:modelValue","today-click","clear-click"],props:{modelValue:null,selectionMode:{type:String,default:"single"},dateFormat:{type:String,default:null},inline:{type:Boolean,default:!1},showOtherMonths:{type:Boolean,default:!0},selectOtherMonths:{type:Boolean,default:!1},showIcon:{type:Boolean,default:!1},icon:{type:String,default:"pi pi-calendar"},numberOfMonths:{type:Number,default:1},view:{type:String,default:"date"},touchUI:{type:Boolean,default:!1},monthNavigator:{type:Boolean,default:!1},yearNavigator:{type:Boolean,default:!1},yearRange:{type:String,default:null},panelClass:{type:String,default:null},minDate:{type:Date,value:null},maxDate:{type:Date,value:null},disabledDates:{type:Array,value:null},disabledDays:{type:Array,value:null},maxDateCount:{type:Number,value:null},showOnFocus:{type:Boolean,default:!0},autoZIndex:{type:Boolean,default:!0},baseZIndex:{type:Number,default:0},showButtonBar:{type:Boolean,default:!1},shortYearCutoff:{type:String,default:"+10"},showTime:{type:Boolean,default:!1},timeOnly:{type:Boolean,default:!1},hourFormat:{type:String,default:"24"},stepHour:{type:Number,default:1},stepMinute:{type:Number,default:1},stepSecond:{type:Number,default:1},showSeconds:{type:Boolean,default:!1},hideOnDateTimeSelect:{type:Boolean,default:!1},timeSeparator:{type:String,default:":"},showWeek:{type:Boolean,default:!1},manualInput:{type:Boolean,default:!0},appendTo:{type:String,default:"body"},keepInvalid:{type:Boolean,default:!1},inputClass:null,inputStyle:null,class:null,style:null},navigationState:null,scrollHandler:null,outsideClickListener:null,maskClickListener:null,resizeListener:null,overlay:null,mask:null,timePickerTimer:null,isKeydown:!1,created(){this.updateCurrentMetaData()},mounted(){this.inline&&!this.$attrs.disabled&&this.initFocusableCell()},updated(){this.overlay&&this.updateFocus(),this.$refs.input&&null!=this.selectionStart&&null!=this.selectionEnd&&(this.$refs.input.$el.selectionStart=this.selectionStart,this.$refs.input.$el.selectionEnd=this.selectionEnd,this.selectionStart=null,this.selectionEnd=null)},beforeUnmount(){this.timePickerTimer&&clearTimeout(this.timePickerTimer),this.mask&&this.destroyMask(),this.unbindOutsideClickListener(),this.unbindResizeListener(),this.scrollHandler&&(this.scrollHandler.destroy(),this.scrollHandler=null),this.overlay&&this.autoZIndex&&e.ZIndexUtils.clear(this.overlay),this.overlay=null},data:()=>({currentMonth:null,currentYear:null,currentHour:null,currentMinute:null,currentSecond:null,pm:null,focused:!1,overlayVisible:!1}),watch:{modelValue(){this.updateCurrentMetaData()},showTime(){this.updateCurrentMetaData()},months(){this.overlay&&setTimeout(this.updateFocus,0)}},methods:{isComparable(){return null!=this.modelValue&&"string"!=typeof this.modelValue},isSelected(e){if(!this.isComparable())return!1;if(this.modelValue){if(this.isSingleSelection())return this.isDateEquals(this.modelValue,e);if(this.isMultipleSelection()){let t=!1;for(let n of this.modelValue)if(t=this.isDateEquals(n,e),t)break;return t}if(this.isRangeSelection())return this.modelValue[1]?this.isDateEquals(this.modelValue[0],e)||this.isDateEquals(this.modelValue[1],e)||this.isDateBetween(this.modelValue[0],this.modelValue[1],e):this.isDateEquals(this.modelValue[0],e)}return!1},isMonthSelected(e){return!!this.isComparable()&&(this.modelValue.getMonth()===e&&this.modelValue.getFullYear()===this.currentYear)},isDateEquals:(e,t)=>!!e&&(e.getDate()===t.day&&e.getMonth()===t.month&&e.getFullYear()===t.year),isDateBetween(e,t,n){if(e&&t){let i=new Date(n.year,n.month,n.day);return e.getTime()<=i.getTime()&&t.getTime()>=i.getTime()}return!1},getFirstDayOfMonthIndex(e,t){let n=new Date;n.setDate(1),n.setMonth(e),n.setFullYear(t);let i=n.getDay()+this.sundayIndex;return i>=7?i-7:i},getDaysCountInMonth(e,t){return 32-this.daylightSavingAdjust(new Date(t,e,32)).getDate()},getDaysCountInPrevMonth(e,t){let n=this.getPreviousMonthAndYear(e,t);return this.getDaysCountInMonth(n.month,n.year)},getPreviousMonthAndYear(e,t){let n,i;return 0===e?(n=11,i=t-1):(n=e-1,i=t),{month:n,year:i}},getNextMonthAndYear(e,t){let n,i;return 11===e?(n=0,i=t+1):(n=e+1,i=t),{month:n,year:i}},daylightSavingAdjust:e=>e?(e.setHours(e.getHours()>12?e.getHours()+2:0),e):null,isToday:(e,t,n,i)=>e.getDate()===t&&e.getMonth()===n&&e.getFullYear()===i,isSelectable(e,t,n,i){let a=!0,s=!0,o=!0,r=!0;return!(i&&!this.selectOtherMonths)&&(this.minDate&&(this.minDate.getFullYear()>n||this.minDate.getFullYear()===n&&(this.minDate.getMonth()>t||this.minDate.getMonth()===t&&this.minDate.getDate()>e))&&(a=!1),this.maxDate&&(this.maxDate.getFullYear()<n||this.maxDate.getFullYear()===n&&(this.maxDate.getMonth()<t||this.maxDate.getMonth()===t&&this.maxDate.getDate()<e))&&(s=!1),this.disabledDates&&(o=!this.isDateDisabled(e,t,n)),this.disabledDays&&(r=!this.isDayDisabled(e,t,n)),a&&s&&o&&r)},onOverlayEnter(t){this.autoZIndex&&(this.touchUI?e.ZIndexUtils.set("modal",t,this.baseZIndex||this.$primevue.config.zIndex.modal):e.ZIndexUtils.set("overlay",t,this.baseZIndex||this.$primevue.config.zIndex.overlay)),this.alignOverlay(),this.$emit("show")},onOverlayEnterComplete(){this.bindOutsideClickListener(),this.bindScrollListener(),this.bindResizeListener()},onOverlayAfterLeave(t){this.autoZIndex&&e.ZIndexUtils.clear(t)},onOverlayLeave(){this.unbindOutsideClickListener(),this.unbindScrollListener(),this.unbindResizeListener(),this.$emit("hide"),this.mask&&this.disableModality(),this.overlay=null},onPrevButtonClick(e){this.showOtherMonths&&(this.navigationState={backward:!0,button:!0},this.navBackward(e))},onNextButtonClick(e){this.showOtherMonths&&(this.navigationState={backward:!1,button:!0},this.navForward(e))},navBackward(e){e.preventDefault(),this.isEnabled()&&("month"===this.view?this.decrementYear():(0===this.currentMonth?(this.currentMonth=11,this.decrementYear()):this.currentMonth--,this.$emit("month-change",{month:this.currentMonth,year:this.currentYear})))},navForward(e){e.preventDefault(),this.isEnabled()&&("month"===this.view?this.incrementYear():(11===this.currentMonth?(this.currentMonth=0,this.incrementYear()):this.currentMonth++,this.$emit("month-change",{month:this.currentMonth,year:this.currentYear})))},decrementYear(){this.currentYear--},incrementYear(){this.currentYear++},isEnabled(){return!this.$attrs.disabled&&!this.$attrs.readonly},updateCurrentTimeMeta(e){let t=e.getHours();"12"===this.hourFormat&&(this.pm=t>11,t=t>=12?12==t?12:t-12:0==t?12:t),this.currentHour=Math.floor(t/this.stepHour)*this.stepHour,this.currentMinute=Math.floor(e.getMinutes()/this.stepMinute)*this.stepMinute,this.currentSecond=Math.floor(e.getSeconds()/this.stepSecond)*this.stepSecond},bindOutsideClickListener(){this.outsideClickListener||(this.outsideClickListener=e=>{this.overlayVisible&&this.isOutsideClicked(e)&&(this.overlayVisible=!1)},document.addEventListener("mousedown",this.outsideClickListener))},unbindOutsideClickListener(){this.outsideClickListener&&(document.removeEventListener("mousedown",this.outsideClickListener),this.outsideClickListener=null)},bindScrollListener(){this.scrollHandler||(this.scrollHandler=new e.ConnectedOverlayScrollHandler(this.$refs.container,(()=>{this.overlayVisible&&(this.overlayVisible=!1)}))),this.scrollHandler.bindScrollListener()},unbindScrollListener(){this.scrollHandler&&this.scrollHandler.unbindScrollListener()},bindResizeListener(){this.resizeListener||(this.resizeListener=()=>{this.overlayVisible&&(this.overlayVisible=!1)},window.addEventListener("resize",this.resizeListener))},unbindResizeListener(){this.resizeListener&&(window.removeEventListener("resize",this.resizeListener),this.resizeListener=null)},isOutsideClicked(e){return!(this.$el.isSameNode(e.target)||this.isNavIconClicked(e)||this.$el.contains(e.target)||this.overlay&&this.overlay.contains(e.target))},isNavIconClicked:t=>e.DomHandler.hasClass(t.target,"p-datepicker-prev")||e.DomHandler.hasClass(t.target,"p-datepicker-prev-icon")||e.DomHandler.hasClass(t.target,"p-datepicker-next")||e.DomHandler.hasClass(t.target,"p-datepicker-next-icon"),alignOverlay(){this.touchUI?this.enableModality():this.overlay&&(this.appendDisabled?e.DomHandler.relativePosition(this.overlay,this.$el):(this.overlay.style.minWidth=e.DomHandler.getOuterWidth(this.$el)+"px",e.DomHandler.absolutePosition(this.overlay,this.$el)))},onButtonClick(){this.isEnabled()&&(this.overlayVisible?this.overlayVisible=!1:(this.$refs.input.$el.focus(),this.overlayVisible=!0))},isDateDisabled(e,t,n){if(this.disabledDates)for(let i of this.disabledDates)if(i.getFullYear()===n&&i.getMonth()===t&&i.getDate()===e)return!0;return!1},isDayDisabled(e,t,n){if(this.disabledDays){let i=new Date(n,t,e).getDay();return-1!==this.disabledDays.indexOf(i)}return!1},onMonthDropdownChange(e){this.currentMonth=parseInt(e),this.$emit("month-change",{month:this.currentMonth+1,year:this.currentYear})},onYearDropdownChange(e){this.currentYear=parseInt(e),this.$emit("year-change",{month:this.currentMonth+1,year:this.currentYear})},onDateSelect(t,n){if(!this.$attrs.disabled&&n.selectable){if(e.DomHandler.find(this.overlay,".p-datepicker-calendar td span:not(.p-disabled)").forEach((e=>e.tabIndex=-1)),t&&t.currentTarget.focus(),this.isMultipleSelection()&&this.isSelected(n)){let e=this.modelValue.filter((e=>!this.isDateEquals(e,n)));this.updateModel(e)}else this.shouldSelectDate(n)&&(n.otherMonth?(this.currentMonth=n.month,this.currentYear=n.year,this.selectDate(n)):this.selectDate(n));!this.isSingleSelection()||this.showTime&&!this.hideOnDateTimeSelect||setTimeout((()=>{this.overlayVisible=!1}),150)}},selectDate(e){let t=new Date(e.year,e.month,e.day);this.showTime&&("12"===this.hourFormat&&this.pm&&12!=this.currentHour?t.setHours(this.currentHour+12):t.setHours(this.currentHour),t.setMinutes(this.currentMinute),t.setSeconds(this.currentSecond)),this.minDate&&this.minDate>t&&(t=this.minDate,this.currentHour=t.getHours(),this.currentMinute=t.getMinutes(),this.currentSecond=t.getSeconds()),this.maxDate&&this.maxDate<t&&(t=this.maxDate,this.currentHour=t.getHours(),this.currentMinute=t.getMinutes(),this.currentSecond=t.getSeconds());let n=null;if(this.isSingleSelection())n=t;else if(this.isMultipleSelection())n=this.modelValue?[...this.modelValue,t]:[t];else if(this.isRangeSelection())if(this.modelValue&&this.modelValue.length){let e=this.modelValue[0],i=this.modelValue[1];!i&&t.getTime()>=e.getTime()?i=t:(e=t,i=null),n=[e,i]}else n=[t,null];null!==n&&this.updateModel(n),this.$emit("date-select",t)},updateModel(e){this.$emit("update:modelValue",e)},shouldSelectDate(){return!this.isMultipleSelection()||(null==this.maxDateCount||this.maxDateCount>(this.modelValue?this.modelValue.length:0))},isSingleSelection(){return"single"===this.selectionMode},isRangeSelection(){return"range"===this.selectionMode},isMultipleSelection(){return"multiple"===this.selectionMode},formatValue(e){if("string"==typeof e)return e;let t="";if(e)try{if(this.isSingleSelection())t=this.formatDateTime(e);else if(this.isMultipleSelection())for(let n=0;n<e.length;n++){t+=this.formatDateTime(e[n]),n!==e.length-1&&(t+=", ")}else if(this.isRangeSelection()&&e&&e.length){let n=e[0],i=e[1];t=this.formatDateTime(n),i&&(t+=" - "+this.formatDateTime(i))}}catch(n){t=e}return t},formatDateTime(e){let t=null;return e&&(this.timeOnly?t=this.formatTime(e):(t=this.formatDate(e,this.datePattern),this.showTime&&(t+=" "+this.formatTime(e)))),t},formatDate(e,t){if(!e)return"";let n;const i=e=>{const i=n+1<t.length&&t.charAt(n+1)===e;return i&&n++,i},a=(e,t,n)=>{let a=""+t;if(i(e))for(;a.length<n;)a="0"+a;return a},s=(e,t,n,a)=>i(e)?a[t]:n[t];let o="",r=!1;if(e)for(n=0;n<t.length;n++)if(r)"\'"!==t.charAt(n)||i("\'")?o+=t.charAt(n):r=!1;else switch(t.charAt(n)){case"d":o+=a("d",e.getDate(),2);break;case"D":o+=s("D",e.getDay(),this.$primevue.config.locale.dayNamesShort,this.$primevue.config.locale.dayNames);break;case"o":o+=a("o",Math.round((new Date(e.getFullYear(),e.getMonth(),e.getDate()).getTime()-new Date(e.getFullYear(),0,0).getTime())/864e5),3);break;case"m":o+=a("m",e.getMonth()+1,2);break;case"M":o+=s("M",e.getMonth(),this.$primevue.config.locale.monthNamesShort,this.$primevue.config.locale.monthNames);break;case"y":o+=i("y")?e.getFullYear():(e.getFullYear()%100<10?"0":"")+e.getFullYear()%100;break;case"@":o+=e.getTime();break;case"!":o+=1e4*e.getTime()+this.ticksTo1970;break;case"\'":i("\'")?o+="\'":r=!0;break;default:o+=t.charAt(n)}return o},formatTime(e){if(!e)return"";let t="",n=e.getHours(),i=e.getMinutes(),a=e.getSeconds();return"12"===this.hourFormat&&n>11&&12!==n&&(n-=12),"12"===this.hourFormat?t+=0===n?12:n<10?"0"+n:n:t+=n<10?"0"+n:n,t+=":",t+=i<10?"0"+i:i,this.showSeconds&&(t+=":",t+=a<10?"0"+a:a),"12"===this.hourFormat&&(t+=e.getHours()>11?" PM":" AM"),t},onTodayButtonClick(e){let t=new Date,n={day:t.getDate(),month:t.getMonth(),year:t.getFullYear(),otherMonth:t.getMonth()!==this.currentMonth||t.getFullYear()!==this.currentYear,today:!0,selectable:!0};this.onDateSelect(null,n),this.$emit("today-click",t),e.preventDefault()},onClearButtonClick(e){this.updateModel(null),this.overlayVisible=!1,this.$emit("clear-click",e),e.preventDefault()},onTimePickerElementMouseDown(e,t,n){this.isEnabled()&&(this.repeat(e,null,t,n),e.preventDefault())},onTimePickerElementMouseUp(e){this.isEnabled()&&(this.clearTimePickerTimer(),this.updateModelTime(),e.preventDefault())},onTimePickerElementMouseLeave(){this.clearTimePickerTimer()},repeat(e,t,n,i){let a=t||500;switch(this.clearTimePickerTimer(),this.timePickerTimer=setTimeout((()=>{this.repeat(e,100,n,i)}),a),n){case 0:1===i?this.incrementHour(e):this.decrementHour(e);break;case 1:1===i?this.incrementMinute(e):this.decrementMinute(e);break;case 2:1===i?this.incrementSecond(e):this.decrementSecond(e)}},convertTo24Hour(e,t){return"12"==this.hourFormat?12===e?t?12:0:t?e+12:e:e},validateTime(e,t,n,i){let a=this.modelValue;const s=this.convertTo24Hour(e,i);if(!this.isComparable())return!0;this.isRangeSelection()&&(a=this.modelValue[1]||this.modelValue[0]),this.isMultipleSelection()&&(a=this.modelValue[this.modelValue.length-1]);const o=a?a.toDateString():null;if(this.minDate&&o&&this.minDate.toDateString()===o){if(this.minDate.getHours()>s)return!1;if(this.minDate.getHours()===s){if(this.minDate.getMinutes()>t)return!1;if(this.minDate.getMinutes()===t&&this.minDate.getSeconds()>n)return!1}}if(this.maxDate&&o&&this.maxDate.toDateString()===o){if(this.maxDate.getHours()<s)return!1;if(this.maxDate.getHours()===s){if(this.maxDate.getMinutes()<t)return!1;if(this.maxDate.getMinutes()===t&&this.maxDate.getSeconds()<n)return!1}}return!0},incrementHour(e){let t=this.currentHour,n=this.currentHour+this.stepHour,i=this.pm;"24"==this.hourFormat?n=n>=24?n-24:n:"12"==this.hourFormat&&(t<12&&n>11&&(i=!this.pm),n=n>=13?n-12:n),this.validateTime(n,this.currentMinute,this.currentSecond,i)&&(this.currentHour=n,this.pm=i),e.preventDefault()},decrementHour(e){let t=this.currentHour-this.stepHour,n=this.pm;"24"==this.hourFormat?t=t<0?24+t:t:"12"==this.hourFormat&&(12===this.currentHour&&(n=!this.pm),t=t<=0?12+t:t),this.validateTime(t,this.currentMinute,this.currentSecond,n)&&(this.currentHour=t,this.pm=n),e.preventDefault()},incrementMinute(e){let t=this.currentMinute+this.stepMinute;this.validateTime(this.currentHour,t,this.currentSecond,!0)&&(this.currentMinute=t>59?t-60:t),e.preventDefault()},decrementMinute(e){let t=this.currentMinute-this.stepMinute;t=t<0?60+t:t,this.validateTime(this.currentHour,t,this.currentSecond,!0)&&(this.currentMinute=t),e.preventDefault()},incrementSecond(e){let t=this.currentSecond+this.stepSecond;this.validateTime(this.currentHour,this.currentMinute,t,!0)&&(this.currentSecond=t>59?t-60:t),e.preventDefault()},decrementSecond(e){let t=this.currentSecond-this.stepSecond;t=t<0?60+t:t,this.validateTime(this.currentHour,this.currentMinute,t,!0)&&(this.currentSecond=t),e.preventDefault()},updateModelTime(){let e=this.isComparable()?this.modelValue:new Date;this.isRangeSelection()&&(e=this.modelValue[1]||this.modelValue[0]),this.isMultipleSelection()&&(e=this.modelValue[this.modelValue.length-1]),e=e?new Date(e.getTime()):new Date,"12"==this.hourFormat?12===this.currentHour?e.setHours(this.pm?12:0):e.setHours(this.pm?this.currentHour+12:this.currentHour):e.setHours(this.currentHour),e.setMinutes(this.currentMinute),e.setSeconds(this.currentSecond),this.isRangeSelection()&&(e=this.modelValue[1]?[this.modelValue[0],e]:[e,null]),this.isMultipleSelection()&&(e=[...this.modelValue.slice(0,-1),e]),this.updateModel(e),this.$emit("date-select",e)},toggleAMPM(e){this.pm=!this.pm,this.updateModelTime(),e.preventDefault()},clearTimePickerTimer(){this.timePickerTimer&&clearInterval(this.timePickerTimer)},onMonthSelect(e,t){this.onDateSelect(e,{year:this.currentYear,month:t,day:1,selectable:!0})},enableModality(){this.mask||(this.mask=document.createElement("div"),this.mask.style.zIndex=String(parseInt(this.overlay.style.zIndex,10)-1),e.DomHandler.addMultipleClasses(this.mask,"p-datepicker-mask p-datepicker-mask-scrollblocker p-component-overlay p-component-overlay-enter"),this.maskClickListener=()=>{this.overlayVisible=!1},this.mask.addEventListener("click",this.maskClickListener),document.body.appendChild(this.mask),e.DomHandler.addClass(document.body,"p-overflow-hidden"))},disableModality(){this.mask&&(e.DomHandler.addClass(this.mask,"p-component-overlay-leave"),this.mask.addEventListener("animationend",(()=>{this.destroyMask()})))},destroyMask(){this.mask.removeEventListener("click",this.maskClickListener),this.maskClickListener=null,document.body.removeChild(this.mask),this.mask=null;let t,n=document.body.children;for(let i=0;i<n.length;i++){let a=n[i];if(e.DomHandler.hasClass(a,"p-datepicker-mask-scrollblocker")){t=!0;break}}t||e.DomHandler.removeClass(document.body,"p-overflow-hidden")},updateCurrentMetaData(){const e=this.viewDate;this.currentMonth=e.getMonth(),this.currentYear=e.getFullYear(),(this.showTime||this.timeOnly)&&this.updateCurrentTimeMeta(e)},isValidSelection(e){let t=!0;return this.isSingleSelection()?this.isSelectable(e.getDate(),e.getMonth(),e.getFullYear(),!1)||(t=!1):e.every((e=>this.isSelectable(e.getDate(),e.getMonth(),e.getFullYear(),!1)))&&this.isRangeSelection()&&(t=e.length>1&&e[1]>e[0]),t},parseValue(e){if(!e||0===e.trim().length)return null;let t;if(this.isSingleSelection())t=this.parseDateTime(e);else if(this.isMultipleSelection()){let n=e.split(",");t=[];for(let e of n)t.push(this.parseDateTime(e.trim()))}else if(this.isRangeSelection()){let n=e.split(" - ");t=[];for(let e=0;e<n.length;e++)t[e]=this.parseDateTime(n[e].trim())}return t},parseDateTime(e){let t,n=e.split(" ");if(this.timeOnly)t=new Date,this.populateTime(t,n[0],n[1]);else{const i=this.datePattern;this.showTime?(t=this.parseDate(n[0],i),this.populateTime(t,n[1],n[2])):t=this.parseDate(e,i)}return t},populateTime(e,t,n){if("12"==this.hourFormat&&!n)throw"Invalid Time";this.pm="PM"===n||"pm"===n;let i=this.parseTime(t);e.setHours(i.hour),e.setMinutes(i.minute),e.setSeconds(i.second)},parseTime(e){let t=e.split(":"),n=this.showSeconds?3:2,i=/^[0-9][0-9]$/;if(t.length!==n||!t[0].match(i)||!t[1].match(i)||this.showSeconds&&!t[2].match(i))throw"Invalid time";let a=parseInt(t[0]),s=parseInt(t[1]),o=this.showSeconds?parseInt(t[2]):null;if(isNaN(a)||isNaN(s)||a>23||s>59||"12"==this.hourFormat&&a>12||this.showSeconds&&(isNaN(o)||o>59))throw"Invalid time";return"12"==this.hourFormat&&12!==a&&this.pm&&(a+=12),{hour:a,minute:s,second:o}},parseDate(e,t){if(null==t||null==e)throw"Invalid arguments";if(""===(e="object"==typeof e?e.toString():e+""))return null;let n,i,a,s,o=0,r="string"!=typeof this.shortYearCutoff?this.shortYearCutoff:(new Date).getFullYear()%100+parseInt(this.shortYearCutoff,10),l=-1,c=-1,h=-1,d=-1,u=!1,p=e=>{let i=n+1<t.length&&t.charAt(n+1)===e;return i&&n++,i},m=t=>{let n=p(t),i="@"===t?14:"!"===t?20:"y"===t&&n?4:"o"===t?3:2,a=new RegExp("^\\d{"+("y"===t?i:1)+","+i+"}"),s=e.substring(o).match(a);if(!s)throw"Missing number at position "+o;return o+=s[0].length,parseInt(s[0],10)},y=(t,n,i)=>{let a=-1,s=p(t)?i:n,r=[];for(let e=0;e<s.length;e++)r.push([e,s[e]]);r.sort(((e,t)=>-(e[1].length-t[1].length)));for(let t=0;t<r.length;t++){let n=r[t][1];if(e.substr(o,n.length).toLowerCase()===n.toLowerCase()){a=r[t][0],o+=n.length;break}}if(-1!==a)return a+1;throw"Unknown name at position "+o},k=()=>{if(e.charAt(o)!==t.charAt(n))throw"Unexpected literal at position "+o;o++};for("month"===this.view&&(h=1),n=0;n<t.length;n++)if(u)"\'"!==t.charAt(n)||p("\'")?k():u=!1;else switch(t.charAt(n)){case"d":h=m("d");break;case"D":y("D",this.$primevue.config.locale.dayNamesShort,this.$primevue.config.locale.dayNames);break;case"o":d=m("o");break;case"m":c=m("m");break;case"M":c=y("M",this.$primevue.config.locale.monthNamesShort,this.$primevue.config.locale.monthNames);break;case"y":l=m("y");break;case"@":s=new Date(m("@")),l=s.getFullYear(),c=s.getMonth()+1,h=s.getDate();break;case"!":s=new Date((m("!")-this.ticksTo1970)/1e4),l=s.getFullYear(),c=s.getMonth()+1,h=s.getDate();break;case"\'":p("\'")?k():u=!0;break;default:k()}if(o<e.length&&(a=e.substr(o),!/^\s+/.test(a)))throw"Extra/unparsed characters found in date: "+a;if(-1===l?l=(new Date).getFullYear():l<100&&(l+=(new Date).getFullYear()-(new Date).getFullYear()%100+(l<=r?0:-100)),d>-1)for(c=1,h=d;;){if(i=this.getDaysCountInMonth(l,c-1),h<=i)break;c++,h-=i}if(s=this.daylightSavingAdjust(new Date(l,c-1,h)),s.getFullYear()!==l||s.getMonth()+1!==c||s.getDate()!==h)throw"Invalid date";return s},getWeekNumber(e){let t=new Date(e.getTime());t.setDate(t.getDate()+4-(t.getDay()||7));let n=t.getTime();return t.setMonth(0),t.setDate(1),Math.floor(Math.round((n-t.getTime())/864e5)/7)+1},onDateCellKeydown(t,n,i){const a=t.currentTarget,s=a.parentElement;switch(t.which){case 40:{a.tabIndex="-1";let n=e.DomHandler.index(s),i=s.parentElement.nextElementSibling;if(i){let a=i.children[n].children[0];e.DomHandler.hasClass(a,"p-disabled")?(this.navigationState={backward:!1},this.navForward(t)):(i.children[n].children[0].tabIndex="0",i.children[n].children[0].focus())}else this.navigationState={backward:!1},this.navForward(t);t.preventDefault();break}case 38:{a.tabIndex="-1";let n=e.DomHandler.index(s),i=s.parentElement.previousElementSibling;if(i){let a=i.children[n].children[0];e.DomHandler.hasClass(a,"p-disabled")?(this.navigationState={backward:!0},this.navBackward(t)):(a.tabIndex="0",a.focus())}else this.navigationState={backward:!0},this.navBackward(t);t.preventDefault();break}case 37:{a.tabIndex="-1";let n=s.previousElementSibling;if(n){let t=n.children[0];e.DomHandler.hasClass(t,"p-disabled")?this.navigateToMonth(!0,i):(t.tabIndex="0",t.focus())}else this.navigateToMonth(!0,i);t.preventDefault();break}case 39:{a.tabIndex="-1";let n=s.nextElementSibling;if(n){let t=n.children[0];e.DomHandler.hasClass(t,"p-disabled")?this.navigateToMonth(!1,i):(t.tabIndex="0",t.focus())}else this.navigateToMonth(!1,i);t.preventDefault();break}case 13:this.onDateSelect(t,n),t.preventDefault();break;case 27:this.overlayVisible=!1,t.preventDefault();break;case 9:this.inline||this.trapFocus(t)}},navigateToMonth(t,n){if(t)if(1===this.numberOfMonths||0===n)this.navigationState={backward:!0},this.navBackward(event);else{let t=this.overlay.children[n-1],i=e.DomHandler.find(t,".p-datepicker-calendar td span:not(.p-disabled)"),a=i[i.length-1];a.tabIndex="0",a.focus()}else if(1===this.numberOfMonths||n===this.numberOfMonths-1)this.navigationState={backward:!1},this.navForward(event);else{let t=this.overlay.children[n+1],i=e.DomHandler.findSingle(t,".p-datepicker-calendar td span:not(.p-disabled)");i.tabIndex="0",i.focus()}},onMonthCellKeydown(t,n){const i=t.currentTarget;switch(t.which){case 38:case 40:{i.tabIndex="-1";var a=i.parentElement.children,s=e.DomHandler.index(i);let n=a[40===t.which?s+3:s-3];n&&(n.tabIndex="0",n.focus()),t.preventDefault();break}case 37:{i.tabIndex="-1";let e=i.previousElementSibling;e&&(e.tabIndex="0",e.focus()),t.preventDefault();break}case 39:{i.tabIndex="-1";let e=i.nextElementSibling;e&&(e.tabIndex="0",e.focus()),t.preventDefault();break}case 13:this.onMonthSelect(t,n),t.preventDefault();break;case 27:this.overlayVisible=!1,t.preventDefault();break;case 9:this.trapFocus(t)}},updateFocus(){let t;if(this.navigationState){if(this.navigationState.button)this.initFocusableCell(),this.navigationState.backward?e.DomHandler.findSingle(this.overlay,".p-datepicker-prev").focus():e.DomHandler.findSingle(this.overlay,".p-datepicker-next").focus();else{if(this.navigationState.backward){let n=e.DomHandler.find(this.overlay,".p-datepicker-calendar td span:not(.p-disabled)");t=n[n.length-1]}else t=e.DomHandler.findSingle(this.overlay,".p-datepicker-calendar td span:not(.p-disabled)");t&&(t.tabIndex="0",t.focus())}this.navigationState=null}else this.initFocusableCell()},initFocusableCell(){let t;if("month"===this.view){let n=e.DomHandler.find(this.overlay,".p-monthpicker .p-monthpicker-month"),i=e.DomHandler.findSingle(this.overlay,".p-monthpicker .p-monthpicker-month.p-highlight");n.forEach((e=>e.tabIndex=-1)),t=i||n[0]}else if(t=e.DomHandler.findSingle(this.overlay,"span.p-highlight"),!t){let n=e.DomHandler.findSingle(this.overlay,"td.p-datepicker-today span:not(.p-disabled)");t=n||e.DomHandler.findSingle(this.overlay,".p-datepicker-calendar td span:not(.p-disabled)")}t&&(t.tabIndex="0")},trapFocus(t){t.preventDefault();let n=e.DomHandler.getFocusableElements(this.overlay);if(n&&n.length>0)if(document.activeElement){let e=n.indexOf(document.activeElement);t.shiftKey?-1==e||0===e?n[n.length-1].focus():n[e-1].focus():-1==e||e===n.length-1?n[0].focus():n[e+1].focus()}else n[0].focus()},onContainerButtonKeydown(e){switch(e.which){case 9:this.trapFocus(e);break;case 27:this.overlayVisible=!1,e.preventDefault()}},onInput(e){if(this.isKeydown){this.isKeydown=!1;try{this.selectionStart=this.$refs.input.$el.selectionStart,this.selectionEnd=this.$refs.input.$el.selectionEnd;let t=this.parseValue(e.target.value);this.isValidSelection(t)&&this.updateModel(t)}catch(t){let n=this.keepInvalid?e.target.value:null;this.updateModel(n)}}},onFocus(){this.showOnFocus&&this.isEnabled()&&(this.overlayVisible=!0),this.focused=!0},onBlur(){this.focused=!1},onKeyDown(t){this.isKeydown=!0,40===t.keyCode&&this.overlay?this.trapFocus(t):27===t.keyCode?this.overlayVisible&&(this.overlayVisible=!1,t.preventDefault()):9===t.keyCode&&(this.overlay&&e.DomHandler.getFocusableElements(this.overlay).forEach((e=>e.tabIndex="-1")),this.overlayVisible&&(this.overlayVisible=!1))},overlayRef(e){this.overlay=e},getMonthName(e){return this.$primevue.config.locale.monthNames[e]},onOverlayClick(e){this.inline||r.default.emit("overlay-click",{originalEvent:e,target:this.$el})}},computed:{viewDate(){let e=this.modelValue;return"string"==typeof e?new Date:(e&&Array.isArray(e)&&(e=e[0]),e||new Date)},inputFieldValue(){return this.keepInvalid?this.modelValue:this.formatValue(this.modelValue)},containerClass(){return["p-calendar p-component p-inputwrapper",this.class,{"p-calendar-w-btn":this.showIcon,"p-calendar-timeonly":this.timeOnly,"p-inputwrapper-filled":this.modelValue,"p-inputwrapper-focus":this.focused}]},panelStyleClass(){return["p-datepicker p-component",this.panelClass,{"p-datepicker-inline":this.inline,"p-disabled":this.$attrs.disabled,"p-datepicker-timeonly":this.timeOnly,"p-datepicker-multiple-month":this.numberOfMonths>1,"p-datepicker-monthpicker":"month"===this.view,"p-datepicker-touch-ui":this.touchUI,"p-input-filled":"filled"===this.$primevue.config.inputStyle,"p-ripple-disabled":!1===this.$primevue.config.ripple}]},months(){let e=[];for(let t=0;t<this.numberOfMonths;t++){let n=this.currentMonth+t,i=this.currentYear;n>11&&(n=n%11-1,i+=1);let a=[],s=this.getFirstDayOfMonthIndex(n,i),o=this.getDaysCountInMonth(n,i),r=this.getDaysCountInPrevMonth(n,i),l=1,c=new Date,h=[],d=Math.ceil((o+s)/7);for(let e=0;e<d;e++){let t=[];if(0==e){for(let e=r-s+1;e<=r;e++){let a=this.getPreviousMonthAndYear(n,i);t.push({day:e,month:a.month,year:a.year,otherMonth:!0,today:this.isToday(c,e,a.month,a.year),selectable:this.isSelectable(e,a.month,a.year,!0)})}let e=7-t.length;for(let a=0;a<e;a++)t.push({day:l,month:n,year:i,today:this.isToday(c,l,n,i),selectable:this.isSelectable(l,n,i,!1)}),l++}else for(let e=0;e<7;e++){if(l>o){let e=this.getNextMonthAndYear(n,i);t.push({day:l-o,month:e.month,year:e.year,otherMonth:!0,today:this.isToday(c,l-o,e.month,e.year),selectable:this.isSelectable(l-o,e.month,e.year,!0)})}else t.push({day:l,month:n,year:i,today:this.isToday(c,l,n,i),selectable:this.isSelectable(l,n,i,!1)});l++}this.showWeek&&h.push(this.getWeekNumber(new Date(t[0].year,t[0].month,t[0].day))),a.push(t)}e.push({month:n,year:i,dates:a,weekNumbers:h})}return e},weekDays(){let e=[],t=this.$primevue.config.locale.firstDayOfWeek;for(let n=0;n<7;n++)e.push(this.$primevue.config.locale.dayNamesMin[t]),t=6==t?0:++t;return e},ticksTo1970:()=>24*(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925))*60*60*1e7,sundayIndex(){return this.$primevue.config.locale.firstDayOfWeek>0?7-this.$primevue.config.locale.firstDayOfWeek:0},datePattern(){return this.dateFormat||this.$primevue.config.locale.dateFormat},yearOptions(){if(this.yearRange){let e=this;const t=this.yearRange.split(":");let n=parseInt(t[0]),i=parseInt(t[1]),a=[];this.currentYear<n?e.currentYear=i:this.currentYear>i&&(e.currentYear=n);for(let e=n;e<=i;e++)a.push(e);return a}return null},monthPickerValues(){let e=[];for(let t=0;t<=11;t++)e.push(this.$primevue.config.locale.monthNamesShort[t]);return e},formattedCurrentHour(){return this.currentHour<10?"0"+this.currentHour:this.currentHour},formattedCurrentMinute(){return this.currentMinute<10?"0"+this.currentMinute:this.currentMinute},formattedCurrentSecond(){return this.currentSecond<10?"0"+this.currentSecond:this.currentSecond},todayLabel(){return this.$primevue.config.locale.today},clearLabel(){return this.$primevue.config.locale.clear},weekHeaderLabel(){return this.$primevue.config.locale.weekHeader},monthNames(){return this.$primevue.config.locale.monthNames},appendDisabled(){return"self"===this.appendTo||this.inline},appendTarget(){return this.appendDisabled?null:this.appendTo}},components:{CalendarInputText:l.default,CalendarButton:c.default},directives:{ripple:h.default}};const u={class:"p-datepicker-group-container"},p={class:"p-datepicker-header"},m=s.createVNode("span",{class:"p-datepicker-prev-icon pi pi-chevron-left"},null,-1),y={class:"p-datepicker-title"},k={key:0,class:"p-datepicker-month"},f={key:2,class:"p-datepicker-year"},b=s.createVNode("span",{class:"p-datepicker-next-icon pi pi-chevron-right"},null,-1),g={key:0,class:"p-datepicker-calendar-container"},v={class:"p-datepicker-calendar"},w={key:0,scope:"col",class:"p-datepicker-weekheader p-disabled"},D={key:0,class:"p-datepicker-weeknumber"},M={class:"p-disabled"},S={key:0,style:{visibility:"hidden"}},x={key:0,class:"p-monthpicker"},C={key:1,class:"p-timepicker"},B={class:"p-hour-picker"},T=s.createVNode("span",{class:"pi pi-chevron-up"},null,-1),V=s.createVNode("span",{class:"pi pi-chevron-down"},null,-1),N={class:"p-separator"},H={class:"p-minute-picker"},E=s.createVNode("span",{class:"pi pi-chevron-up"},null,-1),F=s.createVNode("span",{class:"pi pi-chevron-down"},null,-1),I={key:0,class:"p-separator"},L={key:1,class:"p-second-picker"},$=s.createVNode("span",{class:"pi pi-chevron-up"},null,-1),P=s.createVNode("span",{class:"pi pi-chevron-down"},null,-1),K={key:2,class:"p-separator"},Y={key:3,class:"p-ampm-picker"},O=s.createVNode("span",{class:"pi pi-chevron-up"},null,-1),A=s.createVNode("span",{class:"pi pi-chevron-down"},null,-1),U={key:2,class:"p-datepicker-buttonbar"};return function(e,t){void 0===t&&(t={});var n=t.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],a=document.createElement("style");a.type="text/css","top"===n&&i.firstChild?i.insertBefore(a,i.firstChild):i.appendChild(a),a.styleSheet?a.styleSheet.cssText=e:a.appendChild(document.createTextNode(e))}}("\n.p-calendar {\n    position: relative;\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n}\n.p-calendar .p-inputtext {\n    -webkit-box-flex: 1;\n        -ms-flex: 1 1 auto;\n            flex: 1 1 auto;\n    width: 1%;\n}\n.p-calendar-w-btn .p-inputtext {\n    border-top-right-radius: 0;\n    border-bottom-right-radius: 0;\n}\n.p-calendar-w-btn .p-datepicker-trigger {\n    border-top-left-radius: 0;\n    border-bottom-left-radius: 0;\n}\n\n/* Fluid */\n.p-fluid .p-calendar {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n}\n.p-fluid .p-calendar .p-inputtext {\n    width: 1%;\n}\n\n/* Datepicker */\n.p-calendar .p-datepicker {\n    min-width: 100%;\n}\n.p-datepicker {\n\twidth: auto;\n    position: absolute;\n    top: 0;\n    left: 0;\n}\n.p-datepicker-inline {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    position: static;\n}\n\n/* Header */\n.p-datepicker-header {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: justify;\n        -ms-flex-pack: justify;\n            justify-content: space-between;\n}\n.p-datepicker-header .p-datepicker-title {\n    margin: 0 auto;\n}\n.p-datepicker-prev,\n.p-datepicker-next {\n    cursor: pointer;\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    overflow: hidden;\n    position: relative;\n}\n\n/* Multiple Month DatePicker */\n.p-datepicker-multiple-month .p-datepicker-group-container {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n}\n\n/* DatePicker Table */\n.p-datepicker table {\n\twidth: 100%;\n\tborder-collapse: collapse;\n}\n.p-datepicker td > span {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    cursor: pointer;\n    margin: 0 auto;\n    overflow: hidden;\n    position: relative;\n}\n\n/* Month Picker */\n.p-monthpicker-month {\n    width: 33.3%;\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    cursor: pointer;\n    overflow: hidden;\n    position: relative;\n}\n\n/*  Button Bar */\n.p-datepicker-buttonbar {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-pack: justify;\n        -ms-flex-pack: justify;\n            justify-content: space-between;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n}\n\n/* Time Picker */\n.p-timepicker {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n}\n.p-timepicker button {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    cursor: pointer;\n    overflow: hidden;\n    position: relative;\n}\n.p-timepicker > div {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n\n/* Touch UI */\n.p-datepicker-touch-ui,\n.p-calendar .p-datepicker-touch-ui {\n    position: fixed;\n    top: 50%;\n    left: 50%;\n    min-width: 80vw;\n    -webkit-transform: translate(-50%, -50%);\n            transform: translate(-50%, -50%);\n}\n"),d.render=function(e,t,n,i,a,o){const r=s.resolveComponent("CalendarInputText"),l=s.resolveComponent("CalendarButton"),c=s.resolveDirective("ripple");return s.openBlock(),s.createBlock("span",{ref:"container",class:o.containerClass,style:n.style},[n.inline?s.createCommentVNode("",!0):(s.openBlock(),s.createBlock(r,s.mergeProps({key:0,ref:"input",type:"text"},e.$attrs,{value:o.inputFieldValue,onInput:o.onInput,onFocus:o.onFocus,onBlur:o.onBlur,onKeydown:o.onKeyDown,readonly:!n.manualInput,inputmode:"none",class:n.inputClass,style:n.inputStyle}),null,16,["value","onInput","onFocus","onBlur","onKeydown","readonly","class","style"])),n.showIcon?(s.openBlock(),s.createBlock(l,{key:1,icon:n.icon,tabindex:"-1",class:"p-datepicker-trigger",disabled:e.$attrs.disabled,onClick:o.onButtonClick,type:"button","aria-label":o.inputFieldValue},null,8,["icon","disabled","onClick","aria-label"])):s.createCommentVNode("",!0),(s.openBlock(),s.createBlock(s.Teleport,{to:o.appendTarget,disabled:o.appendDisabled},[s.createVNode(s.Transition,{name:"p-connected-overlay",onEnter:t[48]||(t[48]=e=>o.onOverlayEnter(e)),onAfterEnter:o.onOverlayEnterComplete,onAfterLeave:o.onOverlayAfterLeave,onLeave:o.onOverlayLeave},{default:s.withCtx((()=>[n.inline||a.overlayVisible?(s.openBlock(),s.createBlock("div",{key:0,ref:o.overlayRef,class:o.panelStyleClass,role:n.inline?null:"dialog",onClick:t[47]||(t[47]=(...e)=>o.onOverlayClick&&o.onOverlayClick(...e))},[n.timeOnly?s.createCommentVNode("",!0):(s.openBlock(),s.createBlock(s.Fragment,{key:0},[s.createVNode("div",u,[(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(o.months,((i,r)=>(s.openBlock(),s.createBlock("div",{class:"p-datepicker-group",key:i.month+i.year},[s.createVNode("div",p,[s.renderSlot(e.$slots,"header"),0===r?s.withDirectives((s.openBlock(),s.createBlock("button",{key:0,class:"p-datepicker-prev p-link",onClick:t[1]||(t[1]=(...e)=>o.onPrevButtonClick&&o.onPrevButtonClick(...e)),type:"button",onKeydown:t[2]||(t[2]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),disabled:e.$attrs.disabled},[m],40,["disabled"])),[[c]]):s.createCommentVNode("",!0),s.createVNode("div",y,[n.monthNavigator||"month"===n.view?s.createCommentVNode("",!0):(s.openBlock(),s.createBlock("span",k,s.toDisplayString(o.getMonthName(i.month)),1)),n.monthNavigator&&"month"!==n.view&&1===n.numberOfMonths?(s.openBlock(),s.createBlock("select",{key:1,class:"p-datepicker-month",onChange:t[3]||(t[3]=e=>o.onMonthDropdownChange(e.target.value))},[(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(o.monthNames,((e,t)=>(s.openBlock(),s.createBlock("option",{value:t,key:e,selected:t===i.month},s.toDisplayString(e),9,["value","selected"])))),128))],32)):s.createCommentVNode("",!0),n.yearNavigator?s.createCommentVNode("",!0):(s.openBlock(),s.createBlock("span",f,s.toDisplayString("month"===n.view?a.currentYear:i.year),1)),n.yearNavigator&&1===n.numberOfMonths?(s.openBlock(),s.createBlock("select",{key:3,class:"p-datepicker-year",onChange:t[4]||(t[4]=e=>o.onYearDropdownChange(e.target.value))},[(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(o.yearOptions,(e=>(s.openBlock(),s.createBlock("option",{value:e,key:e,selected:e===a.currentYear},s.toDisplayString(e),9,["value","selected"])))),128))],32)):s.createCommentVNode("",!0)]),1===n.numberOfMonths||r===n.numberOfMonths-1?s.withDirectives((s.openBlock(),s.createBlock("button",{key:1,class:"p-datepicker-next p-link",onClick:t[5]||(t[5]=(...e)=>o.onNextButtonClick&&o.onNextButtonClick(...e)),type:"button",onKeydown:t[6]||(t[6]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),disabled:e.$attrs.disabled},[b],40,["disabled"])),[[c]]):s.createCommentVNode("",!0)]),"date"===n.view?(s.openBlock(),s.createBlock("div",g,[s.createVNode("table",v,[s.createVNode("thead",null,[s.createVNode("tr",null,[n.showWeek?(s.openBlock(),s.createBlock("th",w,[s.createVNode("span",null,s.toDisplayString(o.weekHeaderLabel),1)])):s.createCommentVNode("",!0),(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(o.weekDays,(e=>(s.openBlock(),s.createBlock("th",{scope:"col",key:e},[s.createVNode("span",null,s.toDisplayString(e),1)])))),128))])]),s.createVNode("tbody",null,[(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(i.dates,((t,a)=>(s.openBlock(),s.createBlock("tr",{key:t[0].day+""+t[0].month},[n.showWeek?(s.openBlock(),s.createBlock("td",D,[s.createVNode("span",M,[i.weekNumbers[a]<10?(s.openBlock(),s.createBlock("span",S,"0")):s.createCommentVNode("",!0),s.createTextVNode(" "+s.toDisplayString(i.weekNumbers[a]),1)])])):s.createCommentVNode("",!0),(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(t,(t=>(s.openBlock(),s.createBlock("td",{key:t.day+""+t.month,class:{"p-datepicker-other-month":t.otherMonth,"p-datepicker-today":t.today}},[s.withDirectives(s.createVNode("span",{class:{"p-highlight":o.isSelected(t),"p-disabled":!t.selectable},onClick:e=>o.onDateSelect(e,t),draggable:"false",onKeydown:e=>o.onDateCellKeydown(e,t,r)},[s.renderSlot(e.$slots,"date",{date:t},(()=>[s.createTextVNode(s.toDisplayString(t.day),1)]))],42,["onClick","onKeydown"]),[[c]])],2)))),128))])))),128))])])])):s.createCommentVNode("",!0)])))),128))]),"month"===n.view?(s.openBlock(),s.createBlock("div",x,[(s.openBlock(!0),s.createBlock(s.Fragment,null,s.renderList(o.monthPickerValues,((e,t)=>s.withDirectives((s.openBlock(),s.createBlock("span",{key:e,onClick:e=>o.onMonthSelect(e,t),onKeydown:e=>o.onMonthCellKeydown(e,t),class:["p-monthpicker-month",{"p-highlight":o.isMonthSelected(t)}]},[s.createTextVNode(s.toDisplayString(e),1)],42,["onClick","onKeydown"])),[[c]]))),128))])):s.createCommentVNode("",!0)],64)),n.showTime||n.timeOnly?(s.openBlock(),s.createBlock("div",C,[s.createVNode("div",B,[s.withDirectives(s.createVNode("button",{class:"p-link",onMousedown:t[7]||(t[7]=e=>o.onTimePickerElementMouseDown(e,0,1)),onMouseup:t[8]||(t[8]=e=>o.onTimePickerElementMouseUp(e)),onKeydown:[t[9]||(t[9]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),t[11]||(t[11]=s.withKeys((e=>o.onTimePickerElementMouseDown(e,0,1)),["enter"]))],onMouseleave:t[10]||(t[10]=e=>o.onTimePickerElementMouseLeave()),onKeyup:t[12]||(t[12]=s.withKeys((e=>o.onTimePickerElementMouseUp(e)),["enter"])),type:"button"},[T],544),[[c]]),s.createVNode("span",null,s.toDisplayString(o.formattedCurrentHour),1),s.withDirectives(s.createVNode("button",{class:"p-link",onMousedown:t[13]||(t[13]=e=>o.onTimePickerElementMouseDown(e,0,-1)),onMouseup:t[14]||(t[14]=e=>o.onTimePickerElementMouseUp(e)),onKeydown:[t[15]||(t[15]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),t[17]||(t[17]=s.withKeys((e=>o.onTimePickerElementMouseDown(e,0,-1)),["enter"]))],onMouseleave:t[16]||(t[16]=e=>o.onTimePickerElementMouseLeave()),onKeyup:t[18]||(t[18]=s.withKeys((e=>o.onTimePickerElementMouseUp(e)),["enter"])),type:"button"},[V],544),[[c]])]),s.createVNode("div",N,[s.createVNode("span",null,s.toDisplayString(n.timeSeparator),1)]),s.createVNode("div",H,[s.withDirectives(s.createVNode("button",{class:"p-link",onMousedown:t[19]||(t[19]=e=>o.onTimePickerElementMouseDown(e,1,1)),onMouseup:t[20]||(t[20]=e=>o.onTimePickerElementMouseUp(e)),onKeydown:[t[21]||(t[21]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),t[23]||(t[23]=s.withKeys((e=>o.onTimePickerElementMouseDown(e,1,1)),["enter"]))],disabled:e.$attrs.disabled,onMouseleave:t[22]||(t[22]=e=>o.onTimePickerElementMouseLeave()),onKeyup:t[24]||(t[24]=s.withKeys((e=>o.onTimePickerElementMouseUp(e)),["enter"])),type:"button"},[E],40,["disabled"]),[[c]]),s.createVNode("span",null,s.toDisplayString(o.formattedCurrentMinute),1),s.withDirectives(s.createVNode("button",{class:"p-link",onMousedown:t[25]||(t[25]=e=>o.onTimePickerElementMouseDown(e,1,-1)),onMouseup:t[26]||(t[26]=e=>o.onTimePickerElementMouseUp(e)),onKeydown:[t[27]||(t[27]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),t[29]||(t[29]=s.withKeys((e=>o.onTimePickerElementMouseDown(e,1,-1)),["enter"]))],disabled:e.$attrs.disabled,onMouseleave:t[28]||(t[28]=e=>o.onTimePickerElementMouseLeave()),onKeyup:t[30]||(t[30]=s.withKeys((e=>o.onTimePickerElementMouseUp(e)),["enter"])),type:"button"},[F],40,["disabled"]),[[c]])]),n.showSeconds?(s.openBlock(),s.createBlock("div",I,[s.createVNode("span",null,s.toDisplayString(n.timeSeparator),1)])):s.createCommentVNode("",!0),n.showSeconds?(s.openBlock(),s.createBlock("div",L,[s.withDirectives(s.createVNode("button",{class:"p-link",onMousedown:t[31]||(t[31]=e=>o.onTimePickerElementMouseDown(e,2,1)),onMouseup:t[32]||(t[32]=e=>o.onTimePickerElementMouseUp(e)),onKeydown:[t[33]||(t[33]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),t[35]||(t[35]=s.withKeys((e=>o.onTimePickerElementMouseDown(e,2,1)),["enter"]))],disabled:e.$attrs.disabled,onMouseleave:t[34]||(t[34]=e=>o.onTimePickerElementMouseLeave()),onKeyup:t[36]||(t[36]=s.withKeys((e=>o.onTimePickerElementMouseUp(e)),["enter"])),type:"button"},[$],40,["disabled"]),[[c]]),s.createVNode("span",null,s.toDisplayString(o.formattedCurrentSecond),1),s.withDirectives(s.createVNode("button",{class:"p-link",onMousedown:t[37]||(t[37]=e=>o.onTimePickerElementMouseDown(e,2,-1)),onMouseup:t[38]||(t[38]=e=>o.onTimePickerElementMouseUp(e)),onKeydown:[t[39]||(t[39]=(...e)=>o.onContainerButtonKeydown&&o.onContainerButtonKeydown(...e)),t[41]||(t[41]=s.withKeys((e=>o.onTimePickerElementMouseDown(e,2,-1)),["enter"]))],disabled:e.$attrs.disabled,onMouseleave:t[40]||(t[40]=e=>o.onTimePickerElementMouseLeave()),onKeyup:t[42]||(t[42]=s.withKeys((e=>o.onTimePickerElementMouseUp(e)),["enter"])),type:"button"},[P],40,["disabled"]),[[c]])])):s.createCommentVNode("",!0),"12"==n.hourFormat?(s.openBlock(),s.createBlock("div",K,[s.createVNode("span",null,s.toDisplayString(n.timeSeparator),1)])):s.createCommentVNode("",!0),"12"==n.hourFormat?(s.openBlock(),s.createBlock("div",Y,[s.withDirectives(s.createVNode("button",{class:"p-link",onClick:t[43]||(t[43]=e=>o.toggleAMPM(e)),type:"button",disabled:e.$attrs.disabled},[O],8,["disabled"]),[[c]]),s.createVNode("span",null,s.toDisplayString(a.pm?"PM":"AM"),1),s.withDirectives(s.createVNode("button",{class:"p-link",onClick:t[44]||(t[44]=e=>o.toggleAMPM(e)),type:"button",disabled:e.$attrs.disabled},[A],8,["disabled"]),[[c]])])):s.createCommentVNode("",!0)])):s.createCommentVNode("",!0),n.showButtonBar?(s.openBlock(),s.createBlock("div",U,[s.createVNode(l,{type:"button",label:o.todayLabel,onClick:t[45]||(t[45]=e=>o.onTodayButtonClick(e)),class:"p-button-text",onKeydown:o.onContainerButtonKeydown},null,8,["label","onKeydown"]),s.createVNode(l,{type:"button",label:o.clearLabel,onClick:t[46]||(t[46]=e=>o.onClearButtonClick(e)),class:"p-button-text",onKeydown:o.onContainerButtonKeydown},null,8,["label","onKeydown"])])):s.createCommentVNode("",!0),s.renderSlot(e.$slots,"footer")],10,["role"])):s.createCommentVNode("",!0)])),_:3},8,["onAfterEnter","onAfterLeave","onLeave"])],8,["to","disabled"]))],6)},d}(primevue.utils,primevue.overlayeventbus,primevue.inputtext,primevue.button,primevue.ripple,Vue);');
				break;

		}


	}

	/**
	 * A simple PHP function that calculates the percentage of a given number.
	 *
	 * @param   int  $number   The number you want a percentage of.
	 * @param   numeric  $percent  The percentage that you want to calculate.
	 *
	 * @return int The final result.
	 * @since 1.0
	 */

	public static function getPercentOfNumber(int $number, $percent)
	{
		return ($percent / 100) * $number;
	}


	function hex2rgba($color, $opacity = false)
	{

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if (empty($color))
			return $default;

		//Sanitize $color if "#" is provided
		if ($color[0] == '#')
		{
			$color = substr($color, 1);
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6)
		{
			$hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
		}
		elseif (strlen($color) == 3)
		{
			$hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
		}
		else
		{
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map('hexdec', $hex);

		//Check if opacity is set(rgba or rgb)
		if ($opacity)
		{
			if (abs($opacity) > 1)
				$opacity = 1.0;
			$output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
		}
		else
		{
			$output = 'rgb(' . implode(",", $rgb) . ')';
		}

		//Return rgb(a) color string
		return $output;
	}


}

