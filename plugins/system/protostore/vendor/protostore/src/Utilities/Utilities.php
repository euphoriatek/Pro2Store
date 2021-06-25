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


	public static function getCurrentItemId()
	{

		$input = Factory::getApplication()->input;

		if ($input->get('option') == 'com_content')
		{
			if ($input->get('view') == 'article')
			{
				return $input->get('id');
			}
		}

		return false;

//        return Factory::getApplication()->input->get('id');
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

	public static function getDate()
	{
		$config   = Factory::getConfig();
		$timezone = $config->get('offset');

		$timezone = new DateTimeZone($timezone);

		$date = Factory::getDate();
		$date->setTimezone($timezone);

		return $date->toSql(true);
	}

	public static function prepareTaskData($data)
	{

		$data = (object) $data;
		unset($data->option);
		unset($data->plugin);
		unset($data->task);
		unset($data->type);
		unset($data->format);
		unset($data->method);

		return $data;
	}

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

		$names = explode(',', $names);

		$tagArray = array();

		$db = Factory::getDbo();

		foreach ($names as $name)
		{
			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__tags'));
			$query->where($db->quoteName('title') . ' = ' . $db->quote($name));

			$db->setQuery($query);

			$tag = $db->loadObject();

			if ($tag)
			{
				$tagArray[] = $tag->id;
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

		switch ($node) {
			case "inputswitch":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputswitch=function(e){"use strict";var t={inheritAttrs:!1,emits:["update:modelValue","click","change"],props:{modelValue:Boolean,class:null,style:null},data:()=>({focused:!1}),methods:{onClick(e){this.$attrs.disabled||(this.$emit("click",e),this.$emit("update:modelValue",!this.modelValue),this.$emit("change",e),this.$refs.input.focus()),e.preventDefault()},onFocus(){this.focused=!0},onBlur(){this.focused=!1}},computed:{containerClass(){return["p-inputswitch p-component",this.class,{"p-inputswitch-checked":this.modelValue,"p-disabled":this.$attrs.disabled,"p-focus":this.focused}]}}};const i={class:"p-hidden-accessible"},n=e.createVNode("span",{class:"p-inputswitch-slider"},null,-1);return function(e,t){void 0===t&&(t={});var i=t.insertAt;if(e&&"undefined"!=typeof document){var n=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===i&&n.firstChild?n.insertBefore(s,n.firstChild):n.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}(\'.p-inputswitch {    position: relative;    display: inline-block;}.p-inputswitch-slider {    position: absolute;    cursor: pointer;    top: 0;    left: 0;    right: 0;    bottom: 0;}.p-inputswitch-slider:before {    position: absolute;    content: "";    top: 50%;}\'),t.render=function(t,s,o,c,l,d){return e.openBlock(),e.createBlock("div",{class:d.containerClass,onClick:s[4]||(s[4]=e=>d.onClick(e)),style:o.style},[e.createVNode("div",i,[e.createVNode("input",e.mergeProps({ref:"input",type:"checkbox",checked:o.modelValue},t.$attrs,{onFocus:s[1]||(s[1]=e=>d.onFocus(e)),onBlur:s[2]||(s[2]=e=>d.onBlur(e)),onKeydown:s[3]||(s[3]=e.withKeys(e.withModifiers((e=>d.onClick(e)),["prevent"]),["enter"])),role:"switch","aria-checked":o.modelValue}),null,16,["checked","aria-checked"])]),n],6)},t}(Vue);');
				break;
			case "chips":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.chips=function(e){"use strict";var t={name:"Chips",inheritAttrs:!1,emits:["update:modelValue","add","remove"],props:{modelValue:{type:Array,default:null},max:{type:Number,default:null},separator:{type:String,default:null},addOnBlur:{type:Boolean,default:null},allowDuplicate:{type:Boolean,default:!0},class:null,style:null},data:()=>({inputValue:null,focused:!1}),methods:{onWrapperClick(){this.$refs.input.focus()},onInput(e){this.inputValue=e.target.value},onFocus(){this.focused=!0},onBlur(e){this.focused=!1,this.addOnBlur&&this.addItem(e,e.target.value,!1)},onKeyDown(e){const t=e.target.value;switch(e.which){case 8:0===t.length&&this.modelValue&&this.modelValue.length>0&&this.removeItem(e,this.modelValue.length-1);break;case 13:t&&t.trim().length&&!this.maxedOut&&this.addItem(e,t,!0);break;default:this.separator&&","===this.separator&&188===e.which&&this.addItem(e,t,!0)}},onPaste(e){if(this.separator){let t=(e.clipboardData||window.clipboardData).getData("Text");if(t){let n=this.modelValue||[],i=t.split(this.separator);i=i.filter((e=>this.allowDuplicate||-1===n.indexOf(e))),n=[...n,...i],this.updateModel(e,n,!0)}}},updateModel(e,t,n){this.$emit("update:modelValue",t),this.$emit("add",{originalEvent:e,value:t}),this.$refs.input.value="",this.inputValue="",n&&e.preventDefault()},addItem(e,t,n){if(t&&t.trim().length){let i=this.modelValue?[...this.modelValue]:[];(this.allowDuplicate||-1===i.indexOf(t))&&(i.push(t),this.updateModel(e,i,n))}},removeItem(e,t){if(this.$attrs.disabled)return;let n=[...this.modelValue];const i=n.splice(t,1);this.$emit("update:modelValue",n),this.$emit("remove",{originalEvent:e,value:i})}},computed:{maxedOut(){return this.max&&this.modelValue&&this.max===this.modelValue.length},containerClass(){return["p-chips p-component p-inputwrapper",this.class,{"p-inputwrapper-filled":this.modelValue&&this.modelValue.length||this.inputValue&&this.inputValue.length,"p-inputwrapper-focus":this.focused}]}}};const n={class:"p-chips-token-label"},i={class:"p-chips-input-token"};return function(e,t){void 0===t&&(t={});var n=t.insertAt;if(e&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],l=document.createElement("style");l.type="text/css","top"===n&&i.firstChild?i.insertBefore(l,i.firstChild):i.appendChild(l),l.styleSheet?l.styleSheet.cssText=e:l.appendChild(document.createTextNode(e))}}("\n.p-chips {display: -webkit-inline-box;display: -ms-inline-flexbox;display: inline-flex;\n}\n.p-chips-multiple-container {margin: 0;padding: 0;list-style-type: none;cursor: text;overflow: hidden;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;    -ms-flex-align: center;        align-items: center;-ms-flex-wrap: wrap;    flex-wrap: wrap;\n}\n.p-chips-token {cursor: default;display: -webkit-inline-box;display: -ms-inline-flexbox;display: inline-flex;-webkit-box-align: center;    -ms-flex-align: center;        align-items: center;-webkit-box-flex: 0;    -ms-flex: 0 0 auto;        flex: 0 0 auto;\n}\n.p-chips-input-token {-webkit-box-flex: 1;    -ms-flex: 1 1 auto;        flex: 1 1 auto;display: -webkit-inline-box;display: -ms-inline-flexbox;display: inline-flex;\n}\n.p-chips-token-icon {cursor: pointer;\n}\n.p-chips-input-token input {border: 0 none;outline: 0 none;background-color: transparent;margin: 0;padding: 0;-webkit-box-shadow: none;        box-shadow: none;border-radius: 0;width: 100%;\n}\n.p-fluid .p-chips {display: -webkit-box;display: -ms-flexbox;display: flex;\n}\n"),t.render=function(t,l,a,s,o,p){return e.openBlock(),e.createBlock("div",{class:p.containerClass,style:a.style},[e.createVNode("ul",{class:["p-inputtext p-chips-multiple-container",{"p-disabled":t.$attrs.disabled,"p-focus":o.focused}],onClick:l[6]||(l[6]=e=>p.onWrapperClick())},[(e.openBlock(!0),e.createBlock(e.Fragment,null,e.renderList(a.modelValue,((i,l)=>(e.openBlock(),e.createBlock("li",{key:`${l}_${i}`,class:"p-chips-token"},[e.renderSlot(t.$slots,"chip",{value:i},(()=>[e.createVNode("span",n,e.toDisplayString(i),1)])),e.createVNode("span",{class:"p-chips-token-icon pi pi-times-circle",onClick:e=>p.removeItem(e,l)},null,8,["onClick"])])))),128)),e.createVNode("li",i,[e.createVNode("input",e.mergeProps({ref:"input",type:"text"},t.$attrs,{onFocus:l[1]||(l[1]=(...e)=>p.onFocus&&p.onFocus(...e)),onBlur:l[2]||(l[2]=e=>p.onBlur(e)),onInput:l[3]||(l[3]=(...e)=>p.onInput&&p.onInput(...e)),onKeydown:l[4]||(l[4]=e=>p.onKeyDown(e)),onPaste:l[5]||(l[5]=e=>p.onPaste(e)),disabled:t.$attrs.disabled||p.maxedOut}),null,16,["disabled"])])],2)],6)},t}(Vue);');
				break;
			case "inputnumber":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputnumber=function(t,e,n){"use strict";function i(t){return t&&"object"==typeof t&&"default"in t?t:{default:t}}var s=i(t),r=i(e),u={inheritAttrs:!1,emits:["update:modelValue","input"],props:{modelValue:{type:Number,default:null},format:{type:Boolean,default:!0},showButtons:{type:Boolean,default:!1},buttonLayout:{type:String,default:"stacked"},incrementButtonClass:{type:String,default:null},decrementButtonClass:{type:String,default:null},incrementButtonIcon:{type:String,default:"pi pi-angle-up"},decrementButtonIcon:{type:String,default:"pi pi-angle-down"},locale:{type:String,default:void 0},localeMatcher:{type:String,default:void 0},mode:{type:String,default:"decimal"},prefix:{type:String,default:null},suffix:{type:String,default:null},currency:{type:String,default:void 0},currencyDisplay:{type:String,default:void 0},useGrouping:{type:Boolean,default:!0},minFractionDigits:{type:Number,default:void 0},maxFractionDigits:{type:Number,default:void 0},min:{type:Number,default:null},max:{type:Number,default:null},step:{type:Number,default:1},style:null,class:null,inputStyle:null,inputClass:null},numberFormat:null,_numeral:null,_decimal:null,_group:null,_minusSign:null,_currency:null,_suffix:null,_prefix:null,_index:null,groupChar:"",isSpecialChar:null,prefixChar:null,suffixChar:null,timer:null,data:()=>({focused:!1}),watch:{locale(t,e){this.updateConstructParser(t,e)},localeMatcher(t,e){this.updateConstructParser(t,e)},mode(t,e){this.updateConstructParser(t,e)},currency(t,e){this.updateConstructParser(t,e)},currencyDisplay(t,e){this.updateConstructParser(t,e)},useGrouping(t,e){this.updateConstructParser(t,e)},minFractionDigits(t,e){this.updateConstructParser(t,e)},maxFractionDigits(t,e){this.updateConstructParser(t,e)},suffix(t,e){this.updateConstructParser(t,e)},prefix(t,e){this.updateConstructParser(t,e)}},created(){this.constructParser()},methods:{getOptions(){return{localeMatcher:this.localeMatcher,style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay,useGrouping:this.useGrouping,minimumFractionDigits:this.minFractionDigits,maximumFractionDigits:this.maxFractionDigits}},constructParser(){this.numberFormat=new Intl.NumberFormat(this.locale,this.getOptions());const t=[...new Intl.NumberFormat(this.locale,{useGrouping:!1}).format(9876543210)].reverse(),e=new Map(t.map(((t,e)=>[t,e])));this._numeral=new RegExp(`[${t.join("")}]`,"g"),this._decimal=this.getDecimalExpression(),this._group=this.getGroupingExpression(),this._minusSign=this.getMinusSignExpression(),this._currency=this.getCurrencyExpression(),this._suffix=this.getSuffixExpression(),this._prefix=this.getPrefixExpression(),this._index=t=>e.get(t)},updateConstructParser(t,e){t!==e&&this.constructParser()},escapeRegExp:t=>t.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),getDecimalExpression(){const t=new Intl.NumberFormat(this.locale,{useGrouping:!1});return new RegExp(`[${t.format(1.1).trim().replace(this._numeral,"")}]`,"g")},getGroupingExpression(){const t=new Intl.NumberFormat(this.locale,{useGrouping:!0});return this.groupChar=t.format(1e6).trim().replace(this._numeral,"").charAt(0),new RegExp(`[${this.groupChar}]`,"g")},getMinusSignExpression(){const t=new Intl.NumberFormat(this.locale,{useGrouping:!1});return new RegExp(`[${t.format(-1).trim().replace(this._numeral,"")}]`,"g")},getCurrencyExpression(){if(this.currency){const t=new Intl.NumberFormat(this.locale,{style:"currency",currency:this.currency,currencyDisplay:this.currencyDisplay});return new RegExp(`[${t.format(1).replace(/\s/g,"").replace(this._numeral,"").replace(this._decimal,"").replace(this._group,"")}]`,"g")}return new RegExp("[]","g")},getPrefixExpression(){if(this.prefix)this.prefixChar=this.prefix;else{const t=new Intl.NumberFormat(this.locale,{style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay});this.prefixChar=t.format(1).split("1")[0]}return new RegExp(`${this.escapeRegExp(this.prefixChar||"")}`,"g")},getSuffixExpression(){if(this.suffix)this.suffixChar=this.suffix;else{const t=new Intl.NumberFormat(this.locale,{style:this.mode,currency:this.currency,currencyDisplay:this.currencyDisplay,minimumFractionDigits:0,maximumFractionDigits:0});this.suffixChar=t.format(1).split("1")[1]}return new RegExp(`${this.escapeRegExp(this.suffixChar||"")}`,"g")},formatValue(t){if(null!=t){if("-"===t)return t;if(this.format){let e=new Intl.NumberFormat(this.locale,this.getOptions()).format(t);return this.prefix&&(e=this.prefix+e),this.suffix&&(e+=this.suffix),e}return t.toString()}return""},parseValue(t){let e=t.replace(this._suffix,"").replace(this._prefix,"").trim().replace(/\s/g,"").replace(this._currency,"").replace(this._group,"").replace(this._minusSign,"-").replace(this._decimal,".").replace(this._numeral,this._index);if(e){if("-"===e)return e;let t=+e;return isNaN(t)?null:t}return null},repeat(t,e,n){let i=e||500;this.clearTimer(),this.timer=setTimeout((()=>{this.repeat(t,40,n)}),i),this.spin(t,n)},spin(t,e){if(this.$refs.input){let n=this.step*e,i=this.parseValue(this.$refs.input.$el.value)||0,s=this.validateValue(i+n);this.updateInput(s,null,"spin"),this.updateModel(t,s),this.handleOnInput(t,i,s)}},onUpButtonMouseDown(t){this.$attrs.disabled||(this.$refs.input.$el.focus(),this.repeat(t,null,1),t.preventDefault())},onUpButtonMouseUp(){this.$attrs.disabled||this.clearTimer()},onUpButtonMouseLeave(){this.$attrs.disabled||this.clearTimer()},onUpButtonKeyUp(){this.$attrs.disabled||this.clearTimer()},onUpButtonKeyDown(t){32!==t.keyCode&&13!==t.keyCode||this.repeat(t,null,1)},onDownButtonMouseDown(t){this.$attrs.disabled||(this.$refs.input.$el.focus(),this.repeat(t,null,-1),t.preventDefault())},onDownButtonMouseUp(){this.$attrs.disabled||this.clearTimer()},onDownButtonMouseLeave(){this.$attrs.disabled||this.clearTimer()},onDownButtonKeyUp(){this.$attrs.disabled||this.clearTimer()},onDownButtonKeyDown(t){32!==t.keyCode&&13!==t.keyCode||this.repeat(t,null,-1)},onUserInput(){this.isSpecialChar&&(this.$refs.input.$el.value=this.lastValue),this.isSpecialChar=!1},onInputKeyDown(t){if(this.lastValue=t.target.value,t.shiftKey||t.altKey)return void(this.isSpecialChar=!0);let e=t.target.selectionStart,n=t.target.selectionEnd,i=t.target.value,s=null;switch(t.altKey&&t.preventDefault(),t.which){case 38:this.spin(t,1),t.preventDefault();break;case 40:this.spin(t,-1),t.preventDefault();break;case 37:this.isNumeralChar(i.charAt(e-1))||t.preventDefault();break;case 39:this.isNumeralChar(i.charAt(e))||t.preventDefault();break;case 13:s=this.validateValue(this.parseValue(i)),this.$refs.input.$el.value=this.formatValue(s),this.$refs.input.$el.setAttribute("aria-valuenow",s),this.updateModel(t,s);break;case 8:if(t.preventDefault(),e===n){let n=i.charAt(e-1),r=i.search(this._decimal);this._decimal.lastIndex=0,this.isNumeralChar(n)&&(this._group.test(n)?(this._group.lastIndex=0,s=i.slice(0,e-2)+i.slice(e-1)):this._decimal.test(n)?(this._decimal.lastIndex=0,this.$refs.input.$el.setSelectionRange(e-1,e-1)):r>0&&e>r?s=i.slice(0,e-1)+"0"+i.slice(e):r>0&&1===r?(s=i.slice(0,e-1)+"0"+i.slice(e),s=this.parseValue(s)>0?s:""):s=i.slice(0,e-1)+i.slice(e)),this.updateValue(t,s,null,"delete-single")}else s=this.deleteRange(i,e,n),this.updateValue(t,s,null,"delete-range");break;case 46:if(t.preventDefault(),e===n){let n=i.charAt(e),r=i.search(this._decimal);this._decimal.lastIndex=0,this.isNumeralChar(n)&&(this._group.test(n)?(this._group.lastIndex=0,s=i.slice(0,e)+i.slice(e+2)):this._decimal.test(n)?(this._decimal.lastIndex=0,this.$refs.input.$el.setSelectionRange(e+1,e+1)):r>0&&e>r?s=i.slice(0,e)+"0"+i.slice(e+1):r>0&&1===r?(s=i.slice(0,e)+"0"+i.slice(e+1),s=this.parseValue(s)>0?s:""):s=i.slice(0,e)+i.slice(e+1)),this.updateValue(t,s,null,"delete-back-single")}else s=this.deleteRange(i,e,n),this.updateValue(t,s,null,"delete-range")}},onInputKeyPress(t){t.preventDefault();let e=t.which||t.keyCode,n=String.fromCharCode(e);const i=this.isDecimalSign(n),s=this.isMinusSign(n);(48<=e&&e<=57||s||i)&&this.insert(t,n,{isDecimalSign:i,isMinusSign:s})},onPaste(t){t.preventDefault();let e=(t.clipboardData||window.clipboardData).getData("Text");if(e){let n=this.parseValue(e);null!=n&&this.insert(t,n.toString())}},allowMinusSign(){return null===this.min||this.min<0},isMinusSign(t){return!!this._minusSign.test(t)&&(this._minusSign.lastIndex=0,!0)},isDecimalSign(t){return!!this._decimal.test(t)&&(this._decimal.lastIndex=0,!0)},insert(t,e,n={isDecimalSign:!1,isMinusSign:!1}){const i=e.search(this._minusSign);if(this._minusSign.lastIndex=0,!this.allowMinusSign()&&-1!==i)return;const s=this.$refs.input.$el.selectionStart,r=this.$refs.input.$el.selectionEnd;let u=this.$refs.input.$el.value.trim();const l=u.search(this._decimal);this._decimal.lastIndex=0;const a=u.search(this._minusSign);let o;if(this._minusSign.lastIndex=0,n.isMinusSign)0===s&&(o=u,-1!==a&&0===r||(o=this.insertText(u,e,0,r)),this.updateValue(t,o,e,"insert"));else if(n.isDecimalSign)l>0&&s===l?this.updateValue(t,u,e,"insert"):l>s&&l<r&&(o=this.insertText(u,e,s,r),this.updateValue(t,o,e,"insert"));else{const n=this.numberFormat.resolvedOptions().maximumFractionDigits,i=s!==r?"range-insert":"insert";l>0&&s>l?s+e.length-(l+1)<=n&&(o=u.slice(0,s)+e+u.slice(s+e.length),this.updateValue(t,o,e,i)):(o=this.insertText(u,e,s,r),this.updateValue(t,o,e,i))}},insertText(t,e,n,i){if(2===e.split(".").length){const s=t.slice(n,i).search(this._decimal);return this._decimal.lastIndex=0,s>0?t.slice(0,n)+this.formatValue(e)+t.slice(i):t||this.formatValue(e)}return i-n===t.length?this.formatValue(e):0===n?e+t.slice(i):i===t.length?t.slice(0,n)+e:t.slice(0,n)+e+t.slice(i)},deleteRange(t,e,n){let i;return i=n-e===t.length?"":0===e?t.slice(n):n===t.length?t.slice(0,e):t.slice(0,e)+t.slice(n),i},initCursor(){let t=this.$refs.input.$el.selectionStart,e=this.$refs.input.$el.value,n=e.length,i=null,s=e.charAt(t);if(this.isNumeralChar(s))return;let r=t-1;for(;r>=0;){if(s=e.charAt(r),this.isNumeralChar(s)){i=r;break}r--}if(null!==i)this.$refs.input.$el.setSelectionRange(i+1,i+1);else{for(r=t+1;r<n;){if(s=e.charAt(r),this.isNumeralChar(s)){i=r;break}r++}null!==i&&this.$refs.input.$el.setSelectionRange(i,i)}},onInputClick(){this.initCursor()},isNumeralChar(t){return!(1!==t.length||!(this._numeral.test(t)||this._decimal.test(t)||this._group.test(t)||this._minusSign.test(t)))&&(this.resetRegex(),!0)},resetRegex(){this._numeral.lastIndex=0,this._decimal.lastIndex=0,this._group.lastIndex=0,this._minusSign.lastIndex=0},updateValue(t,e,n,i){let s=this.$refs.input.$el.value,r=null;null!=e&&(r=this.parseValue(e),this.updateInput(r,n,i)),this.handleOnInput(t,s,r)},handleOnInput(t,e,n){this.isValueChanged(e,n)&&this.$emit("input",{originalEvent:t,value:n})},isValueChanged(t,e){if(null===e&&null!==t)return!0;if(null!=e){return e!==("string"==typeof t?this.parseValue(t):t)}return!1},validateValue(t){return null!=this.min&&t<this.min?this.min:null!=this.max&&t>this.max?this.max:"-"===t?null:t},updateInput(t,e,n){e=e||"";let i=this.$refs.input.$el.value,s=this.formatValue(t),r=i.length;if(0===r){this.$refs.input.$el.value=s,this.$refs.input.$el.setSelectionRange(0,0),this.initCursor();const t=(this.prefixChar||"").length+e.length;this.$refs.input.$el.setSelectionRange(t,t)}else{let t=this.$refs.input.$el.selectionStart,u=this.$refs.input.$el.selectionEnd;this.$refs.input.$el.value=s;let l=s.length;if("range-insert"===n){const n=this.parseValue((i||"").slice(0,t)),r=(null!==n?n.toString():"").split("").join(`(${this.groupChar})?`),l=new RegExp(r,"g");l.test(s);const a=e.split("").join(`(${this.groupChar})?`),o=new RegExp(a,"g");o.test(s.slice(l.lastIndex)),u=l.lastIndex+o.lastIndex,this.$refs.input.$el.setSelectionRange(u,u)}else if(l===r)"insert"===n||"delete-back-single"===n?this.$refs.input.$el.setSelectionRange(u+1,u+1):"delete-single"===n?this.$refs.input.$el.setSelectionRange(u-1,u-1):"delete-range"!==n&&"spin"!==n||this.$refs.input.$el.setSelectionRange(u,u);else if("delete-back-single"===n){let t=i.charAt(u-1),e=i.charAt(u),n=r-l,s=this._group.test(e);s&&1===n?u+=1:!s&&this.isNumeralChar(t)&&(u+=-1*n+1),this._group.lastIndex=0,this.$refs.input.$el.setSelectionRange(u,u)}else u+=l-r,this.$refs.input.$el.setSelectionRange(u,u)}this.$refs.input.$el.setAttribute("aria-valuenow",t)},updateModel(t,e){this.$emit("update:modelValue",e)},onInputFocus(){this.focused=!0},onInputBlur(t){this.focused=!1;let e=t.target,n=this.validateValue(this.parseValue(e.value));e.value=this.formatValue(n),e.setAttribute("aria-valuenow",n),this.updateModel(t,n)},clearTimer(){this.timer&&clearInterval(this.timer)}},computed:{containerClass(){return["p-inputnumber p-component p-inputwrapper",this.class,{"p-inputwrapper-filled":this.filled,"p-inputwrapper-focus":this.focused,"p-inputnumber-buttons-stacked":this.showButtons&&"stacked"===this.buttonLayout,"p-inputnumber-buttons-horizontal":this.showButtons&&"horizontal"===this.buttonLayout,"p-inputnumber-buttons-vertical":this.showButtons&&"vertical"===this.buttonLayout}]},upButtonClass(){return["p-inputnumber-button p-inputnumber-button-up",this.incrementButtonClass]},downButtonClass(){return["p-inputnumber-button p-inputnumber-button-down",this.decrementButtonClass]},filled(){return null!=this.modelValue&&this.modelValue.toString().length>0},upButtonListeners(){return{mousedown:t=>this.onUpButtonMouseDown(t),mouseup:t=>this.onUpButtonMouseUp(t),mouseleave:t=>this.onUpButtonMouseLeave(t),keydown:t=>this.onUpButtonKeyDown(t),keyup:t=>this.onUpButtonKeyUp(t)}},downButtonListeners(){return{mousedown:t=>this.onDownButtonMouseDown(t),mouseup:t=>this.onDownButtonMouseUp(t),mouseleave:t=>this.onDownButtonMouseLeave(t),keydown:t=>this.onDownButtonKeyDown(t),keyup:t=>this.onDownButtonKeyUp(t)}},formattedValue(){return this.formatValue(this.modelValue)}},components:{INInputText:s.default,INButton:r.default}};const l={key:0,class:"p-inputnumber-button-group"};return function(t,e){void 0===e&&(e={});var n=e.insertAt;if(t&&"undefined"!=typeof document){var i=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css","top"===n&&i.firstChild?i.insertBefore(s,i.firstChild):i.appendChild(s),s.styleSheet?s.styleSheet.cssText=t:s.appendChild(document.createTextNode(t))}}(".p-inputnumber {    display: -webkit-inline-box;    display: -ms-inline-flexbox;    display: inline-flex;}.p-inputnumber-button {    display: -webkit-box;    display: -ms-flexbox;    display: flex;    -webkit-box-align: center;        -ms-flex-align: center;            align-items: center;    -webkit-box-pack: center;        -ms-flex-pack: center;            justify-content: center;    -webkit-box-flex: 0;        -ms-flex: 0 0 auto;            flex: 0 0 auto;}.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button .p-button-label,.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button .p-button-label {    display: none;}.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button-up {    border-top-left-radius: 0;    border-bottom-left-radius: 0;    border-bottom-right-radius: 0;    padding: 0;}.p-inputnumber-buttons-stacked .p-inputnumber-input {    border-top-right-radius: 0;    border-bottom-right-radius: 0;}.p-inputnumber-buttons-stacked .p-button.p-inputnumber-button-down {    border-top-left-radius: 0;    border-top-right-radius: 0;    border-bottom-left-radius: 0;    padding: 0;}.p-inputnumber-buttons-stacked .p-inputnumber-button-group {    display: -webkit-box;    display: -ms-flexbox;    display: flex;    -webkit-box-orient: vertical;    -webkit-box-direction: normal;        -ms-flex-direction: column;            flex-direction: column;}.p-inputnumber-buttons-stacked .p-inputnumber-button-group .p-button.p-inputnumber-button {    -webkit-box-flex: 1;        -ms-flex: 1 1 auto;            flex: 1 1 auto;}.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button-up {    -webkit-box-ordinal-group: 4;        -ms-flex-order: 3;            order: 3;    border-top-left-radius: 0;    border-bottom-left-radius: 0;}.p-inputnumber-buttons-horizontal .p-inputnumber-input {    -webkit-box-ordinal-group: 3;        -ms-flex-order: 2;            order: 2;    border-radius: 0;}.p-inputnumber-buttons-horizontal .p-button.p-inputnumber-button-down {    -webkit-box-ordinal-group: 2;        -ms-flex-order: 1;            order: 1;    border-top-right-radius: 0;    border-bottom-right-radius: 0;}.p-inputnumber-buttons-vertical {    -webkit-box-orient: vertical;    -webkit-box-direction: normal;        -ms-flex-direction: column;            flex-direction: column;}.p-inputnumber-buttons-vertical .p-button.p-inputnumber-button-up {    -webkit-box-ordinal-group: 2;        -ms-flex-order: 1;            order: 1;    border-bottom-left-radius: 0;    border-bottom-right-radius: 0;    width: 100%;}.p-inputnumber-buttons-vertical .p-inputnumber-input {    -webkit-box-ordinal-group: 3;        -ms-flex-order: 2;            order: 2;    border-radius: 0;    text-align: center;}.p-inputnumber-buttons-vertical .p-button.p-inputnumber-button-down {    -webkit-box-ordinal-group: 4;        -ms-flex-order: 3;            order: 3;    border-top-left-radius: 0;    border-top-right-radius: 0;    width: 100%;}.p-inputnumber-input {    -webkit-box-flex: 1;        -ms-flex: 1 1 auto;            flex: 1 1 auto;}.p-fluid .p-inputnumber {    width: 100%;}.p-fluid .p-inputnumber .p-inputnumber-input {    width: 1%;}.p-fluid .p-inputnumber-buttons-vertical .p-inputnumber-input {    width: 100%;}"),u.render=function(t,e,i,s,r,u){const a=n.resolveComponent("INInputText"),o=n.resolveComponent("INButton");return n.openBlock(),n.createBlock("span",{class:u.containerClass,style:i.style},[n.createVNode(a,n.mergeProps({ref:"input",class:["p-inputnumber-input",i.inputClass],style:i.inputStyle,value:u.formattedValue},t.$attrs,{"aria-valumin":i.min,"aria-valuemax":i.max,onInput:u.onUserInput,onKeydown:u.onInputKeyDown,onKeypress:u.onInputKeyPress,onPaste:u.onPaste,onClick:u.onInputClick,onFocus:u.onInputFocus,onBlur:u.onInputBlur}),null,16,["class","style","value","aria-valumin","aria-valuemax","onInput","onKeydown","onKeypress","onPaste","onClick","onFocus","onBlur"]),i.showButtons&&"stacked"===i.buttonLayout?(n.openBlock(),n.createBlock("span",l,[n.createVNode(o,n.mergeProps({class:u.upButtonClass,icon:i.incrementButtonIcon},n.toHandlers(u.upButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"]),n.createVNode(o,n.mergeProps({class:u.downButtonClass,icon:i.decrementButtonIcon},n.toHandlers(u.downButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"])])):n.createCommentVNode("",!0),i.showButtons&&"stacked"!==i.buttonLayout?(n.openBlock(),n.createBlock(o,n.mergeProps({key:1,class:u.upButtonClass,icon:i.incrementButtonIcon},n.toHandlers(u.upButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"])):n.createCommentVNode("",!0),i.showButtons&&"stacked"!==i.buttonLayout?(n.openBlock(),n.createBlock(o,n.mergeProps({key:2,class:u.downButtonClass,icon:i.decrementButtonIcon},n.toHandlers(u.downButtonListeners),{disabled:t.$attrs.disabled}),null,16,["class","icon","disabled"])):n.createCommentVNode("",!0)],6)},u}(primevue.inputtext,primevue.button,Vue);');
				break;
			case "inputtext":
				$document->addScriptDeclaration('this.primevue=this.primevue||{},this.primevue.inputtext=function(e){"use strict";var t={emits:["update:modelValue"],props:{modelValue:null},methods:{onInput(e){this.$emit("update:modelValue",e.target.value)}},computed:{filled(){return null!=this.modelValue&&this.modelValue.toString().length>0}}};return t.render=function(t,u,l,n,i,o){return e.openBlock(),e.createBlock("input",{class:["p-inputtext p-component",{"p-filled":o.filled}],value:l.modelValue,onInput:u[1]||(u[1]=(...e)=>o.onInput&&o.onInput(...e))},null,42,["value"])},t}(Vue);');
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

