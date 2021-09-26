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
class JFormFieldTags extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Tags';

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

		$html[] = '<div class="uk-grid">';
		$html[] = '<div class="uk-width-1-1">';
		$html[] = '<p-chips ';
		$html[] = 'name="' . $this->name . '" ';
		$html[] = '@remove="addBackToAvailable($event)" ';
		$html[] = 'v-model="form.' . $this->id . '" ';
		$html[] = 'id="' . $this->id . '" ';
		$html[] = 'class="" ';
		$html[] = ' >';
		$html[] = ' </p-chips>';
		$html[] = '</div>';
		$html[] = '<div class="uk-width-1-1 uk-margin-top">';
		$html[] = '<span  v-for="(tag, index) in available_tags"><p-chip @click="addTagFromChip(tag, index)"  :label="tag" style="cursor: pointer"></p-chip></span>';
		$html[] = '</div>';
		$html[] = '</div>';

		return implode('', $html);


	}
}
