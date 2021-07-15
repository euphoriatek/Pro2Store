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

//		<MultiSelect v-model="selectedCars2" :options="cars" optionLabel="brand" placeholder="Select a Car">
//	<template #value="slotProps">
//	<div class="p-multiselect-car-token" v-for="option of slotProps.value" :key="option.brand">
//			<img :alt="option.brand" :src="'demo/images/car/' + option.brand + '.png'" />
//			<span>{{option.brand}}</span>
//		</div>
//		<template v-if="!slotProps.value || slotProps.value.length === 0">
//	Select Brands
//	</template>
//	</template>
//	<template #option="slotProps">
//	<div class="p-multiselect-car-option">
//			<img :alt="slotProps.option.brand" :src="'demo/images/car/' + slotProps.option.brand + '.png'" />
//			<span>{{slotProps.option.brand}}</span>
//		</div>
//	</template>
//</MultiSelect>



		$html = array();

		$html[] = '<p-multiselect ';
//		$html[] = 'name="' . $this->name . '" ';
		$html[] = 'optionLabel="title" ';
		$html[] = 'dataKey="id" ';
		$html[] = 'display="chip" ';
		$html[] = 'placeholder="Tags" ';
		$html[] = 'modelValue="Tags" ';
		$html[] = ':options="available_tags" ';
		$html[] = 'v-model="form.' . $this->id . '" ';
//		$html[] = 'id="' . $this->id . '" ';
		$html[] = 'class="" ';
		$html[] = ' >';
		$html[] = '<template #value="slotProps"><div class="p-multiselect-car-token" v-for="option of slotProps.id" :key="option.title"><span>{{option.title}}</span></div></template>';
		$html[] = ' </p-multiselect>';

		return implode('', $html);


	}
}
