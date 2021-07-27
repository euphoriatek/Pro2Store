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
                <button type="button"
                        class="uk-button uk-button-small uk-button-default button-success"
                     >Add File
                    <span uk-icon="icon: cloud-upload"></span>
                </button>
            </div>

        </div>
    </div>

    <div class="uk-card-body">

        <table class="uk-table uk-table-hover uk-table-striped uk-table-divider uk-table-middle">
            <thead>
            <tr>
                <th class="">File</th>
                <th class="uk-table-shrink">Is Joomla</th>
                <th class="uk-width-1-5">Version</th>
                <th class="uk-width-1-5">Type</th>
                <th class="uk-width-1-5">PHP</th>
                <th class="uk-width-1-5">Stability</th>
                <th class="uk-table-1-5">Controls</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                   Somefilename.zip
                </td>
                <td class="">
                   <input type="checkbox" checked>
                </td>
                <td class="">
                    <input class="uk-input uk-form-small uk-width-3-5" type="text" placeholder="Version" value="1.6.5">
                </td>
                <td class="">
                    <select class="uk-select">
                        <option>Plugin</option>
                        <option>Module</option>
                        <option>Component</option>
                        <option>Library</option>
                        <option>Package</option>
                    </select>
                </td>
                <td class="">
                    <input class="uk-input uk-form-small uk-width-3-5" type="number" step="0.1" placeholder="Minium PHP Version" value="7.4">
                </td>
                <td class="">
                    <select class="uk-select">
                        <option>Alpha</option>
                        <option>Beta</option>
                        <option>Release Candidate</option>
                        <option>Stable</option>
                    </select>
                </td>
                <td class="">
                    <span uk-icon="icon: file-edit"></span>
                    <span uk-icon="icon: trash"></span>
                </td>
            </tr>
            </tbody>
        </table>


    </div>
    <div class="uk-card-footer"></div>
</div>
