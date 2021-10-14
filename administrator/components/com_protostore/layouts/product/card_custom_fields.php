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

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;


/** @var array $displayData */
$data          = $displayData;
$custom_fields = $data['custom_fields'];
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

				<?php foreach ($custom_fields as $key => $field) : ?>

                    <div class="uk-margin uk-card uk-card-body uk-card-default">
                        <label class="uk-form-label"><?= $field->label; ?></label>

						<?php if ($field->type === 'editior') : ?>
                            <!-- editor -->
                            <span>
                             EDITOR?!
                        </span>
						<?php endif; ?>
                        <!-- MEDIA -->
						<?php if ($field->type === 'media') : ?>

							<?= LayoutHelper::render('product/modals/media_manager', array('id' => $field->id, 'isCustom' => true, 'key' => $key)); ?>

						<?php endif; ?>
						<?php if ($field->type === 'textarea') : ?>
                            <!--TEXTAREA -->
                            <textarea :name="<?= $field->name; ?>" v-model="custom_fields[<?= $key; ?>].value"
                                      class="uk-textarea"
                                      :rows="custom_fields[<?= $key; ?>].fieldparams.rows"
                                      :placeholder="<?= $field->label; ?>"></textarea>

						<?php endif; ?>
						<?php if ($field->type === 'text' || $field->type === 'number' || $field->type === 'tel' || $field->type === 'float') : ?>
                            <!-- TEXT OR NUMBER -->

                            <input
                                    class="uk-input"
                                    :type="custom_fields[<?= $key; ?>].type"
                                    v-model="custom_fields[<?= $key; ?>].value"
                                    :step="custom_fields[<?= $key; ?>].fieldparams.filter == 'float' ? '0.01' : false"
                                    :maxlength="custom_fields[<?= $key; ?>].fieldparams.maxlength"
                                    :name="custom_fields[<?= $key; ?>].name"
                                    :value="custom_fields[<?= $key; ?>].value"/>

						<?php endif; ?>
						<?php if ($field->type === 'list') : ?>
                            <!-- LIST -->

                            <div class="uk-form-controls">
                                <div class="uk-margin">
                                    <select class="uk-select" v-model="custom_fields[<?= $key; ?>].value">
                                        <option v-for="option in custom_fields[<?= $key; ?>].options"
                                                :value="option.value">
                                            {{option.name}}
                                        </option>
                                    </select>
                                </div>
                            </div>

						<?php endif; ?>
						<?php if ($field->type === 'radio') : ?>
                            <!-- RADIO -->

                            <div class="uk-grid-small uk-child-width-auto uk-grid">
                                <label v-for="option in custom_fields[<?= $key; ?>].options">
                                    <input class="uk-radio" type="radio"
                                           :name="custom_fields[<?= $key; ?>].name"
                                           :checked="custom_fields[<?= $key; ?>].default_value == option.value"
                                           v-model="custom_fields[<?= $key; ?>].value"
                                    > {{option.name}}
                                </label>
                            </div>

						<?php endif; ?>


                    </div>

				<?php endforeach; ?>

            </div>


			<?php if (isset($data['field_grid_width'])): ?>
        </div>
	<?php endif; ?>

    </div>
    <div class="uk-card-footer"></div>
</div>
