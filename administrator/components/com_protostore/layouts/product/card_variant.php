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
                <button type="button" v-show="showVariantItemsBlock"
                        class="uk-button uk-button-small uk-button-default"
                        @click="variantsStartOver">Start Over
                    <span uk-icon="icon: refresh"></span>
                </button>
            </div>

        </div>
    </div>

    <div class="uk-card-body">


		<?= LayoutHelper::render('product/variants', array()); ?>

    </div>
    <div class="uk-card-footer">
        <div class="uk-grid" uk-grid>
            <div class="uk-width-expand"></div>
            <div class="uk-width-auto">

                <button class="uk-button uk-button-link" type="button">
                    <spa uk-icon="icon: settings" uk-tooltip="Advanced Options"></spa>
                </button>
                <div uk-dropdown="mode: click">
                    <ul class="uk-nav uk-dropdown-nav">
                        <li class="uk-active"><a href="#">Active</a></li>
                        <li><a href="#">Item</a></li>
                        <li class="uk-nav-header">Header</li>
                        <li><a href="#">Item</a></li>
                        <li><a href="#">Item</a></li>
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Item</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
