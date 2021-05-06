<?php

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
