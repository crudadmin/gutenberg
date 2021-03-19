<?php

return [
    'gutenberg_wrapper_class' => 'crudadmin-gutenberg-wrapper',

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
        'https://unpkg.com/react@16.8.6/umd/react.production.min.js',
        'https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js',
        'vendor/gutenberg/js/gutenberg.js',
    ],
];
