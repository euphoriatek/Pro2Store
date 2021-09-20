<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
