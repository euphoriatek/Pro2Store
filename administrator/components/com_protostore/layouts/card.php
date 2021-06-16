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


use Joomla\CMS\Language\Text;

$data = $displayData;
$form = $data['form'];

?>

<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom" :class="{ ukcardwarning: hasError<?= $data['cardId']; ?> }">
    <div class="uk-card-header">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-expand">
                <h3>
					<?= Text::_($data['cardTitle']); ?>
                </h3>
            </div>

        </div>
    </div>

    <div class="uk-card-body">
		<?php if (isset($data['field_grid_width'])): ?>
        <div class="uk-grid uk-child-width-<?= $data['field_grid_width']; ?>" uk-grid>
			<?php endif; ?>


			<?php foreach ($data['fields'] as $field) : ?>
                <div class="">
                    <?php $form->setFieldAttribute($field, 'autofocus', 'p2s_product_form.' . $field, null); ?>
					<?php echo $form->renderField($field); ?>
                </div>
			<?php endforeach; ?>

			<?php if (isset($data['field_grid_width'])): ?>
        </div>
	<?php endif; ?>

    </div>
    <div class="uk-card-footer"></div>
</div>
