<?php

namespace Admin\Gutenberg\Contracts\Blocks;

use Embed\Embed;
use Cache;

class EmbedHelper
{
    /**
     * Transforms the Embed/Embed object to a format that Gutenberg can handle
     * @param Embed $embed
     * @return array
     */
    public static function serialize($embed) {
        return [
            // Uri objects are cast to strings, cached objects would not unserialize
            'url' => self::string($embed->url),
            'author_name' => $embed->authorName,
            'author_url' => self::string($embed->authorUrl),
            'html' => $embed->code->html ?? null,
            'width' => $embed->code->width ?? null,
            'height' => $embed->code->height ?? null,
            'type' => 'video',
            'provider_name' => $embed->providerName,
            'provider_url' => self::string($embed->providerUrl)
        ];
    }

    /**
     * @param  mixed  $value
     * @return string|null
     */
    private static function string($value)
    {
        return is_null($value) ? null : (string) $value;
    }

    /**
     * Creates an embed from a URL
     * @param String $url
     */
    public static function create($url)
    {
        $urlHash = md5($url);

        $data = Cache::rememberForever('gutenberg.embed.'.$urlHash, function() use ($url) {
            $embed = (new Embed)->get($url);

            return self::serialize($embed);
        });

        return $data;
    }
}