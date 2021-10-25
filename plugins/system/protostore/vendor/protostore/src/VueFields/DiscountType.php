<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields;

use Joomla\CMS\Language\Text;


// no direct access
defined('_JEXEC') or die('Restricted access');

class DiscountType implements Field
{


	public static function get(string $formId): string
	{
		$html = array();

		$html[] = self::getLabel();
		$html[] = '<select ';
		$html[] = 'name="' . $formId . '" ';
		$html[] = 'class="required ' . self::$class . '" ';
		$html[] = 'required ';
		$html[] = ' v-model="form.' . $formId. '" ';
		$html[] = 'id="' . $formId . '" >';
		$html[] = '<option value=""> ' . Text::_('COM_PROTOSTORE_ORDER_SHIPPING_PROVIDER_SELECT_DEFAULT') . ' </option>';
		$html[] = '<option value="1">' . Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_AMOUNT') . '</option>';
		$html[] = '<option value="2">' . Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_PERCENT') . '</option>';
		$html[] = '<option value="3">' . Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_FREE_SHIPPING') . '</option>';
		$html[] = '</select>';

		return implode('', $html);
	}

	public static function getLabel(): string
	{
		return '';
	}
}
