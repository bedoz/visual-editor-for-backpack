<?php

namespace Bedoz\VisualEditorForBackpack\Blocks;

abstract class Block {
    static public $name = '';
    static public $description = '';

    static public function renderBackend() {
        $class = get_called_class();
        return view('visual-editor-for-backpack::blocks.'.$class::$name.'.backend');
    }

    static public function renderFrontend() {
        $class = get_called_class();
        return view('visual-editor-for-backpack::blocks.'.$class::$name.'.frontend');
    }
}