<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Tags field.
 *
 * @since  1.6
 */
class JFormFieldTags extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Tags';

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