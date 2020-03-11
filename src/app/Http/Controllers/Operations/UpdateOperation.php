<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait UpdateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as originalUpdate; }

    public function update() {
        // do something before validation, before save, before everything
        $response = $this->originalUpdate();
        dd(request()->all());
        // do something after save
        return $response;
    }
}