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
require_once(__DIR__ . '/vendor/autoload.php');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;


use Protostore\Setup\SetupFactory;
use Protostore\Currency\CurrencyFactory;

use YOOtheme\Application;
use YOOtheme\Path;


class plgSystemProtostore extends CMSPlugin
{

	public function onAfterInitialise()
	{

		if (!ComponentHelper::getComponent('com_protostore', true)->enabled)
		{
			return;
		}

		//import VUE on the frontend
		$app = Factory::getApplication();
		$doc = Factory::getDocument();
		if ($app->isClient('site'))
		{
			$doc->addScript('media/com_protostore/js/vue/bundle.min.js', array('type' => 'text/javascript'));
			$doc->addStyleDeclaration('[v-cloak] {display: none}');

		}
		$doc->addStyleDeclaration('[v-cloak] {display: none}');

		// set the Pro2Store Cookie
		$value = $app->input->cookie->get('yps-cart', null);
		if ($value == null)
		{
			$value = md5(Factory::getSession()->getId());
			$time  = 0;
			$app->input->cookie->set(
				'yps-cart',
				$value,
				$time,
				$app->get('cookie_path', '/'),
				$app->get('cookie_domain'),
				$app->isSSLConnection()
			);
		}

		//check if the setup is done

		if (SetupFactory::isSetup())
		{
			$value = $app->input->cookie->get('yps-currency', null);
			if ($value == null)
			{
				$currency = CurrencyFactory::getDefault();
				$app->input->cookie->set('yps-currency', $currency->id, 0, $app->get('cookie_path', '/'), $app->get('cookie_domain'), $app->isSSLConnection());
			}
		}


		if (class_exists(Application::class, false))
		{

			$app = Application::getInstance();

			$root    = __DIR__;
			$rootUrl = Uri::root(true);

			$themeroot = Path::get('~theme');
			$loader    = require "{$themeroot}/vendor/autoload.php";
			$loader->setPsr4("YpsApp\\", __DIR__ . "/modules/core");

			// set alias
			Path::setAlias('~protostore', $root);
			Path::setAlias('~protostore:rel', $rootUrl . '/plugins/system/protostore');

			// bootstrap modules
			$app->load('~protostore/modules/core/bootstrap.php');

		}

	}
}
