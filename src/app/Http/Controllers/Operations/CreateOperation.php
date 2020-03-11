<?php

namespace Bedoz\VisualEditorForBackpack\app\Http\Controllers\Operations;

trait CreateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { create as originalCreate; }

    public function create() {
        // do something before validation, before save, before everything
        $response = $this->originalCreate();
        // do something after save
        return $response;
    }
}