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
 * @since 2.0
 */
class JFormFieldShippingmode extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Shippingmode';

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

		$html[] = '<select name="jform_shipping_mode" v-model="form.jform_shipping_mode" id="jform_shipping_mode">';
		$html[] = '<option :selected="form.jform_shipping_mode == \'none\'" value="none">' . Text::_('COM_PROTOSTORE_SHIPPING_NO_SHIPPING_FEE') . '</option>';
		$html[] = '<option :selected="form.jform_shipping_mode == \'flat\'" value="flat">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_MODE_FLAT_RATE') . '</option>';
		$html[] = '<option :selected="form.jform_shipping_mode == \'weight\'" value="weight">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_MODE_BY_WEIGHT') . '</option>';
		$html[] = '</select>';

		return implode('', $html);

	}
}
