<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Bootstrap;

// no direct access
defined('_JEXEC') or die('Restricted access');


interface genericView
{

	/**
	 *
	 * @return mixed
	 *
	 * @since 1.6
	 */
	public function init(): void;

	/**
	 *
	 * @return void
	 *
	 * @since 1.6
	 */
	public function setVars(): void;

	/**
	 *
	 * @return void
	 *
	 * @since v1.6
	 */
	public function addScripts(): void;

	/**
	 *
	 * @return void
	 *
	 * @since v1.6
	 */
	public function addStylesheets(): void;

	/**
	 *
	 * @return void
	 *
	 * @since v1.6
	 */
	
	public function addTranslationStrings(): void;




}
