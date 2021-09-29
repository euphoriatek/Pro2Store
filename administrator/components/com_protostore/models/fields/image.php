<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Image field.
 *
 * @since  2.0
 */
class JFormFieldImage extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Image';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since 2.0
	 */
	protected function getInput()
	{


		return LayoutHelper::render('product/modals/media_manager', array('id' => $this->id));



	}
}
