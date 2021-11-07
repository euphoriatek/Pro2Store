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
 * @since 2.0
 */
class JFormFieldByweight extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Byweight';


	public function getLabel()
	{
		return '<div v-show="form.jform_shipping_mode == \'weight\'">' . Text::_($this->element['label']) . '</div>';
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since 2.0
	 */
	protected function getInput()
	{


		$html = array();

		$html[] = '<div v-show="form.jform_shipping_mode == \'weight\'">';
		$html[] = '<div class="uk-grid uk-grid-small" uk-grid>';
		$html[] = '<div class="uk-width-1-2">';
		$html[] = '<p-inputnumber mode="decimal"  name="' . $this->name . '" v-model="form.jform_weight" id="' . $this->id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '</div> ';
		$html[] = '<div class="uk-width-1-2">';
		$html[] = '<select class="uk-select" v-model="form.jform_weight_unit">';
		$html[] = '<option value="">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_SELECT') . '</option>';
		$html[] = '<option value="g">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_WEIGHT_UNIT_GRAM') . '</option>';
		$html[] = '<option value="kg">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_WEIGHT_UNIT_KG') . '</option>';
		$html[] = '<option value="oz">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_WEIGHT_UNIT_OUNCE') . '</option>';
		$html[] = '<option value="lb">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_WEIGHT_UNIT_POUND') . '</option>';
		$html[] = '</select> ';
		$html[] = '</div> ';
		$html[] = '</div> ';
		$html[] = '</div> ';

		return implode('', $html);


	}
}




