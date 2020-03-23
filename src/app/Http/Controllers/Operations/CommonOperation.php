<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait CommonOperation
{
    public function saveFields() {
        $values = collect(request()->all());
        $values = $values->filter(function($value, $key) {
            if (strpos($key, "Bedoz_VisualEditorForBackpack_app_Blocks_") !== false) {
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
        if ($this->crud->entry->isTranslatableAttribute($field)) { // the attribute is translatable
            $this->crud->entry->setTranslation($field, $this->crud->entry->locale, $values);
        } else { // the attribute is NOT translatable
            $this->crud->entry->{$field} = $values;
        }
        $this->crud->entry->save();
    }
}