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

/** @var array $vars */

?>


<div id="p2s_setup">
    <form @submit.prevent="submitSetup">
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">

                <div class="uk-width-1-1 uk-animation-fade">

                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header uk-text-center">
							<?= LayoutHelper::render('svglogo'); ?>
                            <h1 class="uk-text-center">
								<?= Text::_('COM_PROTOSTORE_SETUP_WELCOME'); ?>
                            </h1>
                        </div>
                        <div class="uk-card-body">

                            <div class="uk-margin">
                                <div class="uk-alert-primary" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
									<span class="uk-text-large"><?= Text::_('COM_PROTOSTORE_SETUP_INFO'); ?></span>
                                </div>

                            </div>


							<?= LayoutHelper::render('setup/shop'); ?>
							<?= LayoutHelper::render('setup/currency'); ?>
							<?= LayoutHelper::render('setup/country'); ?>
							<?= LayoutHelper::render('setup/pages'); ?>

                        </div>
                        <div class="uk-card-footer">
                            <div class="uk-text-center">
                                <button class="uk-button uk-button-large uk-button-primary"
                                        type="submit"><?= Text::_('COM_PROTOSTORE_SETUP_DONE_GET_STARTED'); ?></button>
                            </div>
                        </div>
                    </div>


                </div>

            </div>

        </div>
    </form>
</div>
