<?php

namespace Admin\Gutenberg\Fields\Mutations;

use Admin\Core\Fields\Mutations\MutationRule;

class AddGutenbergRawColumn extends MutationRule
{
    public function create(array $field, string $key)
    {
        $add = [
            $key => $field,
        ];

        if (($field['type'] ?? null) == 'gutenberg') {
            $add[$key.'_rendered'] = $field + [
                'inaccessible' => true
            ];
        }

        return $add;
    }
}