<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

use Protostore\Render\Render;


/**
 *
 * @since 2.0
 */
class bootstrapWrapper
{


	public function __construct()
	{
		$this->init();
		$vars = $this->setVars();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/wrapper/wrapper.php', $vars);

	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function init()
	{

	}


	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	private function setVars()
	{

		$vars = array();

		$vars['breadcrumbs'] = $this->getBreadcrumbs();


		return $vars;


	}

	private function getBreadcrumbs()
	{

		$breadcrumbs = array();


		$input = Factory::getApplication()->input;
		$view  = $input->getString('view');

		$breadcrumbs[] = $view;

//		if ($id = $input->get('id'))
//		{
//			$breadcrumbs[] = $this->getBreadcrumbItem($view, $id);
//		}

		return $breadcrumbs;


	}

	private function getBreadcrumbItem($view, $id)
	{

	}


}

