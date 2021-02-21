<?php

namespace Admin\Gutenberg\Eloquent\Modules;

use Admin;
use Admin\Core\Eloquent\Concerns\AdminModelFieldValue;
use Admin\Core\Eloquent\Concerns\AdminModelModule;
use Admin\Core\Eloquent\Concerns\AdminModelModuleSupport;
use Admin\Gutenberg\Contracts\Blocks\BlocksBuilder;

class GutenbergModule extends AdminModelModule implements AdminModelModuleSupport
{
    public function isActive($model)
    {
        return true;
    }

    public function fieldValue($model, $key, $field, $value)
    {
        if ( in_array($field['type'], ['gutenberg']) ) {
            if ($model->hasFieldParam($key, ['locale'], true)) {
                $value = $model->returnLocaleValue($value);
            }

            $builder = new BlocksBuilder($value);

            return new AdminModelFieldValue(
                $builder->render()
            );
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
                if ( array_key_exists($key, $fields) ) {
                    $request->replace($request->all() + [
                        $key => $this->renderRawContentValue(
                            $request->get($key)
                        )
                    ]);
                }
            }
        }
    }

    /*
     * We can mutate send request, but now we are doing nothing
     */
    private function renderRawContentValue($value)
    {
        return $value;
    }
}