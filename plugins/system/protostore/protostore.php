<?php
/**
 * @package     Pro2Store - System Helper
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(__DIR__ . '/vendor/autoload.php');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

use Protostore\Currency\Currency;

use YOOtheme\Application;
use YOOtheme\Path;


class plgSystemProtostore extends CMSPlugin
{

    public function onAfterInitialise()
    {

		if(!ComponentHelper::getComponent('com_protostore', true)->enabled) {
			return;
		}

        //import VUE on the frontend
        $app = Factory::getApplication();
        if ($app->isClient('site')) {
            Factory::getDocument()->addScript('media/com_protostore/js/vue/bundle.min.js', array('type' => 'text/javascript'));
            Factory::getDocument()->addStyleDeclaration('[v-cloak] {display: none}');
        }
	    Factory::getDocument()->addStyleDeclaration('[v-cloak] {display: none}');

        // set the Pro2Store Cookie
	//        $value = Factory::getApplication()->input->cookie->get('yps-cart', null);
	//        if ($value == null) {
	//            $value = md5(Factory::getSession()->getId());
	//            $time = 0;
	//            Factory::getApplication()->input->cookie->set(
	//            	'yps-cart',
	//	            $value,
	//	            $time,
	//	            Factory::getApplication()->get('cookie_path', '/'),
	//	            Factory::getApplication()->get('cookie_domain'),
	//	            Factory::getApplication()->isSSLConnection()
	//            );
	//        }

        //check if the setup is done
//        $setup = new Setup();
//        if ($setup->issetup === 'true') {
//            // set the Pro2Store Currency Cookie
//            $value = Factory::getApplication()->input->cookie->get('yps-currency', null);
//            if ($value == null) {
//
//
//                $currencyHelper = new Currency();
//
//                //check... in case user hasn't set a default currency
//                if ($id = $currencyHelper->_getDefaultCurrencyFromDB()->id) {
//                } else {
//                    //if no default currency... get the first published currency.
//                    $id = $currencyHelper->_getAPublishedCurrency()->id;
//                }
//
//                Factory::getApplication()->input->cookie->set('yps-currency', $id, 0, Factory::getApplication()->get('cookie_path', '/'), Factory::getApplication()->get('cookie_domain'), Factory::getApplication()->isSSLConnection());
//            }
//        }


        if (class_exists(Application::class, false)) {

            $app = Application::getInstance();

            $root = __DIR__;
            $rootUrl = Uri::root(true);

            $themeroot = Path::get('~theme');
            $loader = require "{$themeroot}/vendor/autoload.php";
            $loader->setPsr4("YpsApp\\", __DIR__ . "/modules/core");

            // set alias
            Path::setAlias('~protostore', $root);
            Path::setAlias('~protostore:rel', $rootUrl . '/plugins/system/protostore');

            // bootstrap modules
            $app->load('~protostore/modules/core/bootstrap.php');

        }

    }
}
