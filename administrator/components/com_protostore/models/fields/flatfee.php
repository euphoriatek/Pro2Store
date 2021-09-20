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
class JFormFieldFlatfee extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Flatfee';


	public function getLabel()
	{
		return '<div  v-show="form.jform_shipping_mode == \'flat\'">' . Text::_($this->element['label']) . '</div>';
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

		$html[] = '<div v-show="form.jform_shipping_mode == \'flat\'">';
		$html[] = '<p-inputnumber mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"  name="' . $this->name . '" v-model="form.' . $this->id . '" id="' . $this->id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '</div> ';

		return implode('', $html);


	}
}
