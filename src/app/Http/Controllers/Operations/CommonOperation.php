<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait CommonOperation
{
    public function saveFields() {
        $fields = collect(request()->all());
        $fields = $fields->filter(function($value, $key) {
            if (strpos($key, "Bedoz_VisualEditorForBackpack_Blocks_") !== false) {
                return true;
            }
            return false;
        })->toArray();
        $field = collect($this->crud->settings()['update.fields'])->filter(function($value, $key) {
            if ($value['type'] == 'visual-editor') {
                return true;
            }
            return false;
        })->keys()->first();
        $this->crud->entry->{$field} = $fields;
        $this->crud->entry->save();
    }
}