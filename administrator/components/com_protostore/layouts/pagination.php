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

?>

<div class="uk-grid" uk-grid>
    <div class="uk-width-auto">
        <ul class="uk-pagination" uk-margin>
            <li :class="{'uk-disabled': currentPage === 0}">
                <a @click.prevent="currentPage = currentPage-1">
                    <span uk-pagination-previous></span>
                </a>
            </li>

                    <li v-for="(page, index) in pages" v-if="pages > 10">
                        <a v-if="( index > (currentPage - 5) ) && (index < (currentPage + 5))" @click.prevent="changePage(index)">{{page}}</a>
                    </li>

                    <li v-for="(page, index) in pages" v-if="pages < 10">

                        <a @click.prevent="changePage(index)">{{page}}</a>
                    </li>


            <li :class="{'uk-disabled': currentPage === pages - 1}">
                <a @click.prevent="currentPage = currentPage+1">
                    <span uk-pagination-next></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="uk-width-auto">
        <select v-model="show" @change="changeShow"
                class="uk-select uk-form-width-small">
            <option v-for="pagesize in pagesizes" :value="pagesize">Show {{pagesize}}
            </option>

        </select>

    </div>
</div>
