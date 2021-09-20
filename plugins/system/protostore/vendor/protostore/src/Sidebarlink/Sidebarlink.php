<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Sidebarlink;

// no direct access
defined('_JEXEC') or die('Restricted access');


class Sidebarlink
{
	public $view;
	public $linkText;
	public $icon;

	public function __construct($data)
	{

		$this->view = $this->_setView($data->view);
		$this->linkText = $data->linkText;
		$this->icon = $data->icon;

	}

	/**
	 * @param   string  $view
	 *
	 * @return string
	 *
	 * @since 1.6
	 */


	private function _setView(string $view): string
	{
		return 'index.php?option=com_protostore&view=' . $view;
	}

}
