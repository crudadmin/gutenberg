<?php

return [
    'gutenberg_wrapper_class' => 'crudadmin-gutenberg-wrapper',
    'gutenberg_box_class' => 'g-block-wrapper',

    /*
     * Global rules on fields type
     */
    'global_rules' => [
        'gutenberg' => 'hidden',
    ],

    'styles' => [
        'vendor/gutenberg/css/gutenberg.css',
    ],

    'scripts' => [
        [ 'src' => 'https://unpkg.com/react@16.8.6/umd/react.production.min.js', 'module' => false ],
        [ 'src' => 'https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js', 'module' => false ],
        [ 'src' => 'vendor/gutenberg/js/gutenberg.js', 'module' => false ],
    ],
];
