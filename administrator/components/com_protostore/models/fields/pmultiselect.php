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

/**
 * Tags field.
 *
 * @since 2.0
 */
class JFormFieldPmultiselect extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Pmultiselect';

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

		$html[] = '';
		$html[] = '<p-multiselect v-model="selectedCars" :options="cars" optionLabel="brand" optionValue="value" placeholder="Select Brands" display="chip"> </p-p-multiselect>';
//		$html[] = '<p-multiselect ';
////		$html[] = 'name="' . $this->name . '" ';
//		$html[] = 'optionLabel="title" ';
//		$html[] = 'optionValue="id" ';
//		$html[] = 'dataKey="id" ';
//		$html[] = 'display="chip" ';
//		$html[] = 'placeholder="Tags" ';
//		$html[] = ':options="available_tags" ';
//		$html[] = 'v-model="form.' . $this->id . '" ';
//		$html[] = 'id="' . $this->id . '" ';
//		$html[] = ' >';
//		$html[] = ' </p-multiselect>';

		return implode('', $html);


	}
}
