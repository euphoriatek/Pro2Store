<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Protostore\Tandcs\TandcsFactory;

class protostoreTask_toggle
{

	public function getResponse($data)
	{

		// init
		$response = array();


		$response['toggle'] = TandcsFactory::toggle($data->get('state'));


		return $response;
	}

}