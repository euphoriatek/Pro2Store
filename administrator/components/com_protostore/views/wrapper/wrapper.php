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


use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$input = Factory::getApplication()->input;
$view  = $input->get('view', 'dashboard');


?>
<div id="p2s_main">
    <div id="p2s_leftCol">
        <div style="position: absolute; top: 50px; bottom: 60px; left: 0; right: 0; overflow: hidden;">
            <div style="width: 300px;box-sizing: border-box; height: 100%; width: 100%; padding: 15px 35px; overflow-y: auto; overflow-x: hidden; position: absolute;">
                <div class="uk-flex-middle uk-text-center uk-padding-small">
                    <a href="index.php?option=com_protostore">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink" width="161.874" height="48.013"
                             viewBox="0 0 6689 1984">
                            <image _ngcontent-akt-c98="" x="271" y="85" width="6003" height="1773"
                                   xlink:href="data:img/png;base64,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"></image>
                        </svg>
                    </a>

                    <a href="https://headwayapp.co/pro2store-changelog"
                       rel="noopener" target="_blank"
                       data-uk-tooltip="title:View Changelog; pos: bottom;"
                       class="uk-text-xsmall uk-text-muted uk-display-block"
                       title="" aria-expanded="false"> 2.0.0
                    </a>
                </div>
				<?= LayoutHelper::render('sidemenu'); ?>
            </div>
        </div>

    </div>
    <div id="p2s_content">
        <div class="uk-section-default uk-section uk-section-xsmall">
            <div class="uk-grid" uk-grid>
                <div class="uk-width-expand">
                    <ul class="uk-margin-remove-bottom uk-subnav" uk-margin="">
                        <div class="uk-margin-small">
                            <ul class="uk-breadcrumb uk-margin-remove-bottom">

                                <li class="">
                                    <a class="el-link" href="index.php?option=com_protostore">
                                        <svg width="10px" class="svg-inline--fa fa-house-user fa-w-18 fa-lg"
                                             uk-tooltip="Dashboard"
                                             title="" aria-expanded="false" tabindex="0" aria-hidden="true"
                                             focusable="false" data-prefix="fas" data-icon="house-user" role="img"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                            <path fill="currentColor"
                                                  d="M570.69,236.27,512,184.44V48a16,16,0,0,0-16-16H432a16,16,0,0,0-16,16V99.67L314.78,10.3C308.5,4.61,296.53,0,288,0s-20.46,4.61-26.74,10.3l-256,226A18.27,18.27,0,0,0,0,248.2a18.64,18.64,0,0,0,4.09,10.71L25.5,282.7a21.14,21.14,0,0,0,12,5.3,21.67,21.67,0,0,0,10.69-4.11l15.9-14V480a32,32,0,0,0,32,32H480a32,32,0,0,0,32-32V269.88l15.91,14A21.94,21.94,0,0,0,538.63,288a20.89,20.89,0,0,0,11.87-5.31l21.41-23.81A21.64,21.64,0,0,0,576,248.19,21,21,0,0,0,570.69,236.27ZM288,176a64,64,0,1,1-64,64A64,64,0,0,1,288,176ZM400,448H176a16,16,0,0,1-16-16,96,96,0,0,1,96-96h64a96,96,0,0,1,96,96A16,16,0,0,1,400,448Z"></path>
                                        </svg>
                                    </a>
                                </li>

                                <li><a href="index.php?option=com_protostore">Dashboard</a></li>

                            </ul>
                        </div>

                    </ul>


                </div>

                <div class="uk-width-auto">
                    <div class="uk-text-lowercase uk-visible@s">
                        <ul class="uk-margin-remove-bottom uk-subnav uk-margin-right" uk-margin>
                            <li class="el-item uk-first-column">
                                <a class="el-content uk-disabled">Ray Lawlor</a>
                            </li>
                            <li class="el-item">
                                <a class="el-link"
                                   href="index.php?option=com_config&view=component&component=com_protostore">
                                    <span uk-icon="icon: cog"></span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>


            </div>

            <hr class="uk-margin-remove-vertical">


        </div>
        <div class="uk-section-default uk-section uk-section-xsmall">

            <div class="uk-container uk-container-xlarge uk-margin-xlarge-bottom">

				<?php if ($view) : ?>
					<?php include(JPATH_ADMINISTRATOR . '/components/com_protostore/views/' . $view . '/bootstrap.php'); ?>
					<?php new bootstrap(); ?>
				<?php endif; ?>
            </div>
        </div>

    </div>
</div>






