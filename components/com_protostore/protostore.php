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

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

$type = Factory::getApplication()->input->get('paymenttype');

PluginHelper::importPlugin('protostorepayment');
$payload = @file_get_contents('php://input');
Factory::getApplication()->triggerEvent('onHook' . $type, array('payload' => $payload, 'post' => $_POST));

return;
