<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields;


// no direct access
defined('_JEXEC') or die('Restricted access');

class Number implements Field
{


	public static function get(string $formId): string
	{
		$html = array();

		$html[] = '<input class="input-small " type="number" ';
		$html[] = 'name="' . $formId . '" ';
		$html[] = 'v-model="form.' . $formId . '" ';
		$html[] = 'id="' . $formId . '" ';
		$html[] = ' />test';

		return implode('', $html);
	}

	public static function getLabel(): string
	{
		return '';
	}
}
