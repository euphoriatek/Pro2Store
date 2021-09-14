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
//require_once(__DIR__ . '/vendor/autoload.php');


use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\Input\Input;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filter\OutputFilter;

use Protostore\Bootstrap\AdminViewExtended;

use Protostore\Product\ProductFactory;
use Protostore\User\UserFactory;
use Protostore\Address\AddressFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Customer\CustomerFactory;

use Protostore\Sidebarlink\Sidebarlink;
use Protostore\Utilities\Utilities;


class plgProtostore_extendedIo extends CMSPlugin implements AdminViewExtended
{

	public $app;

	/**
	 * @var Input
	 * @since 1.0
	 */
	public $input;

	public function onAfterInitialise()
	{


	}

	/**
	 *
	 * @return Sidebarlink
	 *
	 * @since 1.6.0
	 */


	public function onGetSidebarLink(): Sidebarlink
	{
		$menuItem = new \stdClass();

		$menuItem->view     = 'io';
		$menuItem->linkText = 'Import/Export';
		$menuItem->icon     = '<svg width="16px" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="file-import" class="svg-inline--fa fa-file-import fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M497.9 97.98L414.02 14.1c-9-9-21.2-14.1-33.89-14.1H175.99C149.5.1 128 21.6 128 48.09V288H8c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8h248v52.67c0 10.98 6.38 20.55 16.69 24.97 14.93 6.45 26.88-1.61 30.88-5.39l71.72-68.12c5.62-5.33 8.72-12.48 8.72-20.12s-3.09-14.8-8.69-20.11l-71.78-68.16c-8.28-7.8-20.41-9.88-30.84-5.38-10.31 4.42-16.69 13.98-16.69 24.97V288H160V48.09c0-8.8 7.2-16.09 16-16.09h176.04v104.07c0 13.3 10.7 23.93 24 23.93h103.98v304.01c0 8.8-7.2 16-16 16H175.99c-8.8 0-16-7.2-16-16V352H128v112.01c0 26.49 21.5 47.99 47.99 47.99h288.02c26.49 0 47.99-21.5 47.99-47.99V131.97c0-12.69-5.1-24.99-14.1-33.99zM288 245.12L350 304l-62 58.88V245.12zm96.03-117.05V32.59c2.8.7 5.3 2.1 7.4 4.2l83.88 83.88c2.1 2.1 3.5 4.6 4.2 7.4h-95.48z"></path></svg>';

		return new Sidebarlink($menuItem);


	}

	/**
	 *
	 * @return JsonResponse
	 *
	 * @throws Exception
	 * @since 1.0
	 */


	public function onAjaxIo()
	{

		$this->app   = Factory::getApplication();
		$this->input = $this->app->input;


		return new JsonResponse($this->getResponse($this->input));

	}

	/**
	 * @param   Input  $data
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since 1.0
	 */


	private function getResponse(Input $data)
	{


	}




}