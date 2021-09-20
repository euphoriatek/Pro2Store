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
use Joomla\CMS\Layout\LayoutHelper;

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
                <div class="uk-inline uk-text-left">
                    <button class="uk-button uk-button-small button-success" type="button" @click="addOption()">
                        <span uk-icon="plus-circle"></span>
                        <?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_ADD_BUTTON'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="uk-card-body" >
	    <?= LayoutHelper::render('product/options', ''); ?>
    </div>
</div>
