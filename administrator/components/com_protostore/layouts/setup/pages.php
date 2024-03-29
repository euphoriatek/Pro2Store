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

<div class="uk-card uk-card-default  uk-margin-bottom">
    <div class="uk-card-header">
        <h3><?= Text::_('COM_PROTOSTORE_SETUP_PAGES_SETUP'); ?></h3></div>
    <div class="uk-card-body">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-1-2">
                <div class="uk-margin">
                    <div class="uk-alert-primary" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
					    <?= Text::_('COM_PROTOSTORE_SETUP_PAGES_SETUP_ONLY_CREATE_NEW_PAGES'); ?>
                    </div>

                </div>
            </div>
            <div class="uk-width-1-2"></div>
        </div>
        <div class="uk-grid uk-grid-collapse uk-margin-bottom" uk-grid="">
            <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                <input class="uk-checkbox" type="checkbox" checked v-model="createCheckout"
                       style="width: 20px!important; height: 20px; border-radius: 3px; ">
            </div>
            <div class="uk-width-expand uk-grid-item-match uk-flex-middle">
                <span class="uk-h6">  &nbsp;&nbsp;<?= Text::_('COM_PROTOSTORE_SETUP_CREATE_CHECKOUT_PAGE_BUTTON'); ?> </span>
            </div>
        </div>
        <div class="uk-grid uk-grid-collapse uk-margin-bottom" uk-grid="">
            <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                <input class="uk-checkbox" type="checkbox" v-model="createConfirmation"
                       style="width: 20px!important; height: 20px; border-radius: 3px; ">
            </div>
            <div class="uk-width-expand uk-grid-item-match uk-flex-middle">
                <span class="uk-h6">  &nbsp;&nbsp;<?= Text::_('COM_PROTOSTORE_SETUP_CREATE_CONFIRMATION_PAGE_BUTTON'); ?> </span>
            </div>
        </div>
        <div class="uk-grid uk-grid-collapse uk-margin-bottom" uk-grid="">
            <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                <input class="uk-checkbox" type="checkbox" v-model="createTandcs"
                       style="width: 20px!important; height: 20px; border-radius: 3px; ">
            </div>
            <div class="uk-width-expand uk-grid-item-match uk-flex-middle">
                <span class="uk-h6">  &nbsp;&nbsp;<?= Text::_('COM_PROTOSTORE_SETUP_CREATE_TERMS_PAGE_BUTTON'); ?> </span>
            </div>
        </div>
        <div class="uk-grid uk-grid-collapse uk-margin-bottom" uk-grid="">
            <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                <input class="uk-checkbox" type="checkbox" v-model="createCancel"
                       style="width: 20px!important; height: 20px; border-radius: 3px; ">
            </div>
            <div class="uk-width-expand uk-grid-item-match uk-flex-middle">
                <span class="uk-h6">  &nbsp;&nbsp;<?= Text::_('COM_PROTOSTORE_SETUP_CREATE_CANCEL_PAGE_BUTTON'); ?> </span>
            </div>
        </div>



    </div>
</div>
