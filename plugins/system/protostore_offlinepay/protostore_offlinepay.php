<?php
/**
 * @package     Pro2Store - Offline Pay
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;

use YOOtheme\Application;
use YOOtheme\Path;

class plgSystemProtostore_offlinepay extends CMSPlugin
{

    public function onAfterInitialise()
    {

        if (!ComponentHelper::getComponent('com_protostore', true)->enabled) {
            return;
        }

        if (!PluginHelper::isEnabled('system', 'protostore')) {
            return;
        }

        if (class_exists(Application::class, false)) {

            $app = Application::getInstance();

            $root = __DIR__;
            $rootUrl = Uri::root(true);

            $themeroot = Path::get('~theme');
            $loader = require "{$themeroot}/vendor/autoload.php";
            $loader->setPsr4("YpsApp_offlinepay\\", __DIR__."/modules/offlinepay");

            // set alias
            Path::setAlias('~protostore_offlinepay', $root);
            Path::setAlias('~protostore_offlinepay:rel', $rootUrl . '/plugins/system/protostore_offlinepay');

            // bootstrap modules
            $app->load('~protostore_offlinepay/modules/offlinepay/bootstrap.php');

        }

    }
}
