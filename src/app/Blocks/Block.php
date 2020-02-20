<?php

namespace Bedoz\VisualEditorForBackpack\Blocks;

abstract class Block {
    static public $name = '';
    static public $description = '';

    static public function renderBackend($crud, $entry) {
        $class = get_called_class();
        return view('visual-editor-for-backpack::blocks.'.$class::$name.'.backend', compact('class', 'crud', 'entry'));
    }

    static public function renderFrontend() {
        $class = get_called_class();
        return view('visual-editor-for-backpack::blocks.'.$class::$name.'.frontend', compact('class'));
    }

    static public function pushStyle() {
        return false;
    }

    static public function pushScripts() {
        return false;
    }
}