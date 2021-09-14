<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

$type = Factory::getApplication()->input->get('paymenttype');

PluginHelper::importPlugin('protostorepayment');
$payload = @file_get_contents('php://input');
Factory::getApplication()->triggerEvent('onHook' . $type, array('payload' => $payload, 'post' => $_POST));

return;
