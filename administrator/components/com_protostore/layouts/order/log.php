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

$data  = $displayData;
$order = $data['item'];

?>


<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom">
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

        <p-timeline :value="order.logs">

            <template #opposite="slotProps" class="uk-margin-medium-bottom">
                <small class="p-text-secondary">{{slotProps.item.created}}</small>
            </template>
            <template #content="slotProps">
              {{slotProps.item.note}}
            </template>

        </p-timeline>
    </div>


    <div class="uk-card-footer"></div>
</div>
