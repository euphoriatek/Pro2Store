<?php
/**
 * @package     Pro2Store - Emailer
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');


use Protostore\Email\EmailFactory;


class plgProtostoresystemProtostore_emailer extends JPlugin
{


	/**
	 * @param   string  $emailType
	 * @param   int     $order_id
	 *
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	public function onSendProtoStoreEmail(string $emailType, int $order_id)
	{

		EmailFactory::send($emailType, $order_id, $this->params->get('layout', 'default'), 'protostore_emailer');

	}


}
