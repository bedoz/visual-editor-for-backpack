<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait CreateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { create as originalCreate; }
    use CommonOperation;

    public function create() {
        // do something before validation, before save, before everything
        $response = $this->originalCreate();

        $this->saveFields();

        // do something after save
        return $response;
    }
}