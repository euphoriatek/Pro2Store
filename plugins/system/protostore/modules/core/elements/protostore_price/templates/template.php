<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */



// Create div tag
$el = $this->el($props['title_element'], [

    'class' => [
        'uk-{title_style}',
        'uk-heading-{title_decoration}',
        'uk-font-{title_font_family}',
        'uk-text-{title_color} {@!title_color: background}',
        'uk-margin-remove {position: absolute}',
    ],
    'style' => [
        'text-decoration: line-through {@strikethru}'
    ],


]);

?>
<?= $el($props, $attrs) ?>
<div id="yps_price">
    <span><?= $props['content_before']; ?></span><span><?= $props['price_type_data']; ?></span>
    <span><?= $props['content_after']; ?></span>
</div>
<?= $el->end(); ?>
