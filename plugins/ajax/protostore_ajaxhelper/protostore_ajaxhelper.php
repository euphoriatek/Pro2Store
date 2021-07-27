<?php
/**
 * @package   The Hub - Ajax Helper
 * @author    Ray Lawlor
 * @copyright Copyright (C) 2021 Ray Lawlor
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');


error_reporting(0);

use Joomla\CMS\Factory;
use Joomla\CMS\Response\JsonResponse;


class plgAjaxProtostore_ajaxhelper extends JPlugin
{

	private $db;
	private $app;
	private $input;

	public function onAjaxProtostore_ajaxhelper()
	{

		// init vars

		$this->db    = Factory::getDbo();
		$this->app   = Factory::getApplication();
		$this->input = $this->app->input;

		// get task

		$task = $this->input->getString('task');

		// call function based on specified task.
		// all functions must return new JsonResponse.

		switch ($task)
		{
			case 'task':
				return $this->_initTask();

		}

		return true;
	}

	/**
	 *
	 * function _initTask()
	 *
	 * Ajax handler for running the specified task
	 *
	 *
	 * @return JsonResponse
	 * @throws Exception
	 * @since 1.0
	 */


	private function _initTask()
	{

		$taskType = $this->input->get('type');
		$taskType = explode('.', $taskType);
		require_once JPATH_ADMINISTRATOR . '/components/com_protostore/tasks/' . $taskType[0] . '/' . $taskType[1] . '.php';
		$class = 'protostoreTask_' . $taskType[1];

		$taskWorker = new $class();

		try
		{
			$response = $taskWorker->getResponse($this->input);

			return new JsonResponse($response);
		}
		catch (Exception $e)
		{
			return new JsonResponse('ko', $e->getMessage(), true);
		}


	}


}


