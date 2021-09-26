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
 * Clicks field.
 *
 * @since 2.0
 */
class JFormFieldTitle extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Title';

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

		$html[] = '<input class="input-small ' . $this->class . '" type="text" ';
		$html[] = 'name="' . $this->name . '" ';
		$html[] = 'v-model="form.' . $this->id . '" ';
		$html[] = ' required ';
		$html[] = 'id="' . $this->id . '" ';
		$html[] = ' />';

		return implode('', $html);


	}
}
