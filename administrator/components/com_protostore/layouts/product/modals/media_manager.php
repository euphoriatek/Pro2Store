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


/** @var array $displayData */
$data = $displayData;


?>
<div id="mediaField<?= $data['id']; ?>" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <div class="uk-grid">

                <div class="uk-width-expand">
                    <div class="uk-grid">
                        <div class="uk-width-auto"><h5
                                    class=""><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_LABEL'); ?></h5></div>
                        <div class="uk-width-auto">
                            <ul class="uk-iconnav uk-margin-small-top">
                                <li>
                                    <button type="button" uk-icon="icon: pencil"></button>
                                </li>
                                <li>
                                    <button type="button" uk-icon="icon: trash"></button>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="uk-width-auto">
                    <div class="uk-margin">
                        <div class="uk-inline">
                            <span class="uk-form-icon" uk-icon="icon: search" style="top: 0px!important;"></span>
                            <input class="uk-input" placeholder="">
                        </div>
                    </div>

                </div>
                <div class="uk-width-auto">
                    <div class="uk-grid">
                        <div>
                            <ul class="uk-iconnav uk-margin-small-top">
                                <li><a uk-icon="icon: table"></a></li>
                                <li><a uk-icon="icon: thumbnails"></a></li>
                                <li><a uk-icon="icon: refresh"></a></li>
                            </ul>
                        </div>
                        <div>
                            <button class="uk-button uk-button-default uk-margin-small-right uk-button-small"
                                    type="button"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_ADD_FOLDER'); ?>
                            </button>
                            <div class="js-upload" uk-form-custom>
                                <input type="file" multiple>
                                <button class="uk-button uk-button-default uk-button-small" type="button"
                                        tabindex="-1"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_UPLOAD'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-modal-body">

        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        </div>
    </div>
</div>

