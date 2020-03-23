<?php

namespace Bedoz\VisualEditorForBackpack\app\Blocks;

abstract class Block {
    static public $name = '';
    static public $description = '';
    public static $label = '';
    public static $hint = '';

    static public function renderBackend($crud, $entry, $id = 'VEBlockName', $value = '') {
        $class = get_called_class();
        return view('visual-editor-for-backpack::blocks.'.$class::$name.'.backend', compact('class', 'crud', 'entry', 'id', 'value'));
    }

    static public function renderFrontend() {
        $class = get_called_class();
        return view('visual-editor-for-backpack::blocks.'.$class::$name.'.frontend', compact('class'));
    }

    static public function pushStyle() {
        return false;
    }

    static public function pushScripts() {
        ?>
        <script>
            this['<?php echo self::classSlug(); ?>'] = function (element) {
                element.find('input[name=VEBlockName]').attr("name", "<?php echo self::fieldName(); ?>");
            }
        </script>
        <?php
    }

    static public function classSlug() {
        return str_replace("\\", "_", static::class);
    }

    static public function fieldName() {
        return self::classSlug() . "_" . str_random(10);
    }
}