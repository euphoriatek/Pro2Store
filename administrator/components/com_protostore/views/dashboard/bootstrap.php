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
class bootstrap
{


	public function __construct()
	{
		$this->init();
		$vars = $this->setVars();

		echo Render::render(JPATH_ADMINISTRATOR . '/components/com_protostore/views/dashboard/dashboard.php', $vars);

	}

	/**
	 *
	 *
	 * @since 2.0
	 */

	private function init()
	{

		Factory::getDocument()->addStyleSheet("https://unpkg.com/primevue/resources/primevue.min.css");
		Factory::getDocument()->addStyleDeclaration('.container-fluid {padding-right: 0;padding-left: 0;}.p-timeline .p-timeline-event-marker {border: 2px solid #2196F3;border-radius: 50%;width: 1rem;height: 1rem;background-color: #ffffff;}.p-timeline .p-timeline-event-connector {background-color: #dee2e6;}.p-timeline.p-timeline-vertical .p-timeline-event-opposite,.p-timeline.p-timeline-vertical .p-timeline-event-content {padding: 0 1rem;}.p-timeline.p-timeline-vertical .p-timeline-event-connector {width: 2px;}.p-timeline.p-timeline-horizontal .p-timeline-event-opposite,.p-timeline.p-timeline-horizontal .p-timeline-event-content {padding: 1rem 0;}.p-timeline.p-timeline-horizontal .p-timeline-event-connector {height: 2px;}');
		Factory::getDocument()->addScript('https://unpkg.com/primevue@3.3.5/timeline/timeline.min.js', array('type' => 'text/javascript'));


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


		$vars['items'] = $this->getItems();


		return $vars;


	}

	/**
	 *
	 * @return array|false
	 *
	 * @since 2.0
	 */

	private function getItems()
	{

	}

}

