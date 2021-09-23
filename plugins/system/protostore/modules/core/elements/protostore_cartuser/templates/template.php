<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Protostore\Config\ConfigFactory;

$el = $this->el('div', [

	'class' => [
		'uk-panel {@!style}',
		'uk-card uk-card-body uk-{style}',
		'tm-child-list [tm-child-list-{list_style}] [uk-link-{link_style}] {@is_list}',
	],

]);


?>

<script id="yps-cartuser-baseUrl" type="application/json"><?= $props['baseUrl']; ?></script>


<?= $el($props, $attrs) ?>

<?php if (Factory::getUser()->guest) : ?>

	<?php if (!$props['guestaddressset']) : ?>
        <!--    NOT LOGGED IN AND NO GUEST ADDRESS SET-->
		<?= $this->render("{$__dir}/login_reg", compact('props')) ?>
	<?php else : ?>
        <!--    GUEST ADDRESS IS SET-->
		<?= $this->render("{$__dir}/guest_set", compact('props')) ?>
	<?php endif; ?>


<?php else : ?>
    <!--ALREADY LOGGED IN-->

	<?php

	$params = ConfigFactory::get();

	if ($params->get('address_show', '1') == '1') :
		?>



		<?= $this->render("{$__dir}/logged_in", compact('props')) ?>

	<?php endif; ?>

<?php endif; ?>
</div>
