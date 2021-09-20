<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace YpsApp;

use YOOtheme\Builder;
use YOOtheme\Path;

return [

    'events' => [
        'source.init' => [
            Source\SourceListener::class => 'initSource',

        ],
    ],

    'extend' => [

        Builder::class => function (Builder $builder) {

            $builder->addTypePath(Path::get('./elements/*/element.json'));

        },


    ]

];
