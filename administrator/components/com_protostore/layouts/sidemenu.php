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

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

PluginHelper::importPlugin('protostore_extended');

$extensions = Factory::getApplication()->triggerEvent('onGetSidebarLink');

//echo json_encode($extensions);

?>

<div class="left-nav-wrap uk-overflow-auto uk-height-1-1">
    <ul
            class="uk-nav uk-nav-primary uk-nav-parent-icon uk-margin-top"
            data-uk-nav>
        <li>
            <a href="index.php?option=com_protostore">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="home" class="svg-inline--fa fa-home fa-w-18" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path fill="currentColor"
                          d="M541 229.16l-61-49.83v-77.4a6 6 0 0 0-6-6h-20a6 6 0 0 0-6 6v51.33L308.19 39.14a32.16 32.16 0 0 0-40.38 0L35 229.16a8 8 0 0 0-1.16 11.24l10.1 12.41a8 8 0 0 0 11.2 1.19L96 220.62v243a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16v-128l64 .3V464a16 16 0 0 0 16 16l128-.33a16 16 0 0 0 16-16V220.62L520.86 254a8 8 0 0 0 11.25-1.16l10.1-12.41a8 8 0 0 0-1.21-11.27zm-93.11 218.59h.1l-96 .3V319.88a16.05 16.05 0 0 0-15.95-16l-96-.27a16 16 0 0 0-16.05 16v128.14H128V194.51L288 63.94l160 130.57z"></path>
                </svg>
				<?= Text::_('COM_PROTOSTORE_SIDEMENU_HOME'); ?>
            </a>
        </li>
        <li class="uk-parent">
            <a href="#">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="boxes" class="svg-inline--fa fa-boxes fa-w-20" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                    <path fill="currentColor"
                          d="M624 224H480V16c0-8.8-7.2-16-16-16H176c-8.8 0-16 7.2-16 16v208H16c-8.8 0-16 7.2-16 16v256c0 8.8 7.2 16 16 16h608c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16zm-176 32h64v62.3l-32-10.7-32 10.7V256zM352 32v62.3l-32-10.7-32 10.7V32h64zm-160 0h64v106.7l64-21.3 64 21.3V32h64v192H192V32zm0 224v62.3l-32-10.7-32 10.7V256h64zm-160 0h64v106.7l64-21.3 64 21.3V256h80v224H32V256zm576 224H336V256h80v106.7l64-21.3 64 21.3V256h64v224z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_PRODUCTS'); ?>
            </a>
            <ul class="uk-nav-sub" hidden="">
                <li><a href="index.php?option=com_protostore&view=product"> <span
                                uk-icon="icon: plus-circle"></span> <?= Text::_('COM_PROTOSTORE_SIDEMENU_ADD_A_PRODUCT'); ?>
                    </a></li>
                <li>
                    <a href="index.php?option=com_protostore&view=products"><?= Text::_('COM_PROTOSTORE_SIDEMENU_PRODUCT_LIST'); ?></a>
                </li>
                <li>
                    <a href="index.php?option=com_protostore&view=productoptions"><?= Text::_('COM_PROTOSTORE_SIDEMENU_PRODUCT_OPTIONS'); ?></a>
                </li>
                <!-- <li><a href="/optionpresets">Option Presets</a></li>
			  <li><a href="/brands">Brands</a></li> -->
            </ul>
        <li>
            <a href="index.php?option=com_protostore&view=orders">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="box-check" class="svg-inline--fa fa-box-check fa-w-20" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                    <path fill="currentColor"
                          d="M492.5 133.4L458.9 32.8C452.4 13.2 434.1 0 413.4 0H98.6c-20.7 0-39 13.2-45.5 32.8L2.5 184.6c-1.6 4.9-2.5 10-2.5 15.2V464c0 26.5 21.5 48 48 48h400c106 0 192-86 192-192 0-90.7-63-166.5-147.5-186.6zM272 32h141.4c6.9 0 13 4.4 15.2 10.9l28.5 85.5c-3-.1-6-.5-9.1-.5-56.8 0-107.7 24.8-142.8 64H272V32zM83.4 42.9C85.6 36.4 91.7 32 98.6 32H240v160H33.7L83.4 42.9zM48 480c-8.8 0-16-7.2-16-16V224h249.9c-16.4 28.3-25.9 61-25.9 96 0 66.8 34.2 125.6 86 160H48zm400 0c-88.2 0-160-71.8-160-160s71.8-160 160-160 160 71.8 160 160-71.8 160-160 160zm64.6-221.7c-3.1-3.1-8.1-3.1-11.2 0l-69.9 69.3-30.3-30.6c-3.1-3.1-8.1-3.1-11.2 0l-18.7 18.6c-3.1 3.1-3.1 8.1 0 11.2l54.4 54.9c3.1 3.1 8.1 3.1 11.2 0l94.2-93.5c3.1-3.1 3.1-8.1 0-11.2l-18.5-18.7z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_ORDERS'); ?>
            </a>
        </li>
        <li>
            <a href="index.php?option=com_protostore&view=customers">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="users" class="svg-inline--fa fa-users fa-w-20" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                    <path fill="currentColor"
                          d="M544 224c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80 35.8 80 80 80zm0-128c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zM320 256c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm0-192c44.1 0 80 35.9 80 80s-35.9 80-80 80-80-35.9-80-80 35.9-80 80-80zm244 192h-40c-15.2 0-29.3 4.8-41.1 12.9 9.4 6.4 17.9 13.9 25.4 22.4 4.9-2.1 10.2-3.3 15.7-3.3h40c24.2 0 44 21.5 44 48 0 8.8 7.2 16 16 16s16-7.2 16-16c0-44.1-34.1-80-76-80zM96 224c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80 35.8 80 80 80zm0-128c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zm304.1 180c-33.4 0-41.7 12-80.1 12-38.4 0-46.7-12-80.1-12-36.3 0-71.6 16.2-92.3 46.9-12.4 18.4-19.6 40.5-19.6 64.3V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-44.8c0-23.8-7.2-45.9-19.6-64.3-20.7-30.7-56-46.9-92.3-46.9zM480 432c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16v-44.8c0-16.6 4.9-32.7 14.1-46.4 13.8-20.5 38.4-32.8 65.7-32.8 27.4 0 37.2 12 80.2 12s52.8-12 80.1-12c27.3 0 51.9 12.3 65.7 32.8 9.2 13.7 14.1 29.8 14.1 46.4V432zM157.1 268.9c-11.9-8.1-26-12.9-41.1-12.9H76c-41.9 0-76 35.9-76 80 0 8.8 7.2 16 16 16s16-7.2 16-16c0-26.5 19.8-48 44-48h40c5.5 0 10.8 1.2 15.7 3.3 7.5-8.5 16.1-16 25.4-22.4z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_CUSTOMERS'); ?>
            </a>
        </li>
        <li>
            <a href="index.php?option=com_protostore&view=currencies">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="usd-circle" class="svg-inline--fa fa-usd-circle fa-w-16" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                    <path fill="currentColor"
                          d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 464c-119.1 0-216-96.9-216-216S128.9 40 248 40s216 96.9 216 216-96.9 216-216 216zm40.3-221.3l-72-20.2c-12.1-3.4-20.6-14.4-20.6-26.7 0-15.3 12.8-27.8 28.5-27.8h45c11.2 0 21.9 3.6 30.6 10.1 3.2 2.4 7.6 2 10.4-.8l11.3-11.5c3.4-3.4 3-9-.8-12-14.6-11.6-32.6-17.9-51.6-17.9H264v-40c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v40h-7.8c-33.3 0-60.5 26.8-60.5 59.8 0 26.6 18.1 50.2 43.9 57.5l72 20.2c12.1 3.4 20.6 14.4 20.6 26.7 0 15.3-12.8 27.8-28.5 27.8h-45c-11.2 0-21.9-3.6-30.6-10.1-3.2-2.4-7.6-2-10.4.8l-11.3 11.5c-3.4 3.4-3 9 .8 12 14.6 11.6 32.6 17.9 51.6 17.9h5.2v40c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-40h7.8c33.3 0 60.5-26.8 60.5-59.8-.1-26.6-18.1-50.2-44-57.5z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_CURRENCIES'); ?>
            </a>
        </li>
        <li class="uk-parent">
            <a href="#">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="envelope-open-text" class="svg-inline--fa fa-envelope-open-text fa-w-16"
                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor"
                          d="M352 248v-16c0-4.42-3.58-8-8-8H168c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8h176c4.42 0 8-3.58 8-8zm-184-56h176c4.42 0 8-3.58 8-8v-16c0-4.42-3.58-8-8-8H168c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8zm326.59-27.48c-1.98-1.63-22.19-17.91-46.59-37.53V96c0-17.67-14.33-32-32-32h-46.47c-4.13-3.31-7.71-6.16-10.2-8.14C337.23 38.19 299.44 0 256 0c-43.21 0-80.64 37.72-103.34 55.86-2.53 2.01-6.1 4.87-10.2 8.14H96c-17.67 0-32 14.33-32 32v30.98c-24.52 19.71-44.75 36.01-46.48 37.43A48.002 48.002 0 0 0 0 201.48V464c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V201.51c0-14.31-6.38-27.88-17.41-36.99zM256 32c21.77 0 44.64 16.72 63.14 32H192.9c18.53-15.27 41.42-32 63.1-32zM96 96h320v173.35c-32.33 26-65.3 52.44-86.59 69.34-16.85 13.43-50.19 45.68-73.41 45.31-23.21.38-56.56-31.88-73.41-45.32-21.29-16.9-54.24-43.33-86.59-69.34V96zM32 201.48c0-4.8 2.13-9.31 5.84-12.36 1.24-1.02 11.62-9.38 26.16-21.08v75.55c-11.53-9.28-22.51-18.13-32-25.78v-16.33zM480 464c0 8.82-7.18 16-16 16H48c-8.82 0-16-7.18-16-16V258.91c42.75 34.44 99.31 79.92 130.68 104.82 20.49 16.36 56.74 52.53 93.32 52.26 36.45.26 72.27-35.46 93.31-52.26C380.72 338.8 437.24 293.34 480 258.9V464zm0-246.19c-9.62 7.75-20.27 16.34-32 25.79v-75.54c14.44 11.62 24.8 19.97 26.2 21.12 3.69 3.05 5.8 7.54 5.8 12.33v16.3z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_MARKETING'); ?>
            </a>
            <ul class="uk-nav-sub" hidden="">
                <li>
                    <a href="index.php?option=com_protostore&view=discounts"><?= Text::_('COM_PROTOSTORE_SIDEMENU_DISCOUNT_CODES'); ?></a>
                </li>
                <!-- <li><a href="/discount_rules">Discount Rules</a></li>
			  <li><a href="/product_bundles">Product Bundles</a></li> -->
            </ul>
        </li>
        <li class="uk-parent">
            <a href="#">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="globe-europe" class="svg-inline--fa fa-globe-europe fa-w-16" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                    <path fill="currentColor"
                          d="M184 119.2c0-7-5.7-12.7-12.7-12.7h-.1c-3.4 0-6.6 1.3-8.9 3.7l-28.5 28.5c-2.4 2.4-3.7 5.6-3.7 8.9v.1c0 7 5.7 12.7 12.7 12.7h18c3.4 0 6.6-1.3 8.9-3.7l10.5-10.5c2.4-2.4 3.7-5.6 3.7-8.9v-18.1zM248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm48 458.4V432c0-26.5-21.5-48-48-48h-20.2c-3.9 0-13.1-3.1-16.2-5.4l-22.2-16.7c-3.4-2.5-5.4-6.6-5.4-10.8v-23.9c0-4.7 2.5-9.1 6.5-11.6l42.9-25.7c2.1-1.3 4.5-1.9 6.9-1.9h31.2c3.2 0 6.3 1.2 8.8 3.2l52.2 44.8h30.2l17.3 17.3c9.5 9.5 22.1 14.7 35.5 14.7h16.8c-29.9 49.1-78.7 85.3-136.3 98.4zM448.5 336h-32.9c-4.8 0-9.5-1.9-12.9-5.3l-17.3-17.3c-6-6-14.1-9.4-22.6-9.4h-18.3l-43.2-37.1c-8.2-7.1-18.7-10.9-29.6-10.9h-31.2c-8.2 0-16.3 2.2-23.4 6.5l-42.9 25.7c-13.7 8.2-22.1 23-22.1 39v23.9c0 14.3 6.7 27.8 18.2 36.4l22.2 16.7c8.6 6.5 24.6 11.8 35.4 11.8h20.2c8.8 0 16 7.2 16 16v39.2c-5.3.4-10.6.8-16 .8-119.1 0-216-96.9-216-216 0-118.9 96.5-215.6 215.3-216L232 51.1c-10.2 7.7-16 19.2-16 31.4v23.2c0 6.4 3.1 17 5.9 22.3-.8 2.1-21.1 15-24.6 18.5-8.6 8.6-13.3 20-13.3 32.1V195c0 25 20.4 45.4 45.4 45.4h25.3c11 0 21.2-3.9 29.2-10.6 3.9 1.4 8.2 2.1 12.6 2.1h13.4c25.6 0 32.2-20.2 36.1-21.5 5.1 9.1 13.5 16.2 23.5 19.5-4.3 14.2-.9 30.3 10.1 41.6l18.2 19.1c8.7 8.9 20.6 13.9 32.7 13.9h27.7c-2.4 10.8-5.7 21.3-9.7 31.5zm-17.8-63.6c-3.6 0-7.1-1.5-9.6-4L402.6 249a9.93 9.93 0 0 1 .1-14c12.6-12.6 10.5-8.6 10.5-17.8 0-2.5-1-4.9-2.8-6.7l-7.9-7.9c-1.8-1.8-4.2-2.8-6.7-2.8h-13.4c-8.5 0-12.6-10.3-6.7-16.2l7.9-7.3c1.8-1.8 4.2-2.8 6.7-2.8h8.3c5.2 0 9.5-4.2 9.5-9.5v-10.2c0-5.2-4.2-9.5-9.5-9.5h-28.2c-7.4 0-13.4 6-13.4 13.4v5.6c0 5.8-3.7 10.9-9.2 12.7l-26.5 8.8c-4.3 1.4-4.6 5-4.6 8.2 0 3.7-3 6.7-6.7 6.7h-13.4c-3.7 0-6.7-3-6.7-6.7 0-8.4-12.5-8.6-15.3-3-9 12.4-11.5 18.2-19.9 18.2h-25.3c-7.4 0-13.4-6-13.4-13.4v-16.4c0-3.6 1.4-7 3.9-9.5 19.5-14 29.6-17.6 29.6-31.5 0-2.9 1.8-5.5 4.6-6.4l33.6-11.2c1.4-.5 2.7-1.2 3.7-2.3L313.9 95c5-5 3.5-14.9-6.7-14.9h-17.4L276.4 99v6.7c0 3.7-3 6.7-6.7 6.7h-15c-3.7 0-6.7-3-6.7-6.7V82.5c0-2.1 1-4.1 2.7-5.4l44-31.9C391.4 66.7 464 153 464 256c0 5.5-.4 11-.8 16.4h-32.5z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_DISCOUNT_ZONE_AND_TAXES'); ?>
            </a>
            <ul class="uk-nav-sub" hidden="">
                <li>
                    <a href="index.php?option=com_protostore&view=countries"><?= Text::_('COM_PROTOSTORE_SIDEMENU_COUNTRIES'); ?></a>
                </li>
                <li><a href="index.php?option=com_protostore&view=zones">&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?= Text::_('COM_PROTOSTORE_SIDEMENU_ZONES'); ?></a>
                </li>
            </ul>
        </li>
        <li class="uk-parent">
            <a href="#">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="shipping-fast" class="svg-inline--fa fa-shipping-fast fa-w-20"
                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                    <path fill="currentColor"
                          d="M280 192c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h240zm352 192h-24V275.9c0-16.8-6.8-33.3-18.8-45.2l-83.9-83.9c-11.8-12-28.3-18.8-45.2-18.8H416V78.6c0-25.7-22.2-46.6-49.4-46.6H113.4C86.2 32 64 52.9 64 78.6V96H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h240c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H96V78.6c0-8.1 7.8-14.6 17.4-14.6h253.2c9.6 0 17.4 6.5 17.4 14.6V384H207.6C193 364.7 170 352 144 352c-18.1 0-34.6 6.2-48 16.4V288H64v144c0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16h195.2c-1.1 5.2-1.6 10.5-1.6 16 0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16H632c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zm-488 96c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm272-320h44.1c8.4 0 16.7 3.4 22.6 9.4l83.9 83.9c.8.8 1.1 1.9 1.8 2.8H416V160zm80 320c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-96h-16.4C545 364.7 522 352 496 352s-49 12.7-63.6 32H416v-96h160v96zM256 248v-16c0-4.4-3.6-8-8-8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h240c4.4 0 8-3.6 8-8z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_SHIPPING'); ?>
            </a>
            <ul class="uk-nav-sub" hidden="">

                <li><a
                            href="index.php?option=com_protostore&view=shippingratescountry"><?= Text::_('COM_PROTOSTORE_SIDEMENU_COUNTRY_SHIPPING_RATES'); ?></a>
                </li>
                <li><a href="index.php?option=com_protostore&view=shippingrateszone">&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?= Text::_('COM_PROTOSTORE_SIDEMENU_ZONE_SHIPPING_RATES'); ?></a>
                </li>
            </ul>
        </li>
        <li class="uk-parent">
            <a href="#">
                <svg width="1.125em" aria-hidden="true" focusable="false" data-prefix="fal"
                     data-icon="envelope-open-text" class="svg-inline--fa fa-envelope-open-text fa-w-16"
                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor"
                          d="M352 248v-16c0-4.42-3.58-8-8-8H168c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8h176c4.42 0 8-3.58 8-8zm-184-56h176c4.42 0 8-3.58 8-8v-16c0-4.42-3.58-8-8-8H168c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8zm326.59-27.48c-1.98-1.63-22.19-17.91-46.59-37.53V96c0-17.67-14.33-32-32-32h-46.47c-4.13-3.31-7.71-6.16-10.2-8.14C337.23 38.19 299.44 0 256 0c-43.21 0-80.64 37.72-103.34 55.86-2.53 2.01-6.1 4.87-10.2 8.14H96c-17.67 0-32 14.33-32 32v30.98c-24.52 19.71-44.75 36.01-46.48 37.43A48.002 48.002 0 0 0 0 201.48V464c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V201.51c0-14.31-6.38-27.88-17.41-36.99zM256 32c21.77 0 44.64 16.72 63.14 32H192.9c18.53-15.27 41.42-32 63.1-32zM96 96h320v173.35c-32.33 26-65.3 52.44-86.59 69.34-16.85 13.43-50.19 45.68-73.41 45.31-23.21.38-56.56-31.88-73.41-45.32-21.29-16.9-54.24-43.33-86.59-69.34V96zM32 201.48c0-4.8 2.13-9.31 5.84-12.36 1.24-1.02 11.62-9.38 26.16-21.08v75.55c-11.53-9.28-22.51-18.13-32-25.78v-16.33zM480 464c0 8.82-7.18 16-16 16H48c-8.82 0-16-7.18-16-16V258.91c42.75 34.44 99.31 79.92 130.68 104.82 20.49 16.36 56.74 52.53 93.32 52.26 36.45.26 72.27-35.46 93.31-52.26C380.72 338.8 437.24 293.34 480 258.9V464zm0-246.19c-9.62 7.75-20.27 16.34-32 25.79v-75.54c14.44 11.62 24.8 19.97 26.2 21.12 3.69 3.05 5.8 7.54 5.8 12.33v16.3z"></path>
                </svg> <?= Text::_('COM_PROTOSTORE_SIDEMENU_EMAILS'); ?>
            </a>
            <ul class="uk-nav-sub" hidden="">
                <li>
                    <a href="index.php?option=com_protostore&view=email"><?= Text::_('COM_PROTOSTORE_SIDEMENU_ADD_EMAIL'); ?></a>
                </li>
                <li>
                    <a href="index.php?option=com_protostore&view=emailmanager"><?= Text::_('COM_PROTOSTORE_SIDEMENU_EMAIL_MANAGER'); ?></a>
                </li>
                <li>
                    <a href="index.php?option=com_protostore&view=emaillogs"><?= Text::_('COM_PROTOSTORE_SIDEMENU_EMAIL_LOGS'); ?></a>
                </li>
            </ul>
        </li>

		<?php foreach ($extensions as $extension) : ?>

            <li>
                <a href="<?= $extension->view; ?>">
                    <?= $extension->icon; ?>
                     <?= Text::_($extension->linkText); ?>
                </a>
            </li>

		<?php endforeach; ?>
    </ul>
</div>
