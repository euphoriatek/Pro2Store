<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Vat;
// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\Input\Input;

use PH7\Eu\Vat\Validator;
use PH7\Eu\Vat\Provider\Europa;





class VatFactory
{


	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function validate(Input $data): bool
	{


//		$oVatValidator = new Validator(new Europa, $data->getString('vatNumber'), 'XI');
		$oVatValidator = new Validator(new Europa, 'DE260820446', 'DE');

		if ($oVatValidator->check()) {


			return true;


		} else {
			return false;
		}

	}



}
