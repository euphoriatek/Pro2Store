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

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Version;


/** @var $vars array */

$input = Factory::getApplication()->input;
$view  = $input->get('view', 'dashboard');

$isSetup = \Protostore\Setup\SetupFactory::isSetup();
if (Version::MAJOR_VERSION === 4) {
	Factory::getApplication()->input->set('hidemainmenu', true);
}


?>
<div id="p2s_main">
	<?php if ( $isSetup) : ?>
        <div id="p2s_leftCol">
            <div style="position: absolute; top: 50px; bottom: 60px; left: 0; right: 0; overflow: hidden;">
                <div style="width: 300px;box-sizing: border-box; height: 100%; width: 100%; padding: 15px 35px; overflow-y: auto; overflow-x: hidden; position: absolute;">
                    <div class="uk-flex-middle uk-text-center uk-padding-small">
                        <a href="index.php?option=com_protostore">
							<?= LayoutHelper::render('svglogo'); ?>
                        </a>
						<?= LayoutHelper::render('version'); ?>
                    </div>
					<?= LayoutHelper::render('sidemenu'); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if ($isSetup) : ?>
        <div id="p2s_content">

            <div class="uk-section-default uk-section uk-section-xsmall">
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-expand">
                        <ul class="uk-margin-remove-bottom uk-subnav" uk-margin="">
                            <div class="uk-margin-small">
                                <ul class="uk-breadcrumb uk-margin-remove-bottom">

                                    <li class="">
                                        <a class="el-link" href="index.php?option=com_protostore">
                                            <svg width="18px" class="svg-inline--fa fa-house-user fa-w-18 fa-lg"
                                                 uk-tooltip="Dashboard"
                                                 title="" aria-expanded="false" tabindex="0" aria-hidden="true"
                                                 focusable="false" data-prefix="fas" data-icon="house-user" role="img"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                                                 data-fa-i2svg="">
                                                <path fill="currentColor"
                                                      d="M570.69,236.27,512,184.44V48a16,16,0,0,0-16-16H432a16,16,0,0,0-16,16V99.67L314.78,10.3C308.5,4.61,296.53,0,288,0s-20.46,4.61-26.74,10.3l-256,226A18.27,18.27,0,0,0,0,248.2a18.64,18.64,0,0,0,4.09,10.71L25.5,282.7a21.14,21.14,0,0,0,12,5.3,21.67,21.67,0,0,0,10.69-4.11l15.9-14V480a32,32,0,0,0,32,32H480a32,32,0,0,0,32-32V269.88l15.91,14A21.94,21.94,0,0,0,538.63,288a20.89,20.89,0,0,0,11.87-5.31l21.41-23.81A21.64,21.64,0,0,0,576,248.19,21,21,0,0,0,570.69,236.27ZM288,176a64,64,0,1,1-64,64A64,64,0,0,1,288,176ZM400,448H176a16,16,0,0,1-16-16,96,96,0,0,1,96-96h64a96,96,0,0,1,96,96A16,16,0,0,1,400,448Z"></path>
                                            </svg>
                                        </a>
                                    </li>
									<?php foreach ($vars['breadcrumbs'] as $breadcrumb) : ?>
                                        <li><a href="index.php?option=com_protostore"><?= $breadcrumb; ?></a></li>
									<?php endforeach; ?>
                                </ul>
                            </div>

                        </ul>


                    </div>

                    <div class="uk-width-auto">
                        <div class="uk-text-lowercase uk-visible@s">
                            <ul class="uk-margin-remove-bottom uk-subnav uk-margin-right" uk-margin>
                                <li class="el-item uk-first-column">
                                    <a class="el-content"
                                       href="index.php?option=com_users&task=user.edit&id=295"><?= Factory::getUser()->name; ?></a>
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
						<?php

						if (file_exists(JPATH_PLUGINS . '/protostore_extended/' . $view . '/views/' . $view . '/bootstrap.php') && PluginHelper::isEnabled('protostore_extended', $view))
						{
							include(JPATH_PLUGINS . '/protostore_extended/' . $view . '/views/' . $view . '/bootstrap.php');
						}
						else
						{
							include(JPATH_ADMINISTRATOR . '/components/com_protostore/views/' . $view . '/bootstrap.php');
						}

						?>
						<?php new bootstrap(); ?>
					<?php endif; ?>
                </div>
            </div>

        </div>
	<?php else: ?>
        <div style="width: 100%;">
            <div class="uk-section-default uk-section uk-section-xsmall">
                <div class="uk-container uk-container-xlarge uk-margin-xlarge-bottom">
					<?php include(JPATH_ADMINISTRATOR . '/components/com_protostore/views/setup/bootstrap.php'); ?>
					<?php new bootstrap(); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>


</div>






