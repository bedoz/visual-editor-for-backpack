<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait UpdateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as originalUpdate; }
    use CommonOperation;

    public function update() {
        // do something before validation, before save, before everything
        $response = $this->originalUpdate();

        $this->saveFields();

        // do something after save
        return $response;
    }
}