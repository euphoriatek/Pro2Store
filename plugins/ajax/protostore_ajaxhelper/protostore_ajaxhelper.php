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


// error_reporting(0);

use Joomla\CMS\Factory;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Filesystem\File;


class plgAjaxProtostore_ajaxhelper extends JPlugin
{

	private $db;
	private $app;
	private $input;


	/**
	 * @throws Exception
	 * @since 2.0
	 */
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
			case 'upload':
				return $this->_upload();

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


	private function _initTask(): JsonResponse
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

	private function _upload()
	{


		$md5_1 = md5(uniqid());
		$md5_2 = md5(uniqid());
		$md5_3 = md5(uniqid());
		$md5_4 = md5(uniqid());

		$path = $md5_1 . '/' . $md5_2 . '/' . $md5_3 . '/' . $md5_4;

		$file = $this->input->files->get('files');
		$file = $file[0];

		jimport('joomla.filesystem.file');
		$filename = File::makeSafe($file['name']);
		$src      = $file['tmp_name'];

		$dest = JPATH_SITE . '/images/' . $path . '/' . $filename;
		if (File::upload($src, $dest))
		{

			$response['uploaded']     = true;
			$response['path']         = $path;
			$response['relativepath'] = $path . '/' . $filename;
			$response['dest']         = $dest;

			// now update the database for this item

//			$object = new stdClass();
//			$object->id = 0;
//			$object->product_id = $this->input->getInt('productid');
//			$object->filename = $filename;
//			$object->filename_obscured = $response['path'];
//			$object->isjoomla = $this->input->get('isjoomla');
//			$object->version = $this->input->get('version');
//			$object->type = $this->input->get('type');
//			$object->stability_level = $this->input->get('stability_level');
//			$object->php_min = $this->input->get('php_min');
//			$object->download_access = $this->input->get('download_access');
//			$object->published = $this->input->get('published');
//			$object->created = \Protostore\Utilities\Utilities::getDate();
//
//			$result = Factory::getDbo()->updateObject('#__protostore_product_file', $object, 'id');
//
//			if($result) {
//				$response['dbupdated'] = true;
//			}

			return new JsonResponse($response, 'Uploaded');
		}
		else
		{
			return new JsonResponse('', '', 'Unable to upload file');
		}


	}


}


