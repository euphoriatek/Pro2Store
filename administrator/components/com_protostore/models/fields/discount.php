<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

/**
 * Clicks field.
 *
 * @since  1.6
 */
class JFormFieldDiscount extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Discount';


	public function getLabel()
	{
		return '<div v-show="form.jform_show_discount">' . Text::_($this->element['label']) . '</div>';
	}

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


		$html[] = '<div v-show="form.jform_show_discount">';
		$html[] = '<div class="uk-grid" uk-grid>';
		$html[] = '<div class="uk-width-auto">';
		$html[] = '<p-inputnumber @input="getSellPrice()" v-show="form.jform_discount_type == 1" mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"  name="' . $this->name . '" v-model="form.' . $this->id . '" id="' . $this->id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '<p-inputnumber @input="getSellPrice()"  v-show="form.jform_discount_type == 2" mode="decimal" name="' . $this->name . '" v-model="form.' . $this->id . '" id="' . $this->id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '</div>';
		$html[] = '<div class="uk-width-expand">';
		$html[] = '<select @change="getSellPrice()" class="uk-select" v-model="form.jform_discount_type">';
		$html[] = '<option value="1">Amount</option>';
		$html[] = '<option value="2">Percentage</option>';
		$html[] = '</select>';
		$html[] = '</div>';
		$html[] = '</div>';
		$html[] = '<br/><span>This product will sell for: {{sellPrice}}</span>';
		$html[] = ' </div>';


		return implode('', $html);


	}
}
