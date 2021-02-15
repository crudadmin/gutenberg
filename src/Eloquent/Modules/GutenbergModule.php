<?php

namespace VanOns\Laraberg\Eloquent\Modules;

use Admin;
use Admin\Core\Eloquent\Concerns\AdminModelFieldValue;
use Admin\Core\Eloquent\Concerns\AdminModelModule;
use Admin\Core\Eloquent\Concerns\AdminModelModuleSupport;
use VanOns\Laraberg\Helpers\EmbedHelper;
use VanOns\Laraberg\Helpers\SocialHelper;

class GutenbergModule extends AdminModelModule implements AdminModelModuleSupport
{
    static $blockMutators = [
        EmbedHelper::class,
        SocialHelper::class,
    ];

    public static function addBlockMutator($class)
    {
        self::$blockMutators = $class;
    }

    public function isActive($model)
    {
        return true;
    }

    public function fieldValue($model, $key, $field, $value)
    {
        if ( in_array($field['type'], ['gutenberg']) && substr($key, -9) !== '_rendered' ) {
            $renderedKey = $key.'_rendered';

            $value = $model->__get($renderedKey);

            if ($model->hasFieldParam($key, ['locale'], true)) {
                $value = $model->returnLocaleValue($value);
            }

            return new AdminModelFieldValue($value);
        }
    }

    public function requestMutator($request, $model, $fields, $rules)
    {
        //Allow this feature only in administration
        if ( Admin::isAdmin() === false ) {
            return;
        }

        foreach ($fields as $key => $field) {
            //Allow remove only "removed" fields from dom.
            if ( $model->isFieldType($key, 'gutenberg') ) {
                $renderedKey = $key.'_rendered';

                if ( array_key_exists($renderedKey, $fields) ) {
                    $request->replace($request->all() + [
                        $renderedKey => $this->renderValue(
                            $request->get($key)
                        )
                    ]);
                }
            }
        }
    }

    private function renderValue($value)
    {
        foreach (self::$blockMutators as $mutator) {
            $value = $mutator::render($value);
        }

        return $value;
    }
}
