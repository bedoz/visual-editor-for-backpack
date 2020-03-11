<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait UpdateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as originalUpdate; }

    public function update() {
        // do something before validation, before save, before everything
        $response = $this->originalUpdate();
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
        // do something after save
        return $response;
    }
}