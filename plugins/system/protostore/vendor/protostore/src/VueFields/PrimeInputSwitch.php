<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields; use Protostore\VueFields\Field;


// no direct access
defined('_JEXEC') or die('Restricted access');

class PrimeInputSwitch implements Field
{

	public static function get(string $formId, string $label): string
	{

		$html = array();

		$html[] = '<div class="uk-grid uk-margin" uk-grid>';
		$html[] = '<div class="uk-width-1-4 uk-grid-item-match uk-flex-middle">';
		$html[] =  Text::_($label);
		$html[] = '</div>';
		$html[] = '<div class="uk-width-3-4">';
		$html[] = '<p-inputswitch @change="getSellPrice()" v-model="form.' . $formId . '" id="' . $formId . '" @change="logIt"></p-inputswitch>';
		$html[] = '</div>';
		$html[] = '</div>';

		return implode('', $html);
	}


}
