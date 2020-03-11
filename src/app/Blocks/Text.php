<?php

namespace Bedoz\VisualEditorForBackpack\Blocks;

class Text extends Block {
    public static $name = 'text';
    public static $label = 'Text';

    static public function pushStyle() {
        ?>

        <?php
    }

    static public function pushScripts() {
        ?>
        <script src="<?php echo asset('packages/ckeditor/ckeditor.js'); ?>"></script>
        <script src="<?php echo asset('packages/ckeditor/adapters/jquery.js'); ?>"></script>
        <script>
            this['<?php echo self::classSlug(); ?>'] = function (element) {
                element.find('textarea[name=VEBlockName]').attr("name", "<?php echo self::fieldName(); ?>");

                element.find('textarea').ckeditor({
                    "filebrowserBrowseUrl": "<?php echo url(config('backpack.base.route_prefix').'/elfinder/ckeditor'); ?>",
                    "extraPlugins" : 'embed,widget',
                    "embed_provider": '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}'
                });
            }
        </script>
        <?php
    }
}