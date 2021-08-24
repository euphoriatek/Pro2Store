<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

$data = $displayData;
$form = $data['form'];
?>

<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom uk-animation-fade">
    <div class="uk-card-header">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-expand">
                <h3>
					<?= Text::_($data['cardTitle']); ?>
                </h3>
            </div>

            <div class="uk-width-auto">

            </div>

        </div>
    </div>

    <div class="uk-card-body">
		<?php if (isset($data['field_grid_width'])): ?>
        <div class="uk-grid uk-child-width-<?= $data['field_grid_width']; ?>" uk-grid>
			<?php endif; ?>


            <div class="uk-margin-bottom">

                <div class="uk-margin uk-card uk-card-body uk-card-default" v-for="field in available_custom_fields">
                    <label class="uk-form-label">{{field.label}}</label>
                    <!-- editor -->
                    <span v-if="field.type === 'editor'">

                        </span>

                    <!-- MEDIA -->
                    <span v-if="field.type === 'media'">

                     <?= LayoutHelper::render('product/custom_media', array(
                     )); ?>


                    </span>

                    <!--TEXTAREA -->
                    <textarea :name="field.name" v-model="field.value"
                              v-if="field.type === 'textarea'" class="uk-textarea"
                              :rows="field.fieldparams.rows" :placeholder="field.label"></textarea>

                    <!-- TEXT OR NUMBER -->
                    <input
                            v-if="field.type === 'text' || field.type === 'number' || field.type === 'tel' || field.type === 'float'"
                            class="uk-input"
                            :type="field.type" v-model="field.value"
                            :step="field.fieldparams.filter == 'float' ? '0.01' : false"
                            :maxlength="field.fieldparams.maxlength"
                            :name="field.name"
                            :value="field.value"
                    >
                    <!-- LIST -->
                    <div class="uk-form-controls" v-if="field.type === 'list'">
                        <div class="uk-margin">
                            <select class="uk-select" v-model="field.value">
                                <option v-for="option in field.options" :value="option.value">
                                    {{option.name}}
                                </option>
                            </select>
                        </div>
                    </div>


                    <!-- RADIO -->
                    <div v-if="field.type === 'radio'" class="uk-grid-small uk-child-width-auto uk-grid">
                        <label v-for="option in field.options">
                            <input class="uk-radio" type="radio"
                                   :name="field.name" checked
                                   v-model="field.value"
                            > {{option.name}}
                        </label>
                    </div>


                </div>
            </div>


			<?php if (isset($data['field_grid_width'])): ?>
        </div>
	<?php endif; ?>

    </div>
    <div class="uk-card-footer"></div>
</div>
