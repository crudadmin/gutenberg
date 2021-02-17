<?php

namespace Admin\Gutenberg\Test;

use Admin\Gutenberg\GunebergFacade;
use Admin\Gutenberg\GutenbergServiceProvider;
use Orchestra\Testbench\Testcase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return Admin\Gutenberg\GutenbergServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [GutenbergServiceProvider::class];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Guneberg' => GunebergFacade::class,
        ];
    }
}

