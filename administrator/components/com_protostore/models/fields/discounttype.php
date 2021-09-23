<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

/**
 * Clicks field.
 *
 * @since  1.6
 */
class JFormFieldDiscounttype extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Discounttype';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{


		$html = array();

		$html[] = '<select ';
		$html[] = 'name="' . $this->name . '" ';
		$html[] = 'class="required ' . $this->class . '" ';
		$html[] = 'required ';
		$html[] = ' v-model="form.' . $this->id . '" ';
		$html[] = 'id="' . $this->id . '" >';
		$html[] = '<option value=""> ' . Text::_('COM_PROTOSTORE_ORDER_SHIPPING_PROVIDER_SELECT_DEFAULT') . ' </option>';
		$html[] = '<option value="1">' . Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_AMOUNT') . '</option>';
		$html[] = '<option value="2">' . Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_PERCENT') . '</option>';
		$html[] = '<option value="3">' . Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_FREE_SHIPPING') . '</option>';
		$html[] = '</select>';

		return implode('', $html);


	}
}
