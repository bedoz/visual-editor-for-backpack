<?php

namespace Bedoz\VisualEditorForBackpack\app\Blocks;

class Text extends Block {
    public static $name = 'text';
    public static $label = 'Text';

    static public function pushStyle() {
        ?>

        <?php
    }

    static public function pushScripts() {
        $fieldName = self::fieldName();
        ?>
        <script src="<?php echo asset('packages/ckeditor/ckeditor.js'); ?>"></script>
        <script src="<?php echo asset('packages/ckeditor/adapters/jquery.js'); ?>"></script>
        <script>
            this['<?php echo self::classSlug(); ?>'] = {};

            this['<?php echo self::classSlug(); ?>'].startCKEditor = function (element) {
                element.find('textarea').ckeditor({
                    "filebrowserBrowseUrl": "<?php echo url(config('backpack.base.route_prefix').'/elfinder/ckeditor'); ?>",
                    "extraPlugins" : 'embed,widget',
                    "embed_provider": '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}'
                });
            }

            this['<?php echo self::classSlug(); ?>'].beforeSort = function (element) {
                var instanceName = element.find('textarea').siblings("[id^='cke_']").attr("id");
                instanceName = instanceName.substr(4);
                if(CKEDITOR.instances[instanceName]) {
                    CKEDITOR.instances[instanceName].destroy();
                }
            }

            this['<?php echo self::classSlug(); ?>'].afterSort = function (element) {
                window['<?php echo self::classSlug(); ?>'].startCKEditor(element);
            }

            this['<?php echo self::classSlug(); ?>'].init = function (element) {
                if (element.data("id") === "VEBlockName") {
                    element.find('textarea[name=VEBlockName]').attr("name", "<?php echo $fieldName; ?>");
                    element.data("id", "<?php echo $fieldName; ?>").attr("data-id", "<?php echo $fieldName; ?>");
                }

                window['<?php echo self::classSlug(); ?>'].startCKEditor(element);
            }

            this['<?php echo self::classSlug(); ?>'].destroy = function (element) {
                var instanceName = element.find('textarea').siblings("[id^='cke_']").attr("id");
                instanceName = instanceName.substr(4);
                if(CKEDITOR.instances[instanceName]) {
                    CKEDITOR.instances[instanceName].destroy();
                }
            }
        </script>
        <?php
    }
}