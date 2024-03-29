<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Language\Text;



?>

<div class="uk-card uk-card-default uk-margin-bottom">
	<div class="uk-card-header">
		<h3><?= Text::_('COM_PROTOSTORE_SETUP_SETUPACOUNTRY'); ?></h3></div>
	<div class="uk-card-body">


		<div class="uk-margin">
			<label class="uk-form-label"
			       for="form-stacked-select"><?= Text::_('COM_PROTOSTORE_SETUP_CHOOSE_A_DEFAULT_COUNTRY'); ?></label>
			<div class="uk-form-controls">
				<select class="uk-select" v-model="selectedCountry">
					<option v-for="country in countries" :value="country.id">
						{{country.country_name}}
					</option>
				</select>
			</div>
		</div>

	</div>
</div>
