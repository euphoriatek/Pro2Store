<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
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
	 * @since 1.6
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
		$query->where($db->quoteName('field_id') . ' = ' . $db->quote($fieldid), 'AND');
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
			case "inputswitch":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputswitch=function(e){"use strict";var t={name:"InputSwitch",inheritAttrs:!1,emits:["update:modelValue","click","change"],props:{modelValue:Boolean,class:null,style:null},data:()=>({focused:!1}),methods:{onClick(e){this.$attrs.disabled||(this.$emit("click",e),this.$emit("update:modelValue",!this.modelValue),this.$emit("change",e),this.$refs.input.focus()),e.preventDefault()},onFocus(){this.focused=!0},onBlur(){this.focused=!1}},computed:{containerClass(){return["p-inputswitch p-component",this.class,{"p-inputswitch-checked":this.modelValue,"p-disabled":this.$attrs.disabled,"p-focus":this.focused}]}}};const i={class:"p-hidden-accessible"},n=e.createVNode("span",{class:"p-inputswitch-slider"},null,-1);return function(e,t){void 0===t&&(t={});var i=t.insertAt;if(e&&"undefined"!=typeof document){var n=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===i&&n.firstChild?n.insertBefore(s,n.firstChild):n.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}(\'.p-inputswitch {    position: relative;    display: inline-block;}.p-inputswitch-slider {    position: absolute;    cursor: pointer;    top: 0;    left: 0;    right: 0;    bottom: 0;}.p-inputswitch-slider:before {    position: absolute;    content: "";    top: 50%;}\'),t.render=function(t,s,o,c,l,d){return e.openBlock(),e.createBlock("div",{class:d.containerClass,onClick:s[4]||(s[4]=e=>d.onClick(e)),style:o.style},[e.createVNode("div",i,[e.createVNode("input",e.mergeProps({ref:"input",type:"checkbox",checked:o.modelValue},t.$attrs,{onFocus:s[1]||(s[1]=e=>d.onFocus(e)),onBlur:s[2]||(s[2]=e=>d.onBlur(e)),onKeydown:s[3]||(s[3]=e.withKeys(e.withModifiers((e=>d.onClick(e)),["prevent"]),["enter"])),role:"switch","aria-checked":o.modelValue}),null,16,["checked","aria-checked"])]),n],6)},t}(Vue);');
				break;
			case "chips":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.chips=function(e){"use strict";var t={name:"Chips",inheritAttrs:!1,emits:["update:modelValue","add","remove"],props:{modelValue:{type:Array,default:null},max:{type:Number,default:null},separator:{type:String,default:null},addOnBlur:{type:Boolean,default:null},allowDuplicate:{type:Boolean,default:!0},class:null,style:null},data:()=>({inputValue:null,focused:!1}),methods:{onWrapperClick(){this.$refs.input.focus()},onInput(e){this.inputValue=e.target.value},onFocus(){this.focused=!0},onBlur(e){this.focused=!1,this.addOnBlur&&this.addItem(e,e.target.value,!1)},onKeyDown(e){const t=e.target.value;switch(e.which){case 8:0===t.length&&this.modelValue&&this.modelValue.length>0&&this.removeItem(e,this.modelValue.length-1);break;case 13:t&&t.trim().length&&!this.maxedOut&&this.addItem(e,t,!0);break;default:this.separator&&","===this.separator&&188===e.which&&this.addItem(e,t,!0)}},onPaste(e){if(this.separator){let t=(e.clipboardData||window.clipboardData).getData("Text");if(t){let n=this.modelValue||[],i=t.split(this.separator);i=i.filter((e=>this.allowDuplicate||-1===n.indexOf(e))),n=[...n,...i],this.updateModel(e,n,!0)}}},updateModel(e,t,n){this.$emit("update:modelValue",t),this.$emit("add",{originalEvent:e,value:t}),this.$refs.input.value="",this.inputValue="",n&&e.preventDefault()},addItem(e,t,n){if(t&&t.trim().length){let i=this.modelValue?[...this.modelValue]:[];(this.allowDuplicate||-1===i.indexOf(t))&&(i.push(t),this.updateModel(e,i,n))}},removeItem(e,t){if(this.$attrs.disabled)return;let n=[...this.modelValue];const i=n.splice(t,1);this.$emit("update:modelValue",n),this.$emit("remove",{originalEvent:e,value:i})}},computed:{maxedOut(){return this.max&&this.modelValue&&this.max===this.modelValue.length},containerClass(){return["p-chips p-component p-inputwrapper",this.class,{"p-inputwrapper-filled":this.modelValue&&this.modelValue.length||this.inputValue&&this.inputValue.length,"p-inputwrapper-focus":this.focused}]}}};const n={class:"p-chips-token-label"},i={class:"p-chips-input-token"};return function(e,t){void 0===t&&(t={});var n=t.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],l=document.createElement("style");l.type="text/css","top"===n&&i.firstChild?i.insertBefore(l,i.firstChild):i.appendChild(l),l.styleSheet?l.styleSheet.cssText=e:l.appendChild(document.createTextNode(e))}}(".p-chips {    display: -webkit-inline-box;    display: -ms-inline-flexbox;    display: inline-flex;}.p-chips-multiple-container {    margin: 0;    padding: 0;    list-style-type: none;    cursor: text;    overflow: hidden;    display: -webkit-box;    display: -ms-flexbox;    display: flex;    -webkit-box-align: center;        -ms-flex-align: center;            align-items: center;    -ms-flex-wrap: wrap;        flex-wrap: wrap;}.p-chips-token {    cursor: default;    display: -webkit-inline-box;    display: -ms-inline-flexbox;    display: inline-flex;    -webkit-box-align: center;        -ms-flex-align: center;            align-items: center;    -webkit-box-flex: 0;        -ms-flex: 0 0 auto;            flex: 0 0 auto;}.p-chips-input-token {    -webkit-box-flex: 1;        -ms-flex: 1 1 auto;            flex: 1 1 auto;    display: -webkit-inline-box;    display: -ms-inline-flexbox;    display: inline-flex;}.p-chips-token-icon {    cursor: pointer;}.p-chips-input-token input {    border: 0 none;    outline: 0 none;    background-color: transparent;    margin: 0;    padding: 0;    -webkit-box-shadow: none;            box-shadow: none;    border-radius: 0;    width: 100%;}.p-fluid .p-chips {    display: -webkit-box;    display: -ms-flexbox;    display: flex;}"),t.render=function(t,l,a,s,o,p){return e.openBlock(),e.createBlock("div",{class:p.containerClass,style:a.style},[e.createVNode("ul",{class:["p-inputtext p-chips-multiple-container",{"p-disabled":t.$attrs.disabled,"p-focus":o.focused}],onClick:l[6]||(l[6]=e=>p.onWrapperClick())},[(e.openBlock(!0),e.createBlock(e.Fragment,null,e.renderList(a.modelValue,((i,l)=>(e.openBlock(),e.createBlock("li",{key:`${l}_${i}`,class:"p-chips-token"},[e.renderSlot(t.$slots,"chip",{value:i},(()=>[e.createVNode("span",n,e.toDisplayString(i),1)])),e.createVNode("span",{class:"p-chips-token-icon pi pi-times-circle",onClick:e=>p.removeItem(e,l)},null,8,["onClick"])])))),128)),e.createVNode("li",i,[e.createVNode("input",e.mergeProps({ref:"input",type:"text"},t.$attrs,{onFocus:l[1]||(l[1]=(...e)=>p.onFocus&&p.onFocus(...e)),onBlur:l[2]||(l[2]=e=>p.onBlur(e)),onInput:l[3]||(l[3]=(...e)=>p.onInput&&p.onInput(...e)),onKeydown:l[4]||(l[4]=e=>p.onKeyDown(e)),onPaste:l[5]||(l[5]=e=>p.onPaste(e)),disabled:t.$attrs.disabled||p.maxedOut}),null,16,["disabled"])])],2)],6)},t}(Vue);');
				break;
			case "chip":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.chip=function(e){"use strict";var n={name:"Chip",emits:["remove"],props:{label:{type:String,default:null},icon:{type:String,default:null},image:{type:String,default:null},removable:{type:Boolean,default:!1},removeIcon:{type:String,default:"pi pi-times-circle"}},data:()=>({visible:!0}),methods:{close(e){this.visible=!1,this.$emit("remove",e)}},computed:{containerClass(){return["p-chip p-component",{"p-chip-image":null!=this.image}]},iconClass(){return["p-chip-icon",this.icon]},removeIconClass(){return["p-chip-remove-icon",this.removeIcon]}}};const t={key:2,class:"p-chip-text"};return function(e,n){void 0===n&&(n={});var t=n.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],l=document.createElement("style");l.type="text/css","top"===t&&i.firstChild?i.insertBefore(l,i.firstChild):i.appendChild(l),l.styleSheet?l.styleSheet.cssText=e:l.appendChild(document.createTextNode(e))}}(".p-chip {    display: -webkit-inline-box;    display: -ms-inline-flexbox;    display: inline-flex;    -webkit-box-align: center;        -ms-flex-align: center;            align-items: center;}.p-chip-text {    line-height: 1.5;}.p-chip-icon.pi {    line-height: 1.5;}.p-chip-remove-icon {    line-height: 1.5;    cursor: pointer;}.p-chip img {    border-radius: 50%;}"),n.render=function(n,i,l,o,c,s){return c.visible?(e.openBlock(),e.createBlock("div",{key:0,class:s.containerClass},[e.renderSlot(n.$slots,"default",{},(()=>[l.image?(e.openBlock(),e.createBlock("img",{key:0,src:l.image},null,8,["src"])):l.icon?(e.openBlock(),e.createBlock("span",{key:1,class:s.iconClass},null,2)):e.createCommentVNode("",!0),l.label?(e.openBlock(),e.createBlock("div",t,e.toDisplayString(l.label),1)):e.createCommentVNode("",!0)])),l.removable?(e.openBlock(),e.createBlock("span",{key:0,tabindex:"0",class:s.removeIconClass,onClick:i[1]||(i[1]=(...e)=>s.close&&s.close(...e)),onKeydown:i[2]||(i[2]=e.withKeys(((...e)=>s.close&&s.close(...e)),["enter"]))},null,34)):e.createCommentVNode("",!0)],2)):e.createCommentVNode("",!0)},n}(Vue);');
				break;
			case "inputnumber":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputnumber=function(t,e,n){"use strict";function i(t){return t&&"object"==typeof t&&"default"in t?t:{default:t}}var s=i(t),r=i(e),u={name:"InputNumber",inheritAttrs:!1,emits:["update:modelValue","input"],props:{modelValue:{type:Number,default:null},format:{type:Boolean,default:!0},showButtons:{type:Boolean,default:!1},buttonLayout:{type:String,default:"stacked"},incrementButtonClass:{type:String,default:null},decrementButtonClass:{type:String,default:null},incrementButtonIcon:{type:String,default:"pi pi-angle-up"},decrementButtonIcon:{type:String,default:"pi pi-angle-down"},locale:{type:String,default:void 0},localeMatcher:{type:String,default:void 0},mode:{type:String,default:"decimal"},prefix:{type:String,default:null},suffix:{type:String,default:null},currency:{type:String,default:void 0},currencyDisplay:{type:String,default:void 0},useGrouping:{type:Boolean,default:!0},minFractionDigits:{type:Number,default:void 0},maxFractionDigits:{type:Number,default:void 0},min:{type:Number,default:null},max:{type:Number,default:null},step:{type:Number,default:1},style:null,class:null,inputStyle:null,inputClass:null},numberFormat:null,_numeral:null,_decimal:null,_group:null,_minusSign:null,_currency:null,_suffix:null,_prefix:null,_index:null,groupChar:"",isSpecialChar:null,prefixChar:null,suffixChar:null,timer:null,data:()=>({focused:!1}),watch:{locale(t,e){this.updateConstructParser(t,e)},localeMatcher(t,e){this.updateConstructParser(t,e)},mode(t,e){this.updateConstructParser(t,e)},currency(t,e){this.updateConstructParser(t,e)},currencyDisplay(t,e){this.updateConstructParser(t,e)},useGrouping(t,e){this.updateConstructParser(t,e)},minFractionDigits(t,e){this.updateConstructParser(t,e)},maxFractionDigits(t,e){this.updateConstructParser(t,e)},suffix(t,e){this.updateConstructParser(t,e)},prefix(t,e){this.updateConstructParser(t,e)}},created(){this.constructParser()},methods:{getOptions(){return{localeMatcher:this.localeMatcher,style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay,useGrouping:this.useGrouping,minimumFractionDigits:this.minFractionDigits,maximumFractionDigits:this.maxFractionDigits}},constructParser(){this.numberFormat=new Intl.NumberFormat(this.locale,this.getOptions());const t=[...new Intl.NumberFormat(this.locale,{useGrouping:!1}).format(9876543210)].reverse(),e=new Map(t.map(((t,e)=>[t,e])));this._numeral=new RegExp(`[${t.join("")}]`,"g"),this._decimal=this.getDecimalExpression(),this._group=this.getGroupingExpression(),this._minusSign=this.getMinusSignExpression(),this._currency=this.getCurrencyExpression(),this._suffix=this.getSuffixExpression(),this._prefix=this.getPrefixExpression(),this._index=t=>e.get(t)},updateConstructParser(t,e){t!==e&&this.constructParser()},escapeRegExp:t=>t.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),getDecimalExpression(){const t=new Intl.NumberFormat(this.locale,{useGrouping:!1});return new RegExp(`[${t.format(1.1).trim().replace(this._numeral,"")}]`,"g")},getGroupingExpression(){const t=new Intl.NumberFormat(this.locale,{useGrouping:!0});return this.groupChar=t.format(1e6).trim().replace(this._numeral,"").charAt(0),new RegExp(`[${this.groupChar}]`,"g")},getMinusSignExpression(){const t=new Intl.NumberFormat(this.locale,{useGrouping:!1});return new RegExp(`[${t.format(-1).trim().replace(this._numeral,"")}]`,"g")},getCurrencyExpression(){if(this.currency){const t=new Intl.NumberFormat(this.locale,{style:"currency",currency:this.currency,currencyDisplay:this.currencyDisplay});return new RegExp(`[${t.format(1).replace(/\s/g,"").replace(this._numeral,"").replace(this._decimal,"").replace(this._group,"")}]`,"g")}return new RegExp("[]","g")},getPrefixExpression(){if(this.prefix)this.prefixChar=this.prefix;else{const t=new Intl.NumberFormat(this.locale,{style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay});this.prefixChar=t.format(1).split("1")[0]}return new RegExp(`${this.escapeRegExp(this.prefixChar||"")}`,"g")},getSuffixExpression(){if(this.suffix)this.suffixChar=this.suffix;else{const t=new Intl.NumberFormat(this.locale,{style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay,minimumFractionDigits:0,maximumFractionDigits:0});this.suffixChar=t.format(1).split("1")[1]}return new RegExp(`${this.escapeRegExp(this.suffixChar||"")}`,"g")},formatValue(t){if(null!=t){if("-"===t)return t;if(this.format){let e=new Intl.NumberFormat(this.locale,this.getOptions()).format(t);return this.prefix&&(e=this.prefix+e),this.suffix&&(e+=this.suffix),e}return t.toString()}return""},parseValue(t){let e=t.replace(this._suffix,"").replace(this._prefix,"").trim().replace(/\s/g,"").replace(this._currency,"").replace(this._group,"").replace(this._minusSign,"-").replace(this._decimal,".").replace(this._numeral,this._index);if(e){if("-"===e)return e;let t=+e;return isNaN(t)?null:t}return null},repeat(t,e,n){let i=e||500;this.clearTimer(),this.timer=setTimeout((()=>{this.repeat(t,40,n)}),i),this.spin(t,n)},spin(t,e){if(this.$refs.input){let n=this.step*e,i=this.parseValue(this.$refs.input.$el.value)||0,s=this.validateValue(i+n);this.updateInput(s,null,"spin"),this.updateModel(t,s),this.handleOnInput(t,i,s)}},onUpButtonMouseDown(t){this.$attrs.disabled||(this.$refs.input.$el.focus(),this.repeat(t,null,1),t.preventDefault())},onUpButtonMouseUp(){this.$attrs.disabled||this.clearTimer()},onUpButtonMouseLeave(){this.$attrs.disabled||this.clearTimer()},onUpButtonKeyUp(){this.$attrs.disabled||this.clearTimer()},onUpButtonKeyDown(t){32!==t.keyCode&&13!==t.keyCode||this.repeat(t,null,1)},onDownButtonMouseDown(t){this.$attrs.disabled||(this.$refs.input.$el.focus(),this.repeat(t,null,-1),t.preventDefault())},onDownButtonMouseUp(){this.$attrs.disabled||this.clearTimer()},onDownButtonMouseLeave(){this.$attrs.disabled||this.clearTimer()},onDownButtonKeyUp(){this.$attrs.disabled||this.clearTimer()},onDownButtonKeyDown(t){32!==t.keyCode&&13!==t.keyCode||this.repeat(t,null,-1)},onUserInput(){this.isSpecialChar&&(this.$refs.input.$el.value=this.lastValue),this.isSpecialChar=!1},onInputKeyDown(t){if(this.lastValue=t.target.value,t.shiftKey||t.altKey)return void(this.isSpecialChar=!0);let e=t.target.selectionStart,n=t.target.selectionEnd,i=t.target.value,s=null;switch(t.altKey&&t.preventDefault(),t.which){case 38:this.spin(t,1),t.preventDefault();break;case 40:this.spin(t,-1),t.preventDefault();break;case 37:this.isNumeralChar(i.charAt(e-1))||t.preventDefault();break;case 39:this.isNumeralChar(i.charAt(e))||t.preventDefault();break;case 13:s=this.validateValue(this.parseValue(i)),this.$refs.input.$el.value=this.formatValue(s),this.$refs.input.$el.setAttribute("aria-valuenow",s),this.updateModel(t,s);break;case 8:if(t.preventDefault(),e===n){let n=i.charAt(e-1),r=i.search(this._decimal);this._decimal.lastIndex=0,this.isNumeralChar(n)&&(this._group.test(n)?(this._group.lastIndex=0,s=i.slice(0,e-2)+i.slice(e-1)):this._decimal.test(n)?(this._decimal.lastIndex=0,this.$refs.input.$el.setSelectionRange(e-1,e-1)):r>0&&e>r?s=i.slice(0,e-1)+"0"+i.slice(e):r>0&&1===r?(s=i.slice(0,e-1)+"0"+i.slice(e),s=this.parseValue(s)>0?s:""):s=i.slice(0,e-1)+i.slice(e)),this.updateValue(t,s,null,"delete-single")}else s=this.deleteRange(i,e,n),this.updateValue(t,s,null,"delete-range");break;case 46:if(t.preventDefault(),e===n){let n=i.charAt(e),r=i.search(this._decimal);this._decimal.lastIndex=0,this.isNumeralChar(n)&&(this._group.test(n)?(this._group.lastIndex=0,s=i.slice(0,e)+i.slice(e+2)):this._decimal.test(n)?(this._decimal.lastIndex=0,this.$refs.input.$el.setSelectionRange(e+1,e+1)):r>0&&e>r?s=i.slice(0,e)+"0"+i.slice(e+1):r>0&&1===r?(s=i.slice(0,e)+"0"+i.slice(e+1),s=this.parseValue(s)>0?s:""):s=i.slice(0,e)+i.slice(e+1)),this.updateValue(t,s,null,"delete-back-single")}else s=this.deleteRange(i,e,n),this.updateValue(t,s,null,"delete-range")}},onInputKeyPress(t){t.preventDefault();let e=t.which||t.keyCode,n=String.fromCharCode(e);const i=this.isDecimalSign(n),s=this.isMinusSign(n);(48<=e&&e<=57||s||i)&&this.insert(t,n,{isDecimalSign:i,isMinusSign:s})},onPaste(t){t.preventDefault();let e=(t.clipboardData||window.clipboardData).getData("Text");if(e){let n=this.parseValue(e);null!=n&&this.insert(t,n.toString())}},allowMinusSign(){return null===this.min||this.min<0},isMinusSign(t){return!!this._minusSign.test(t)&&(this._minusSign.lastIndex=0,!0)},isDecimalSign(t){return!!this._decimal.test(t)&&(this._decimal.lastIndex=0,!0)},insert(t,e,n={isDecimalSign:!1,isMinusSign:!1}){const i=e.search(this._minusSign);if(this._minusSign.lastIndex=0,!this.allowMinusSign()&&-1!==i)return;const s=this.$refs.input.$el.selectionStart,r=this.$refs.input.$el.selectionEnd;let u=this.$refs.input.$el.value.trim();const l=u.search(this._decimal);this._decimal.lastIndex=0;const a=u.search(this._minusSign);let o;if(this._minusSign.lastIndex=0,n.isMinusSign)0===s&&(o=u,-1!==a&&0===r||(o=this.insertText(u,e,0,r)),this.updateValue(t,o,e,"insert"));else if(n.isDecimalSign)l>0&&s===l?this.updateValue(t,u,e,"insert"):l>s&&l<r&&(o=this.insertText(u,e,s,r),this.updateValue(t,o,e,"insert"));else{const n=this.numberFormat.resolvedOptions().maximumFractionDigits,i=s!==r?"range-insert":"insert";l>0&&s>l?s+e.length-(l+1)<=n&&(o=u.slice(0,s)+e+u.slice(s+e.length),this.updateValue(t,o,e,i)):(o=this.insertText(u,e,s,r),this.updateValue(t,o,e,i))}},insertText(t,e,n,i){if(2===e.split(".").length){const s=t.slice(n,i).search(this._decimal);return this._decimal.lastIndex=0,s>0?t.slice(0,n)+this.formatValue(e)+t.slice(i):t||this.formatValue(e)}return i-n===t.length?this.formatValue(e):0===n?e+t.slice(i):i===t.length?t.slice(0,n)+e:t.slice(0,n)+e+t.slice(i)},deleteRange(t,e,n){let i;return i=n-e===t.length?"":0===e?t.slice(n):n===t.length?t.slice(0,e):t.slice(0,e)+t.slice(n),i},initCursor(){let t=this.$refs.input.$el.selectionStart,e=this.$refs.input.$el.value,n=e.length,i=null,s=e.charAt(t);if(this.isNumeralChar(s))return;let r=t-1;for(;r>=0;){if(s=e.charAt(r),this.isNumeralChar(s)){i=r;break}r--}if(null!==i)this.$refs.input.$el.setSelectionRange(i+1,i+1);else{for(r=t+1;r<n;){if(s=e.charAt(r),this.isNumeralChar(s)){i=r;break}r++}null!==i&&this.$refs.input.$el.setSelectionRange(i,i)}},onInputClick(){this.initCursor()},isNumeralChar(t){return!(1!==t.length||!(this._numeral.test(t)||this._decimal.test(t)||this._group.test(t)||this._minusSign.test(t)))&&(this.resetRegex(),!0)},resetRegex(){this._numeral.lastIndex=0,this._decimal.lastIndex=0,this._group.lastIndex=0,this._minusSign.lastIndex=0},updateValue(t,e,n,i){let s=this.$refs.input.$el.value,r=null;null!=e&&(r=this.parseValue(e),this.updateInput(r,n,i)),this.handleOnInput(t,s,r)},handleOnInput(t,e,n){this.isValueChanged(e,n)&&this.$emit("input",{originalEvent:t,value:n})},isValueChanged(t,e){if(null===e&&null!==t)return!0;if(null!=e){return e!==("string"==typeof t?this.parseValue(t):t)}return!1},validateValue(t){return null!=this.min&&t<this.min?this.min:null!=this.max&&t>this.max?this.max:"-"===t?null:t},updateInput(t,e,n){e=e||"";let i=this.$refs.input.$el.value,s=this.formatValue(t),r=i.length;if(0===r){this.$refs.input.$el.value=s,this.$refs.input.$el.setSelectionRange(0,0),this.initCursor();const t=(this.prefixChar||"").length+e.length;this.$refs.input.$el.setSelectionRange(t,t)}else{let t=this.$refs.input.$el.selectionStart,u=this.$refs.input.$el.selectionEnd;this.$refs.input.$el.value=s;let l=s.length;if("range-insert"===n){const n=this.parseValue((i||"").slice(0,t)),r=(null!==n?n.toString():"").split("").join(`(${this.groupChar})?`),l=new RegExp(r,"g");l.test(s);const a=e.split("").join(`(${this.groupChar})?`),o=new RegExp(a,"g");o.test(s.slice(l.lastIndex)),u=l.lastIndex+o.lastIndex,this.$refs.input.$el.setSelectionRange(u,u)}else if(l===r)"insert"===n||"delete-back-single"===n?this.$refs.input.$el.setSelectionRange(u+1,u+1):"delete-single"===n?this.$refs.input.$el.setSelectionRange(u-1,u-1):"delete-range"!==n&&"spin"!==n||this.$refs.input.$el.setSelectionRange(u,u);else if("delete-back-single"===n){let t=i.charAt(u-1),e=i.charAt(u),n=r-l,s=this._group.test(e);s&&1===n?u+=1:!s&&this.isNumeralChar(t)&&(u+=-1*n+1),this._group.lastIndex=0,this.$refs.input.$el.setSelectionRange(u,u)}else u+=l-r,this.$refs.input.$el.setSelectionRange(u,u)}this.$refs.input.$el.setAttribute("aria-valuenow",t)},updateModel(t,e){this.$emit("update:modelValue",e)},onInputFocus(){this.focused=!0},onInputBlur(t){this.focused=!1;let e=t.target,n=this.validateValue(this.parseValue(e.value));e.value=this.formatValue(n),e.setAttribute("aria-valuenow",n),this.updateModel(t,n)},clearTimer(){this.timer&&clearInterval(this.timer)}},computed:{containerClass(){return["p-inputnumber p-component p-inputwrapper",this.class,{"p-inputwrapper-filled":this.filled,"p-inputwrapper-focus":this.focused,"p-inputnumber-buttons-stacked":this.showButtons&&"stacked"===this.buttonLayout,"p-inputnumber-buttons-horizontal":this.showButtons&&"horizontal"===this.buttonLayout,"p-inputnumber-buttons-vertical":this.showButtons&&"vertical"===this.buttonLayout}]},upButtonClass(){return["p-inputnumber-button p-inputnumber-button-up",this.incrementButtonClass]},downButtonClass(){return["p-inputnumber-button p-inputnumber-button-down",this.decrementButtonClass]},filled(){return null!=this.modelValue&&this.modelValue.toString().length>0},upButtonListeners(){return{mousedown:t=>this.onUpButtonMouseDown(t),mouseup:t=>this.onUpButtonMouseUp(t),mouseleave:t=>this.onUpButtonMouseLeave(t),keydown:t=>this.onUpButtonKeyDown(t),keyup:t=>this.onUpButtonKeyUp(t)}},downButtonListeners(){return{mousedown:t=>this.onDownButtonMouseDown(t),mouseup:t=>this.onDownButtonMouseUp(t),mouseleave:t=>this.onDownButtonMouseLeave(t),keydown:t=>this.onDownButtonKeyDown(t),keyup:t=>this.onDownButtonKeyUp(t)}},formattedValue(){return this.formatValue(this.modelValue)}},components:{INInputText:s.default,INButton:r.default}};const l={key:0,class:"p-inputnumber-button-group"};return function(t,e){void 0===e&&(e={});var n=e.insertAt;if(t&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===n&&i.firstChild?i.insertBefore(s,i.firstChild):i.appendChild(s),s.styleSheet?s.styleSheet.cssText=t:s.appendChild(document.createTextNode(t))}}(".p-inputnumber {    display: -webkit-inline-box;    display: -ms-inline-flexbox;    display: inline-flex;}.p-inputnumber-button {    display: -webkit-box;    display: -ms-flexbox;    display: flex;    -webkit-box-align: center;        -ms-flex-align: center;            align-items: center;    -webkit-box-pack: center;        -ms-flex-pack: center;            justify-content: center;    -webkit-box-flex: 0;        -ms-flex: 0 0 auto;            flex: 0 0 auto;}.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button .p-button-label,.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button .p-button-label {    display: none;}.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button-up {    border-top-left-radius: 0;    border-bottom-left-radius: 0;    border-bottom-right-radius: 0;    padding: 0;}.p-inputnumber-buttons-stacked .p-inputnumber-input {    border-top-right-radius: 0;    border-bottom-right-radius: 0;}.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button-down {    border-top-left-radius: 0;    border-top-right-radius: 0;    border-bottom-left-radius: 0;    padding: 0;}.p-inputnumber-buttons-stacked .p-inputnumber-button-group {    display: -webkit-box;    display: -ms-flexbox;    display: flex;    -webkit-box-orient: vertical;    -webkit-box-direction: normal;        -ms-flex-direction: column;            flex-direction: column;}.p-inputnumber-buttons-stacked .p-inputnumber-button-group .p-button.p-inputnumber-button {    -webkit-box-flex: 1;        -ms-flex: 1 1 auto;            flex: 1 1 auto;}.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button-up {    -webkit-box-ordinal-group: 4;        -ms-flex-order: 3;            order: 3;    border-top-left-radius: 0;    border-bottom-left-radius: 0;}.p-inputnumber-buttons-horizontal .p-inputnumber-input {    -webkit-box-ordinal-group: 3;        -ms-flex-order: 2;            order: 2;    border-radius: 0;}.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button-down {    -webkit-box-ordinal-group: 2;        -ms-flex-order: 1;            order: 1;    border-top-right-radius: 0;    border-bottom-right-radius: 0;}.p-inputnumber-buttons-vertical {    -webkit-box-orient: vertical;    -webkit-box-direction: normal;        -ms-flex-direction: column;            flex-direction: column;}.p-inputnumber-buttons-vertical .p-button.p-inputnumber-button-up {    -webkit-box-ordinal-group: 2;        -ms-flex-order: 1;            order: 1;    border-bottom-left-radius: 0;    border-bottom-right-radius: 0;    width: 100%;}.p-inputnumber-buttons-vertical .p-inputnumber-input {    -webkit-box-ordinal-group: 3;        -ms-flex-order: 2;            order: 2;    border-radius: 0;    text-align: center;}.p-inputnumber-buttons-vertical .p-button.p-inputnumber-button-down {    -webkit-box-ordinal-group: 4;        -ms-flex-order: 3;            order: 3;    border-top-left-radius: 0;    border-top-right-radius: 0;    width: 100%;}.p-inputnumber-input {    -webkit-box-flex: 1;        -ms-flex: 1 1 auto;            flex: 1 1 auto;}.p-fluid .p-inputnumber {    width: 100%;}.p-fluid .p-inputnumber .p-inputnumber-input {    width: 1%;}.p-fluid .p-inputnumber-buttons-vertical .p-inputnumber-input {    width: 100%;}"),u.render=function(t,e,i,s,r,u){const a=n.resolveComponent("INInputText"),o=n.resolveComponent("INButton");return n.openBlock(),n.createBlock("span",{class:u.containerClass,style:i.style},[n.createVNode(a,n.mergeProps({ref:"input",class:["p-inputnumber-input",i.inputClass],style:i.inputStyle,value:u.formattedValue},t.$attrs,{"aria-valumin":i.min,"aria-valuemax":i.max,onInput:u.onUserInput,onKeydown:u.onInputKeyDown,onKeypress:u.onInputKeyPress,onPaste:u.onPaste,onClick:u.onInputClick,onFocus:u.onInputFocus,onBlur:u.onInputBlur}),null,16,["class","style","value","aria-valumin","aria-valuemax","onInput","onKeydown","onKeypress","onPaste","onClick","onFocus","onBlur"]),i.showButtons&&"stacked"===i.buttonLayout?(n.openBlock(),n.createBlock("span",l,[n.createVNode(o,n.mergeProps({class:u.upButtonClass,icon:i.incrementButtonIcon},n.toHandlers(u.upButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"]),n.createVNode(o,n.mergeProps({class:u.downButtonClass,icon:i.decrementButtonIcon},n.toHandlers(u.downButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"])])):n.createCommentVNode("",!0),i.showButtons&&"stacked"!==i.buttonLayout?(n.openBlock(),n.createBlock(o,n.mergeProps({key:1,class:u.upButtonClass,icon:i.incrementButtonIcon},n.toHandlers(u.upButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"])):n.createCommentVNode("",!0),i.showButtons&&"stacked"!==i.buttonLayout?(n.openBlock(),n.createBlock(o,n.mergeProps({key:2,class:u.downButtonClass,icon:i.decrementButtonIcon},n.toHandlers(u.downButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"])):n.createCommentVNode("",!0)],6)},u}(primevue.inputtext,primevue.button,Vue);');
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
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.dropdown=function(e,t,i,l,n){"use strict";function o(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var s=o(t),r={name:"Dropdown",emits:["update:modelValue","before-show","before-hide","show","hide","change","filter","focus","blur"],props:{modelValue:null,options:Array,optionLabel:null,optionValue:null,optionDisabled:null,optionGroupLabel:null,optionGroupChildren:null,scrollHeight:{type:String,default:"200px"},filter:Boolean,filterPlaceholder:String,filterLocale:String,filterMatchMode:{type:String,default:"contains"},filterFields:{type:Array,default:null},editable:Boolean,placeholder:String,disabled:Boolean,dataKey:null,showClear:Boolean,inputId:String,tabindex:String,ariaLabelledBy:null,appendTo:{type:String,default:"body"},emptyFilterMessage:{type:String,default:null},emptyMessage:{type:String,default:null},panelClass:null,loading:{type:Boolean,default:!1},loadingIcon:{type:String,default:"pi pi-spinner pi-spin"}},data:()=>({focused:!1,filterValue:null,overlayVisible:!1}),outsideClickListener:null,scrollHandler:null,resizeListener:null,searchTimeout:null,currentSearchChar:null,previousSearchChar:null,searchValue:null,overlay:null,itemsWrapper:null,beforeUnmount(){this.unbindOutsideClickListener(),this.unbindResizeListener(),this.scrollHandler&&(this.scrollHandler.destroy(),this.scrollHandler=null),this.itemsWrapper=null,this.overlay&&(e.ZIndexUtils.clear(this.overlay),this.overlay=null)},methods:{getOptionLabel(t){return this.optionLabel?e.ObjectUtils.resolveFieldData(t,this.optionLabel):t},getOptionValue(t){return this.optionValue?e.ObjectUtils.resolveFieldData(t,this.optionValue):t},getOptionRenderKey(t){return this.dataKey?e.ObjectUtils.resolveFieldData(t,this.dataKey):this.getOptionLabel(t)},isOptionDisabled(t){return!!this.optionDisabled&&e.ObjectUtils.resolveFieldData(t,this.optionDisabled)},getOptionGroupRenderKey(t){return e.ObjectUtils.resolveFieldData(t,this.optionGroupLabel)},getOptionGroupLabel(t){return e.ObjectUtils.resolveFieldData(t,this.optionGroupLabel)},getOptionGroupChildren(t){return e.ObjectUtils.resolveFieldData(t,this.optionGroupChildren)},getSelectedOption(){let e=this.getSelectedOptionIndex();return-1!==e?this.optionGroupLabel?this.getOptionGroupChildren(this.options[e.group])[e.option]:this.options[e]:null},getSelectedOptionIndex(){if(null!=this.modelValue&&this.options){if(!this.optionGroupLabel)return this.findOptionIndexInList(this.modelValue,this.options);for(let e=0;e<this.options.length;e++){let t=this.findOptionIndexInList(this.modelValue,this.getOptionGroupChildren(this.options[e]));if(-1!==t)return{group:e,option:t}}}return-1},findOptionIndexInList(t,i){for(let l=0;l<i.length;l++)if(e.ObjectUtils.equals(t,this.getOptionValue(i[l]),this.equalityKey))return l;return-1},isSelected(t){return e.ObjectUtils.equals(this.modelValue,this.getOptionValue(t),this.equalityKey)},show(){this.$emit("before-show"),this.overlayVisible=!0},hide(){this.$emit("before-hide"),this.overlayVisible=!1},onFocus(e){this.focused=!0,this.$emit("focus",e)},onBlur(e){this.focused=!1,this.$emit("blur",e)},onKeyDown(e){switch(e.which){case 40:this.onDownKey(e);break;case 38:this.onUpKey(e);break;case 32:this.overlayVisible||(this.show(),e.preventDefault());break;case 13:case 27:this.overlayVisible&&(this.hide(),e.preventDefault());break;case 9:this.hide();break;default:this.search(e)}},onFilterKeyDown(e){switch(e.which){case 40:this.onDownKey(e);break;case 38:this.onUpKey(e);break;case 13:case 27:this.overlayVisible=!1,e.preventDefault()}},onDownKey(e){if(this.visibleOptions)if(!this.overlayVisible&&e.altKey)this.show();else{let t=this.visibleOptions&&this.visibleOptions.length>0?this.findNextOption(this.getSelectedOptionIndex()):null;t&&this.updateModel(e,this.getOptionValue(t))}e.preventDefault()},onUpKey(e){if(this.visibleOptions){let t=this.findPrevOption(this.getSelectedOptionIndex());t&&this.updateModel(e,this.getOptionValue(t))}e.preventDefault()},findNextOption(e){if(this.optionGroupLabel){let t=-1===e?0:e.group,i=-1===e?-1:e.option,l=this.findNextOptionInList(this.getOptionGroupChildren(this.visibleOptions[t]),i);return l||(t+1!==this.visibleOptions.length?this.findNextOption({group:t+1,option:-1}):null)}return this.findNextOptionInList(this.visibleOptions,e)},findNextOptionInList(e,t){let i=t+1;if(i===e.length)return null;let l=e[i];return this.isOptionDisabled(l)?this.findNextOptionInList(i):l},findPrevOption(e){if(-1===e)return null;if(this.optionGroupLabel){let t=e.group,i=e.option,l=this.findPrevOptionInList(this.getOptionGroupChildren(this.visibleOptions[t]),i);return l||(t>0?this.findPrevOption({group:t-1,option:this.getOptionGroupChildren(this.visibleOptions[t-1]).length}):null)}return this.findPrevOptionInList(this.visibleOptions,e)},findPrevOptionInList(e,t){let i=t-1;if(i<0)return null;let l=e[i];return this.isOptionDisabled(l)?this.findPrevOption(i):l},onClearClick(e){this.updateModel(e,null)},onClick(t){this.disabled||this.loading||e.DomHandler.hasClass(t.target,"p-dropdown-clear-icon")||"INPUT"===t.target.tagName||this.overlay&&this.overlay.contains(t.target)||(this.overlayVisible?this.hide():this.show(),this.$refs.focusInput.focus())},onOptionSelect(e,t){let i=this.getOptionValue(t);this.updateModel(e,i),this.$refs.focusInput.focus(),setTimeout((()=>{this.hide()}),200)},onEditableInput(e){this.$emit("update:modelValue",e.target.value)},onOverlayEnter(t){e.ZIndexUtils.set("overlay",t,this.$primevue.config.zIndex.overlay),this.scrollValueInView(),this.alignOverlay(),this.bindOutsideClickListener(),this.bindScrollListener(),this.bindResizeListener(),this.filter&&this.$refs.filterInput.focus(),this.$emit("show")},onOverlayLeave(){this.unbindOutsideClickListener(),this.unbindScrollListener(),this.unbindResizeListener(),this.$emit("hide"),this.itemsWrapper=null,this.overlay=null},onOverlayAfterLeave(t){e.ZIndexUtils.clear(t)},alignOverlay(){this.appendDisabled?e.DomHandler.relativePosition(this.overlay,this.$el):(this.overlay.style.minWidth=e.DomHandler.getOuterWidth(this.$el)+"px",e.DomHandler.absolutePosition(this.overlay,this.$el))},updateModel(e,t){this.$emit("update:modelValue",t),this.$emit("change",{originalEvent:e,value:t})},bindOutsideClickListener(){this.outsideClickListener||(this.outsideClickListener=e=>{this.overlayVisible&&this.overlay&&!this.$el.contains(e.target)&&!this.overlay.contains(e.target)&&this.hide()},document.addEventListener("click",this.outsideClickListener))},unbindOutsideClickListener(){this.outsideClickListener&&(document.removeEventListener("click",this.outsideClickListener),this.outsideClickListener=null)},bindScrollListener(){this.scrollHandler||(this.scrollHandler=new e.ConnectedOverlayScrollHandler(this.$refs.container,(()=>{this.overlayVisible&&this.hide()}))),this.scrollHandler.bindScrollListener()},unbindScrollListener(){this.scrollHandler&&this.scrollHandler.unbindScrollListener()},bindResizeListener(){this.resizeListener||(this.resizeListener=()=>{this.overlayVisible&&!e.DomHandler.isAndroid()&&this.hide()},window.addEventListener("resize",this.resizeListener))},unbindResizeListener(){this.resizeListener&&(window.removeEventListener("resize",this.resizeListener),this.resizeListener=null)},search(e){if(!this.visibleOptions)return;this.searchTimeout&&clearTimeout(this.searchTimeout);const t=String.fromCharCode(e.keyCode);if(this.previousSearchChar=this.currentSearchChar,this.currentSearchChar=t,this.previousSearchChar===this.currentSearchChar?this.searchValue=this.currentSearchChar:this.searchValue=this.searchValue?this.searchValue+t:t,this.searchValue){let t=this.getSelectedOptionIndex(),i=this.optionGroupLabel?this.searchOptionInGroup(t):this.searchOption(++t);i&&this.updateModel(e,this.getOptionValue(i))}this.searchTimeout=setTimeout((()=>{this.searchValue=null}),250)},searchOption(e){let t;return this.searchValue&&(t=this.searchOptionInRange(e,this.visibleOptions.length),t||(t=this.searchOptionInRange(0,e))),t},searchOptionInRange(e,t){for(let i=e;i<t;i++){let e=this.visibleOptions[i];if(this.matchesSearchValue(e))return e}return null},searchOptionInGroup(e){let t=-1===e?{group:0,option:-1}:e;for(let e=t.group;e<this.visibleOptions.length;e++){let i=this.getOptionGroupChildren(this.visibleOptions[e]);for(let l=t.group===e?t.option+1:0;l<i.length;l++)if(this.matchesSearchValue(i[l]))return i[l]}for(let e=0;e<=t.group;e++){let i=this.getOptionGroupChildren(this.visibleOptions[e]);for(let l=0;l<(t.group===e?t.option:i.length);l++)if(this.matchesSearchValue(i[l]))return i[l]}return null},matchesSearchValue(e){return this.getOptionLabel(e).toLocaleLowerCase(this.filterLocale).startsWith(this.searchValue.toLocaleLowerCase(this.filterLocale))},onFilterChange(e){this.$emit("filter",{originalEvent:e,value:e.target.value}),this.overlayVisible&&this.alignOverlay()},overlayRef(e){this.overlay=e},itemsWrapperRef(e){this.itemsWrapper=e},scrollValueInView(){if(this.overlay){let t=e.DomHandler.findSingle(this.overlay,"li.p-highlight");t&&(this.itemsWrapper.scrollTop=t.offsetTop)}},onOverlayClick(e){s.default.emit("overlay-click",{originalEvent:e,target:this.$el})}},computed:{visibleOptions(){if(this.filterValue){if(this.optionGroupLabel){let e=[];for(let t of this.options){let l=i.FilterService.filter(this.getOptionGroupChildren(t),this.searchFields,this.filterValue,this.filterMatchMode,this.filterLocale);if(l&&l.length){let i={...t};i[this.optionGroupChildren]=l,e.push(i)}}return e}return i.FilterService.filter(this.options,this.searchFields,this.filterValue,"contains",this.filterLocale)}return this.options},containerClass(){return["p-dropdown p-component p-inputwrapper",{"p-disabled":this.disabled,"p-dropdown-clearable":this.showClear&&!this.disabled,"p-focus":this.focused,"p-inputwrapper-filled":this.modelValue,"p-inputwrapper-focus":this.focused||this.overlayVisible}]},labelClass(){return["p-dropdown-label p-inputtext",{"p-placeholder":this.label===this.placeholder,"p-dropdown-label-empty":!this.$slots.value&&("p-emptylabel"===this.label||0===this.label.length)}]},panelStyleClass(){return["p-dropdown-panel p-component",this.panelClass,{"p-input-filled":"filled"===this.$primevue.config.inputStyle,"p-ripple-disabled":!1===this.$primevue.config.ripple}]},label(){let e=this.getSelectedOption();return e?this.getOptionLabel(e):this.placeholder||"p-emptylabel"},editableInputValue(){let e=this.getSelectedOption();return e?this.getOptionLabel(e):this.modelValue},equalityKey(){return this.optionValue?null:this.dataKey},searchFields(){return this.filterFields||[this.optionLabel]},emptyFilterMessageText(){return this.emptyFilterMessage||this.$primevue.config.locale.emptyFilterMessage},emptyMessageText(){return this.emptyMessage||this.$primevue.config.locale.emptyMessage},appendDisabled(){return"self"===this.appendTo},appendTarget(){return this.appendDisabled?null:this.appendTo},dropdownIconClass(){return["p-dropdown-trigger-icon",this.loading?this.loadingIcon:"pi pi-chevron-down"]}},directives:{ripple:o(l).default}};const a={class:"p-hidden-accessible"},p={key:0,class:"p-dropdown-header"},d={class:"p-dropdown-filter-container"},h=n.createVNode("span",{class:"p-dropdown-filter-icon pi pi-search"},null,-1),u={class:"p-dropdown-items",role:"listbox"},c={class:"p-dropdown-item-group"},b={key:2,class:"p-dropdown-empty-message"},f={key:3,class:"p-dropdown-empty-message"};return function(e,t){void 0===t&&(t={});var i=t.insertAt;if(e&&"undefined"!=typeof document){var l=document.head||document.getElementsByTagName("head")[0],n=document.createElement("style");n.type="text/css","top"===i&&l.firstChild?l.insertBefore(n,l.firstChild):l.appendChild(n),n.styleSheet?n.styleSheet.cssText=e:n.appendChild(document.createTextNode(e))}}("\n.p-dropdown {\n    display: -webkit-inline-box;\n    display: -ms-inline-flexbox;\n    display: inline-flex;\n    cursor: pointer;\n    position: relative;\n    -webkit-user-select: none;\n       -moz-user-select: none;\n        -ms-user-select: none;\n            user-select: none;\n}\n.p-dropdown-clear-icon {\n    position: absolute;\n    top: 50%;\n    margin-top: -.5rem;\n}\n.p-dropdown-trigger {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -ms-flex-negative: 0;\n        flex-shrink: 0;\n}\n.p-dropdown-label {\n    display: block;\n    white-space: nowrap;\n    overflow: hidden;\n    -webkit-box-flex: 1;\n        -ms-flex: 1 1 auto;\n            flex: 1 1 auto;\n    width: 1%;\n    text-overflow: ellipsis;\n    cursor: pointer;\n}\n.p-dropdown-label-empty {\n    overflow: hidden;\n    visibility: hidden;\n}\ninput.p-dropdown-label  {\n    cursor: default;\n}\n.p-dropdown .p-dropdown-panel {\n    min-width: 100%;\n}\n.p-dropdown-panel {\n    position: absolute;\n    top: 0;\n    left: 0;\n}\n.p-dropdown-items-wrapper {\n    overflow: auto;\n}\n.p-dropdown-item {\n    cursor: pointer;\n    font-weight: normal;\n    white-space: nowrap;\n    position: relative;\n    overflow: hidden;\n}\n.p-dropdown-item-group {\n    cursor: auto;\n}\n.p-dropdown-items {\n    margin: 0;\n    padding: 0;\n    list-style-type: none;\n}\n.p-dropdown-filter {\n    width: 100%;\n}\n.p-dropdown-filter-container {\n    position: relative;\n}\n.p-dropdown-filter-icon {\n    position: absolute;\n    top: 50%;\n    margin-top: -.5rem;\n}\n.p-fluid .p-dropdown {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n}\n.p-fluid .p-dropdown .p-dropdown-label {\n    width: 1%;\n}\n"),r.render=function(e,t,i,l,o,s){const r=n.resolveDirective("ripple");return n.openBlock(),n.createBlock("div",{ref:"container",class:s.containerClass,onClick:t[12]||(t[12]=e=>s.onClick(e))},[n.createVNode("div",a,[n.createVNode("input",{ref:"focusInput",type:"text",id:i.inputId,readonly:"",disabled:i.disabled,onFocus:t[1]||(t[1]=(...e)=>s.onFocus&&s.onFocus(...e)),onBlur:t[2]||(t[2]=(...e)=>s.onBlur&&s.onBlur(...e)),onKeydown:t[3]||(t[3]=(...e)=>s.onKeyDown&&s.onKeyDown(...e)),tabindex:i.tabindex,"aria-haspopup":"true","aria-expanded":o.overlayVisible,"aria-labelledby":i.ariaLabelledBy},null,40,["id","disabled","tabindex","aria-expanded","aria-labelledby"])]),i.editable?(n.openBlock(),n.createBlock("input",{key:0,type:"text",class:"p-dropdown-label p-inputtext",disabled:i.disabled,onFocus:t[4]||(t[4]=(...e)=>s.onFocus&&s.onFocus(...e)),onBlur:t[5]||(t[5]=(...e)=>s.onBlur&&s.onBlur(...e)),placeholder:i.placeholder,value:s.editableInputValue,onInput:t[6]||(t[6]=(...e)=>s.onEditableInput&&s.onEditableInput(...e)),"aria-haspopup":"listbox","aria-expanded":o.overlayVisible},null,40,["disabled","placeholder","value","aria-expanded"])):n.createCommentVNode("",!0),i.editable?n.createCommentVNode("",!0):(n.openBlock(),n.createBlock("span",{key:1,class:s.labelClass},[n.renderSlot(e.$slots,"value",{value:i.modelValue,placeholder:i.placeholder},(()=>[n.createTextVNode(n.toDisplayString(s.label),1)]))],2)),i.showClear&&null!=i.modelValue?(n.openBlock(),n.createBlock("i",{key:2,class:"p-dropdown-clear-icon pi pi-times",onClick:t[7]||(t[7]=e=>s.onClearClick(e))})):n.createCommentVNode("",!0),n.createVNode("div",{class:"p-dropdown-trigger",role:"button","aria-haspopup":"listbox","aria-expanded":o.overlayVisible},[n.createVNode("span",{class:s.dropdownIconClass},null,2)],8,["aria-expanded"]),(n.openBlock(),n.createBlock(n.Teleport,{to:s.appendTarget,disabled:s.appendDisabled},[n.createVNode(n.Transition,{name:"p-connected-overlay",onEnter:s.onOverlayEnter,onLeave:s.onOverlayLeave,onAfterLeave:s.onOverlayAfterLeave},{default:n.withCtx((()=>[o.overlayVisible?(n.openBlock(),n.createBlock("div",{key:0,ref:s.overlayRef,class:s.panelStyleClass,onClick:t[11]||(t[11]=(...e)=>s.onOverlayClick&&s.onOverlayClick(...e))},[n.renderSlot(e.$slots,"header",{value:i.modelValue,options:s.visibleOptions}),i.filter?(n.openBlock(),n.createBlock("div",p,[n.createVNode("div",d,[n.withDirectives(n.createVNode("input",{type:"text",ref:"filterInput","onUpdate:modelValue":t[8]||(t[8]=e=>o.filterValue=e),autoComplete:"off",class:"p-dropdown-filter p-inputtext p-component",placeholder:i.filterPlaceholder,onKeydown:t[9]||(t[9]=(...e)=>s.onFilterKeyDown&&s.onFilterKeyDown(...e)),onInput:t[10]||(t[10]=(...e)=>s.onFilterChange&&s.onFilterChange(...e))},null,40,["placeholder"]),[[n.vModelText,o.filterValue]]),h])])):n.createCommentVNode("",!0),n.createVNode("div",{ref:s.itemsWrapperRef,class:"p-dropdown-items-wrapper",style:{"max-height":i.scrollHeight}},[n.createVNode("ul",u,[i.optionGroupLabel?(n.openBlock(!0),n.createBlock(n.Fragment,{key:1},n.renderList(s.visibleOptions,((t,i)=>(n.openBlock(),n.createBlock(n.Fragment,{key:s.getOptionGroupRenderKey(t)},[n.createVNode("li",c,[n.renderSlot(e.$slots,"optiongroup",{option:t,index:i},(()=>[n.createTextVNode(n.toDisplayString(s.getOptionGroupLabel(t)),1)]))]),(n.openBlock(!0),n.createBlock(n.Fragment,null,n.renderList(s.getOptionGroupChildren(t),((t,i)=>n.withDirectives((n.openBlock(),n.createBlock("li",{class:["p-dropdown-item",{"p-highlight":s.isSelected(t),"p-disabled":s.isOptionDisabled(t)}],key:s.getOptionRenderKey(t),onClick:e=>s.onOptionSelect(e,t),role:"option","aria-label":s.getOptionLabel(t),"aria-selected":s.isSelected(t)},[n.renderSlot(e.$slots,"option",{option:t,index:i},(()=>[n.createTextVNode(n.toDisplayString(s.getOptionLabel(t)),1)]))],10,["onClick","aria-label","aria-selected"])),[[r]]))),128))],64)))),128)):(n.openBlock(!0),n.createBlock(n.Fragment,{key:0},n.renderList(s.visibleOptions,((t,i)=>n.withDirectives((n.openBlock(),n.createBlock("li",{class:["p-dropdown-item",{"p-highlight":s.isSelected(t),"p-disabled":s.isOptionDisabled(t)}],key:s.getOptionRenderKey(t),onClick:e=>s.onOptionSelect(e,t),role:"option","aria-label":s.getOptionLabel(t),"aria-selected":s.isSelected(t)},[n.renderSlot(e.$slots,"option",{option:t,index:i},(()=>[n.createTextVNode(n.toDisplayString(s.getOptionLabel(t)),1)]))],10,["onClick","aria-label","aria-selected"])),[[r]]))),128)),o.filterValue&&(!s.visibleOptions||s.visibleOptions&&0===s.visibleOptions.length)?(n.openBlock(),n.createBlock("li",b,[n.renderSlot(e.$slots,"emptyfilter",{},(()=>[n.createTextVNode(n.toDisplayString(s.emptyFilterMessageText),1)]))])):!i.options||i.options&&0===i.options.length?(n.openBlock(),n.createBlock("li",f,[n.renderSlot(e.$slots,"empty",{},(()=>[n.createTextVNode(n.toDisplayString(s.emptyMessageText),1)]))])):n.createCommentVNode("",!0)])],4),n.renderSlot(e.$slots,"footer",{value:i.modelValue,options:s.visibleOptions})],2)):n.createCommentVNode("",!0)])),_:3},8,["onEnter","onLeave","onAfterLeave"])],8,["to","disabled"]))],2)},r}(primevue.utils,primevue.overlayeventbus,primevue.api,primevue.ripple,Vue);');
				break;
			case "speeddail":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.speeddial=function(e,t,i){"use strict";function n(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var s=n(e),l=n(t);class o{static innerWidth(e){let t=e.offsetWidth,i=getComputedStyle(e);return t+=parseFloat(i.paddingLeft)+parseFloat(i.paddingRight),t}static width(e){let t=e.offsetWidth,i=getComputedStyle(e);return t-=parseFloat(i.paddingLeft)+parseFloat(i.paddingRight),t}static getWindowScrollTop(){let e=document.documentElement;return(window.pageYOffset||e.scrollTop)-(e.clientTop||0)}static getWindowScrollLeft(){let e=document.documentElement;return(window.pageXOffset||e.scrollLeft)-(e.clientLeft||0)}static getOuterWidth(e,t){if(e){let i=e.offsetWidth;if(t){let t=getComputedStyle(e);i+=parseFloat(t.marginLeft)+parseFloat(t.marginRight)}return i}return 0}static getOuterHeight(e,t){if(e){let i=e.offsetHeight;if(t){let t=getComputedStyle(e);i+=parseFloat(t.marginTop)+parseFloat(t.marginBottom)}return i}return 0}static getClientHeight(e,t){if(e){let i=e.clientHeight;if(t){let t=getComputedStyle(e);i+=parseFloat(t.marginTop)+parseFloat(t.marginBottom)}return i}return 0}static getViewport(){let e=window,t=document,i=t.documentElement,n=t.getElementsByTagName("body")[0];return{width:e.innerWidth||i.clientWidth||n.clientWidth,height:e.innerHeight||i.clientHeight||n.clientHeight}}static getOffset(e){var t=e.getBoundingClientRect();return{top:t.top+(window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop||0),left:t.left+(window.pageXOffset||document.documentElement.scrollLeft||document.body.scrollLeft||0)}}static index(e){let t=e.parentNode.childNodes,i=0;for(var n=0;n<t.length;n++){if(t[n]===e)return i;1===t[n].nodeType&&i++}return-1}static addMultipleClasses(e,t){if(e.classList){let i=t.split(" ");for(let t=0;t<i.length;t++)e.classList.add(i[t])}else{let i=t.split(" ");for(let t=0;t<i.length;t++)e.className+=" "+i[t]}}static addClass(e,t){e.classList?e.classList.add(t):e.className+=" "+t}static removeClass(e,t){e.classList?e.classList.remove(t):e.className=e.className.replace(new RegExp("(^|\\b)"+t.split(" ").join("|")+"(\\b|$)","gi")," ")}static hasClass(e,t){return!!e&&(e.classList?e.classList.contains(t):new RegExp("(^| )"+t+"( |$)","gi").test(e.className))}static find(e,t){return e.querySelectorAll(t)}static findSingle(e,t){return e.querySelector(t)}static getHeight(e){let t=e.offsetHeight,i=getComputedStyle(e);return t-=parseFloat(i.paddingTop)+parseFloat(i.paddingBottom)+parseFloat(i.borderTopWidth)+parseFloat(i.borderBottomWidth),t}static getWidth(e){let t=e.offsetWidth,i=getComputedStyle(e);return t-=parseFloat(i.paddingLeft)+parseFloat(i.paddingRight)+parseFloat(i.borderLeftWidth)+parseFloat(i.borderRightWidth),t}static absolutePosition(e,t){let i,n,s=e.offsetParent?{width:e.offsetWidth,height:e.offsetHeight}:this.getHiddenElementDimensions(e),l=s.height,o=s.width,r=t.offsetHeight,a=t.offsetWidth,d=t.getBoundingClientRect(),c=this.getWindowScrollTop(),p=this.getWindowScrollLeft(),m=this.getViewport();d.top+r+l>m.height?(i=d.top+c-l,e.style.transformOrigin="bottom",i<0&&(i=c)):(i=r+d.top+c,e.style.transformOrigin="top"),n=d.left+o>m.width?Math.max(0,d.left+p+a-o):d.left+p,e.style.top=i+"px",e.style.left=n+"px"}static relativePosition(e,t){let i=e.offsetParent?{width:e.offsetWidth,height:e.offsetHeight}:this.getHiddenElementDimensions(e);const n=t.offsetHeight,s=t.getBoundingClientRect(),l=this.getViewport();let o,r;s.top+n+i.height>l.height?(o=-1*i.height,e.style.transformOrigin="bottom",s.top+o<0&&(o=-1*s.top)):(o=n,e.style.transformOrigin="top"),r=i.width>l.width?-1*s.left:s.left+i.width>l.width?-1*(s.left+i.width-l.width):0,e.style.top=o+"px",e.style.left=r+"px"}static getParents(e,t=[]){return null===e.parentNode?t:this.getParents(e.parentNode,t.concat([e.parentNode]))}static getScrollableParents(e){let t=[];if(e){let i=this.getParents(e);const n=/(auto|scroll)/,s=e=>{let t=window.getComputedStyle(e,null);return n.test(t.getPropertyValue("overflow"))||n.test(t.getPropertyValue("overflowX"))||n.test(t.getPropertyValue("overflowY"))};for(let e of i){let i=1===e.nodeType&&e.dataset.scrollselectors;if(i){let n=i.split(",");for(let i of n){let n=this.findSingle(e,i);n&&s(n)&&t.push(n)}}9!==e.nodeType&&s(e)&&t.push(e)}}return t}static getHiddenElementOuterHeight(e){e.style.visibility="hidden",e.style.display="block";let t=e.offsetHeight;return e.style.display="none",e.style.visibility="visible",t}static getHiddenElementOuterWidth(e){e.style.visibility="hidden",e.style.display="block";let t=e.offsetWidth;return e.style.display="none",e.style.visibility="visible",t}static getHiddenElementDimensions(e){var t={};return e.style.visibility="hidden",e.style.display="block",t.width=e.offsetWidth,t.height=e.offsetHeight,e.style.display="none",e.style.visibility="visible",t}static fadeIn(e,t){e.style.opacity=0;var i=+new Date,n=0,s=function(){n=+e.style.opacity+((new Date).getTime()-i)/t,e.style.opacity=n,i=+new Date,+n<1&&(window.requestAnimationFrame&&requestAnimationFrame(s)||setTimeout(s,16))};s()}static fadeOut(e,t){var i=1,n=50/t;let s=setInterval((()=>{(i-=n)<=0&&(i=0,clearInterval(s)),e.style.opacity=i}),50)}static getUserAgent(){return navigator.userAgent}static appendChild(e,t){if(this.isElement(t))t.appendChild(e);else{if(!t.el||!t.elElement)throw new Error("Cannot append "+t+" to "+e);t.elElement.appendChild(e)}}static scrollInView(e,t){let i=getComputedStyle(e).getPropertyValue("borderTopWidth"),n=i?parseFloat(i):0,s=getComputedStyle(e).getPropertyValue("paddingTop"),l=s?parseFloat(s):0,o=e.getBoundingClientRect(),r=t.getBoundingClientRect().top+document.body.scrollTop-(o.top+document.body.scrollTop)-n-l,a=e.scrollTop,d=e.clientHeight,c=this.getOuterHeight(t);r<0?e.scrollTop=a+r:r+c>d&&(e.scrollTop=a+r-d+c)}static clearSelection(){if(window.getSelection)window.getSelection().empty?window.getSelection().empty():window.getSelection().removeAllRanges&&window.getSelection().rangeCount>0&&window.getSelection().getRangeAt(0).getClientRects().length>0&&window.getSelection().removeAllRanges();else if(document.selection&&document.selection.empty)try{document.selection.empty()}catch(e){}}static calculateScrollbarWidth(){if(null!=this.calculatedScrollbarWidth)return this.calculatedScrollbarWidth;let e=document.createElement("div");e.className="p-scrollbar-measure",document.body.appendChild(e);let t=e.offsetWidth-e.clientWidth;return document.body.removeChild(e),this.calculatedScrollbarWidth=t,t}static getBrowser(){if(!this.browser){let e=this.resolveUserAgent();this.browser={},e.browser&&(this.browser[e.browser]=!0,this.browser.version=e.version),this.browser.chrome?this.browser.webkit=!0:this.browser.webkit&&(this.browser.safari=!0)}return this.browser}static resolveUserAgent(){let e=navigator.userAgent.toLowerCase(),t=/(chrome)[ ]([\w.]+)/.exec(e)||/(webkit)[ ]([\w.]+)/.exec(e)||/(opera)(?:.*version|)[ ]([\w.]+)/.exec(e)||/(msie) ([\w.]+)/.exec(e)||e.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e)||[];return{browser:t[1]||"",version:t[2]||"0"}}static isVisible(e){return null!=e.offsetParent}static invokeElementMethod(e,t,i){e[t].apply(e,i)}static getFocusableElements(e){let t=o.find(e,\'button:not([tabindex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden]),\n                [href][clientHeight][clientWidth]:not([tabindex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden]),\n                input:not([tabindex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden]), select:not([tabindex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden]),\n                textarea:not([tabindex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden]), [tabIndex]:not([tabIndex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden]),\n                [contenteditable]:not([tabIndex = "-1"]):not([disabled]):not([style*="display:none"]):not([hidden])\'),i=[];for(let e of t)"none"!=getComputedStyle(e).display&&"hidden"!=getComputedStyle(e).visibility&&i.push(e);return i}static isClickable(e){const t=e.nodeName,i=e.parentElement&&e.parentElement.nodeName;return"INPUT"==t||"BUTTON"==t||"A"==t||"INPUT"==i||"BUTTON"==i||"A"==i||this.hasClass(e,"p-button")||this.hasClass(e.parentElement,"p-button")||this.hasClass(e.parentElement,"p-checkbox")||this.hasClass(e.parentElement,"p-radiobutton")}static applyStyle(e,t){if("string"==typeof t)e.style.cssText=this.style;else for(let i in this.style)e.style[i]=t[i]}static isIOS(){return/iPad|iPhone|iPod/.test(navigator.userAgent)&&!window.MSStream}static isAndroid(){return/(android)/i.test(navigator.userAgent)}}var r={name:"SpeedDial",props:{model:null,visible:{type:Boolean,default:!1},direction:{type:String,default:"up"},transitionDelay:{type:Number,default:30},type:{type:String,default:"linear"},radius:{type:Number,default:0},mask:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1},hideOnClickOutside:{type:Boolean,default:!0},buttonClassName:null,maskStyle:null,maskClassName:null,showIcon:{type:String,default:"pi pi-plus"},hideIcon:null,rotateAnimation:{type:Boolean,default:!0},style:null,class:null},documentClickListener:null,container:null,list:null,data(){return{d_visible:this.visible,isItemClicked:!1}},watch:{visible(e){this.d_visible=e}},mounted(){if("linear"!==this.type){const e=o.findSingle(this.container,".p-speeddial-button"),t=o.findSingle(this.list,".p-speeddial-item");if(e&&t){const i=Math.abs(e.offsetWidth-t.offsetWidth),n=Math.abs(e.offsetHeight-t.offsetHeight);this.list.style.setProperty("--item-diff-x",i/2+"px"),this.list.style.setProperty("--item-diff-y",n/2+"px")}}this.hideOnClickOutside&&this.bindDocumentClickListener()},beforeMount(){this.bindDocumentClickListener()},methods:{onItemClick(e,t){t.command&&t.command({originalEvent:e,item:t}),this.hide(),this.isItemClicked=!0,e.preventDefault()},onClick(e){this.d_visible?this.hide():this.show(),this.isItemClicked=!0,this.$emit("click",e)},show(){this.d_visible=!0,this.$emit("show")},hide(){this.d_visible=!1,this.$emit("hide")},calculateTransitionDelay(e){const t=this.model.length;return(this.d_visible?e:t-e-1)*this.transitionDelay},calculatePointStyle(e){const t=this.type;if("linear"!==t){const i=this.model.length,n=this.radius||20*i;if("circle"===t){const t=2*Math.PI/i;return{left:`calc(${n*Math.cos(t*e)}px + var(--item-diff-x, 0px))`,top:`calc(${n*Math.sin(t*e)}px + var(--item-diff-y, 0px))`}}if("semi-circle"===t){const t=this.direction,s=Math.PI/(i-1),l=`calc(${n*Math.cos(s*e)}px + var(--item-diff-x, 0px))`,o=`calc(${n*Math.sin(s*e)}px + var(--item-diff-y, 0px))`;if("up"===t)return{left:l,bottom:o};if("down"===t)return{left:l,top:o};if("left"===t)return{right:o,top:l};if("right"===t)return{left:o,top:l}}else if("quarter-circle"===t){const t=this.direction,s=Math.PI/(2*(i-1)),l=`calc(${n*Math.cos(s*e)}px + var(--item-diff-x, 0px))`,o=`calc(${n*Math.sin(s*e)}px + var(--item-diff-y, 0px))`;if("up-left"===t)return{right:l,bottom:o};if("up-right"===t)return{left:l,bottom:o};if("down-left"===t)return{right:o,top:l};if("down-right"===t)return{left:o,top:l}}}return{}},getItemStyle(e){return{transitionDelay:`${this.calculateTransitionDelay(e)}ms`,...this.calculatePointStyle(e)}},bindDocumentClickListener(){this.documentClickListener||(this.documentClickListener=e=>{this.d_visible&&this.isOutsideClicked(e)&&this.hide(),this.isItemClicked=!1},document.addEventListener("click",this.documentClickListener))},unbindDocumentClickListener(){this.documentClickListener&&(document.removeEventListener("click",this.documentClickListener),this.documentClickListener=null)},isOutsideClicked(e){return this.container&&!(this.container.isSameNode(e.target)||this.container.contains(e.target)||this.isItemClicked)},containerRef(e){this.container=e},listRef(e){this.list=e}},computed:{containerClass(){return[`p-speeddial p-component p-speeddial-${this.type}`,{[`p-speeddial-direction-${this.direction}`]:"circle"!==this.type,"p-speeddial-opened":this.d_visible,"p-disabled":this.disabled},this.class]},buttonClass(){return["p-speeddial-button p-button-rounded",{"p-speeddial-rotate":this.rotateAnimation&&!this.hideIcon},this.buttonClassName]},iconClassName(){return this.d_visible&&this.hideIcon?this.hideIcon:this.showIcon},maskClass(){return["p-speeddial-mask",{"p-speeddial-mask-visible":this.d_visible},this.maskClassName]}},components:{SDButton:s.default},directives:{ripple:l.default}};return function(e,t){void 0===t&&(t={});var i=t.insertAt;if(e&&"undefined"!=typeof document){var n=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===i&&n.firstChild?n.insertBefore(s,n.firstChild):n.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}("\n.p-speeddial {\n    position: absolute;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    z-index: 1;\n}\n.p-speeddial-list {\n    margin: 0;\n    padding: 0;\n    list-style: none;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-transition: top 0s linear 0.2s;\n    transition: top 0s linear 0.2s;\n    pointer-events: none;\n}\n.p-speeddial-item {\n    -webkit-transform: scale(0);\n            transform: scale(0);\n    opacity: 0;\n    -webkit-transition: opacity 0.8s, -webkit-transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: opacity 0.8s, -webkit-transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, opacity 0.8s;\n    transition: transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, opacity 0.8s, -webkit-transform 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    will-change: transform;\n}\n.p-speeddial-action {\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    border-radius: 50%;\n    position: relative;\n    overflow: hidden;\n}\n.p-speeddial-circle .p-speeddial-item,\n.p-speeddial-semi-circle .p-speeddial-item,\n.p-speeddial-quarter-circle .p-speeddial-item {\n    position: absolute;\n}\n.p-speeddial-rotate {\n    -webkit-transition: -webkit-transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: -webkit-transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, -webkit-transform 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;\n    will-change: transform;\n}\n.p-speeddial-mask {\n    position: absolute;\n    left: 0;\n    top: 0;\n    width: 100%;\n    height: 100%;\n    opacity: 0;\n    -webkit-transition: opacity 250ms cubic-bezier(0.25, 0.8, 0.25, 1);\n    transition: opacity 250ms cubic-bezier(0.25, 0.8, 0.25, 1);\n}\n.p-speeddial-mask-visible {\n    pointer-events: none;\n    opacity: 1;\n    -webkit-transition: opacity 400ms cubic-bezier(0.25, 0.8, 0.25, 1);\n    transition: opacity 400ms cubic-bezier(0.25, 0.8, 0.25, 1);\n}\n.p-speeddial-opened .p-speeddial-list {\n    pointer-events: auto;\n}\n.p-speeddial-opened .p-speeddial-item {\n    -webkit-transform: scale(1);\n            transform: scale(1);\n    opacity: 1;\n}\n.p-speeddial-opened .p-speeddial-rotate {\n    -webkit-transform: rotate(45deg);\n            transform: rotate(45deg);\n}\n\n/* Direction */\n.p-speeddial-direction-up {\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: column-reverse;\n            flex-direction: column-reverse;\n}\n.p-speeddial-direction-up .p-speeddial-list {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: column-reverse;\n            flex-direction: column-reverse;\n}\n.p-speeddial-direction-down {\n    -webkit-box-align: center;\n        -ms-flex-align: center;\n            align-items: center;\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-speeddial-direction-down .p-speeddial-list {\n    -webkit-box-orient: vertical;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: column;\n            flex-direction: column;\n}\n.p-speeddial-direction-left {\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: row-reverse;\n            flex-direction: row-reverse;\n}\n.p-speeddial-direction-left .p-speeddial-list {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: reverse;\n        -ms-flex-direction: row-reverse;\n            flex-direction: row-reverse;\n}\n.p-speeddial-direction-right {\n    -webkit-box-pack: center;\n        -ms-flex-pack: center;\n            justify-content: center;\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: row;\n            flex-direction: row;\n}\n.p-speeddial-direction-right .p-speeddial-list {\n    -webkit-box-orient: horizontal;\n    -webkit-box-direction: normal;\n        -ms-flex-direction: row;\n            flex-direction: row;\n}\n"),r.render=function(e,t,n,s,l,o){const r=i.resolveComponent("SDButton"),a=i.resolveDirective("ripple");return i.openBlock(),i.createBlock(i.Fragment,null,[i.createVNode("div",{ref:o.containerRef,class:o.containerClass,style:n.style},[i.renderSlot(e.$slots,"button",{toggle:o.onClick},(()=>[i.createVNode(r,{type:"button",class:o.buttonClass,icon:o.iconClassName,onClick:t[1]||(t[1]=e=>o.onClick(e)),disabled:n.disabled},null,8,["class","icon","disabled"])])),i.createVNode("ul",{ref:o.listRef,class:"p-speeddial-list",role:"menu"},[(i.openBlock(!0),i.createBlock(i.Fragment,null,i.renderList(n.model,((t,n)=>(i.openBlock(),i.createBlock("li",{key:n,class:"p-speeddial-item",style:o.getItemStyle(n),role:"none"},[e.$slots.item?(i.openBlock(),i.createBlock(i.resolveDynamicComponent(e.$slots.item),{key:1,item:t},null,8,["item"])):i.withDirectives((i.openBlock(),i.createBlock("a",{key:0,href:t.url||"#",role:"menuitem",class:["p-speeddial-action",{"p-disabled":t.disabled}],target:t.target,"data-pr-tooltip":t.label,onClick:e=>o.onItemClick(e,t)},[t.icon?(i.openBlock(),i.createBlock("span",{key:0,class:["p-speeddial-action-icon",t.icon]},null,2)):i.createCommentVNode("",!0)],10,["href","target","data-pr-tooltip","onClick"])),[[a]])],4)))),128))],512)],6),n.mask?(i.openBlock(),i.createBlock("div",{key:0,class:o.maskClass,style:this.maskStyle},null,6)):i.createCommentVNode("",!0)],64)},r}(primevue.button,primevue.ripple,Vue);');
				break;
		}


	}

	/**
	 * A simple PHP function that calculates the percentage of a given number.
	 *
	 * @param   int  $number   The number you want a percentage of.
	 * @param   int  $percent  The percentage that you want to calculate.
	 *
	 * @return int The final result.
	 */

	public static function getPercentOfNumber($number, $percent)
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

