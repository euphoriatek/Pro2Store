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

$model = ($data['isCustom'] ? 'custom_fields[' . $data['key'] . '].value' : 'form.' . $data['id']);

?>

<div class="uk-grid" uk-grid="">
    <div class="uk-width-1-1">

        <div class="uk-grid uk-grid-small" uk-grid="">
            <div class="uk-width-expand">
                <input class="uk-input" v-model="<?= $model; ?>">
            </div>
            <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                <button type="button" uk-toggle="target: #mediaField<?= $data['id']; ?>"
                        class="uk-button uk-button-default uk-button-small"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_SELECT'); ?>
                </button>
            </div>
        </div>

    </div>
    <div class="uk-width-1-1">
        <a uk-toggle="target: #mediaField<?= $data['id']; ?>">
            <img v-show="<?= $model; ?> !== null && <?= $model; ?> !== ''"
                 :src="'<?= \Joomla\CMS\Uri\Uri::root(); ?>' + <?= $model; ?>"
                 width="400">
        </a>
    </div>
</div>


<div id="mediaField<?= $data['id']; ?>" class="uk-modal uk-modal-container uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical" style="">
        <button class="uk-modal-close-outside" type="button" uk-close></button>
        <div class="uk-modal-header">
            <div class="uk-grid">
                <div class="uk-width-expand">
                    <div class="uk-grid">
                        <div class="uk-width-auto">
                            <h5 class=""><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_LABEL'); ?>
                            <span v-show="mediaLoading">
                            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="spinner-third" class="svg-inline--fa fa-spinner-third fa-w-16 fa-spin" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M460.115 373.846l-6.941-4.008c-5.546-3.202-7.564-10.177-4.661-15.886 32.971-64.838 31.167-142.731-5.415-205.954-36.504-63.356-103.118-103.876-175.8-107.701C260.952 39.963 256 34.676 256 28.321v-8.012c0-6.904 5.808-12.337 12.703-11.982 83.552 4.306 160.157 50.861 202.106 123.67 42.069 72.703 44.083 162.322 6.034 236.838-3.14 6.149-10.75 8.462-16.728 5.011z"></path></svg></h5>
                            </span>
                        </div>
                        <div class="uk-width-auto">
                            <ul class="uk-iconnav uk-margin-small-top">
                                <li>
                                    <button v-show="!singleSelection" @click="editFileName()" type="button" class="uk-button uk-button-link">
                                        <span uk-icon="icon: pencil"></span>
                                    </button>
                                    <button v-show="!singleFolderSelection" @click="editFolderName()" type="button" class="uk-button uk-button-link">
                                        <span uk-icon="icon: pencil"></span>
                                    </button>
                                </li>
                                <li>
                                    <button v-show="somethingIsSelected" @click="trashSelected()" type="button" class="uk-button uk-button-link">
                                        <span uk-icon="icon: trash"></span></button>
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
                                <li>
                                    <button @click="media_view = 'table'" uk-icon="icon: table"></button>
                                </li>
                                <li>
                                    <button @click="media_view = 'thumbs'" uk-icon="icon: thumbnails"></button>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <button @click="addFolder" class="uk-button uk-button-default uk-margin-small-right uk-button-small"
                                    type="button"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_ADD_FOLDER'); ?>
                            </button>
                            <div class="p2s-image-upload" uk-form-custom>
                                <input type="file" multiple @change="uploadImage($event)">
                                <button class="uk-button uk-button-secondary uk-button-small" type="button"
                                        tabindex="-1"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_UPLOAD'); ?> <span
                                            uk-icon="icon: upload"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="uk-modal-body">
            <ul class="uk-breadcrumb">
                <li>
                    <a @click="setToHome()"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_HOME_BREADCRUMB'); ?></a>
                </li>
                <li v-for="(folder, index) in breadcrumbs">
                    <button class="uk-button uk-button-link" @click="openBreadcrumb(folder, index)">{{folder.name}}
                    </button>
                </li>
            </ul>

            <div class="uk-overflow-auto uk-height-large" v-if="media_view === 'thumbs'">

                <div class="uk-grid uk-child-width-1-4@m uk-grid-match" uk-grid>

                    <div v-for="folder in folderTree" v-show="folder.parent == currentParent">
                        <div class="uk-card uk-card-default uk-card-small" @click="openFolder(folder)"
                             style="cursor: pointer">
                            <div class="uk-card-media-top">
                                <span uk-icon="icon: folder; width: 800; height: 800;"></span>
                            </div>
                            <div class="uk-card-body">
                                <p class="uk-text-truncate">{{folder.name}}</p>
                            </div>
                        </div>
                    </div>

                    <template v-for="folder in folderTree">
                        <div v-for="image in folder.images">
                            <div @click="toggleSelectImage(image)" v-if="image.folder_id == currentParent"
                                 :class="selected_images.includes(image) ? 'uk-card uk-card-primary uk-card-small' : 'uk-card uk-card-default uk-card-small'"
                                 style="cursor: pointer">
                                <div class="uk-card-media-top">
                                    <img class=""
                                         :src="'<?= rtrim(\Joomla\CMS\Uri\Uri::root(), '/'); ?>' + image.relname"
                                         style="max-height: 350px">
                                </div>
                                <div class="uk-card-body">
                                    <p class="uk-text-truncate">{{image.name}}</p>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>

            </div>


            <div class="uk-overflow-auto uk-height-large" v-if="media_view === 'table'">
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
                    <thead>
                    <tr>
                        <th class="uk-table-shrink"></th>
                        <th class="uk-table-shrink"></th>
                        <th class="uk-table-expand"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="folder in folderTree">
                        <tr v-if="folder.parent == currentParent">
                            <td>
                                <input class="uk-checkbox" type="checkbox" v-model="selected_folders" :value="folder">
                            </td>
                            <td class="uk-table-link">
                                <a @click="openFolder(folder)">
                                    <span class="uk-icon-button" uk-icon="icon: folder; width: 25; height: 25;"></span>
                                </a>
                            </td>
                            <td class="uk-text-nowrap uk-table-link">
                                <a @click="openFolder(folder)">{{folder.name}}</a>
                            </td>
                        </tr>
                    </template>
                    <template v-for="folder in folderTree">
                        <template v-for="image in folder.images">
                            <tr @click="toggleSelectImage(image)"
                                class="uk-table-link" style="cursor: pointer" v-if="image.folder_id == currentParent">
                                <td><input class="uk-checkbox" type="checkbox" v-model="selected_images" :value="image">
                                </td>
                                <td>
                                    <img class="uk-preserve-width"
                                         :src="'<?= rtrim(\Joomla\CMS\Uri\Uri::root(), '/'); ?>' + image.relname"
                                         width="25"
                                         alt="">
                                </td>
                                <td class="uk-text-nowrap">{{image.name}}</td>
                            </tr>
                        </template>
                    </template>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-primary" type="button" :disabled="singleSelection"
                    @click="selectImage('<?= $data['id']; ?>')">

                <span v-if="!singleSelection"> <?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_SELECT'); ?></span>
                <span v-if="singleSelection"> <?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_SELECT_ONE'); ?></span>

            </button>
            <button class="uk-button uk-button-default uk-modal-close uk-margin-left"
                    type="button"><?= Text::_('COM_PROTOSTORE_MEDIA_MANAGER_CANCEL'); ?></button>
        </div>
    </div>
</div>
