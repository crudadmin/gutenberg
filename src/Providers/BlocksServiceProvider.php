<?php

namespace Admin\Gutenberg\Providers;

use Admin\Core\State\RequestShared;
use Admin\Gutenberg\Contracts\Blocks\EmbedHelper;
use Admin\Providers\AdminPackageServiceProvider;

/**
 * Render filters of the WordPress blocks (embeds, social links), registered by GutenbergServiceProvider.
 */
class BlocksServiceProvider extends AdminPackageServiceProvider
{
    /**
     * Static state reset before every operation of long running processes.
     */
    protected $flush = [
        self::class,
    ];

    /**
     * The filters live in the global $wp_filter of the whole process, another application booted in
     * the same process (tests) must not add them again.
     *
     * @var bool
     */
    #[RequestShared]
    private static $filtersAdded = false;

    /**
     * Filters of $wp_filter after the boot of the process (packages, autoloaded files of the application).
     * Filters added during an operation, e.g. by a middleware of some routes, are removed before the next
     * one, otherwise every request of a long running worker would add them again and run them many times.
     *
     * @var array<string, \WP_Hook>|null
     */
    private static $bootFilters = null;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        if (self::$filtersAdded === false) {
            self::$filtersAdded = true;

            $this->addOembedBlock();
            $this->addSocialBlock();
        }
    }

    /**
     * Return the filters to the state after the boot and forget the filters which were being applied
     * when the previous request failed in a block. The first flush runs right after the boot, before
     * the first operation of the process.
     *
     * @return void
     */
    public static function flushState()
    {
        $GLOBALS['wp_current_filter'] = [];

        if (self::$bootFilters === null) {
            self::$bootFilters = self::cloneFilters($GLOBALS['wp_filter'] ?? []);

            return;
        }

        $GLOBALS['wp_filter'] = self::cloneFilters(self::$bootFilters);
    }

    /**
     * Copy of the filters, hooks are objects and add_filter() changes them in place.
     *
     * @param  array<string, \WP_Hook>  $filters
     * @return array<string, \WP_Hook>
     */
    private static function cloneFilters(array $filters)
    {
        return array_map(fn ($hook) => is_object($hook) ? clone $hook : $hook, $filters);
    }

    private function addOembedBlock()
    {
        add_filter('render_block', function ($block_content, $block) {
            // Filter only embed blocks
            if (
                strpos($block['blockName'], 'core-embed/') !== false
                || strpos($block['blockName'], 'core/embed') !== false
            ) {
                if (! ($url = $block['attrs']['url'] ?? null)) {
                    return $block_content;
                }

                try {
                    $embed = EmbedHelper::create($url);
                } catch (\Throwable $e) {
                    report($e);

                    return $block_content;
                }

                // Replace video
                $block_content = str_replace(
                    $url,
                    $embed['html'] ?? null,
                    $block_content
                );

                return $block_content;
            }

            return $block_content;
        }, -1, 2);
    }

    private function addSocialBlock()
    {
        add_filter('render_block_core/social-link', function ($block_content, $block) {
            return gutenberg_render_block_core_social_link($block['attrs']);
        }, -1, 2);
    }
}
