<?php

namespace Bedoz\VisualEditorForBackpack\app\Blocks;

class Slideshow extends Block {
    public static $name = 'slideshow';
    public static $label = 'Slideshow';
    public static $hint = '';
    public static $sizes = [
        'quadrata' => [
            'ratio' => 1,
        ],
        'facebook' => [
            'ratio' => 2,
        ],
    ];

    static public function pushStyle() {
        ?>
        <link href="<?php echo asset('packages/cropperjs/dist/cropper.min.css'); ?>" rel="stylesheet" type="text/css" />
        <style>
            .hide {
                display: none;
            }
            .image .btn-group {
                margin-top: 10px;
            }
            img {
                max-width: 100%; /* This rule is very important, please do not ignore this! */
            }
            .img-container, .img-preview {
                width: 100%;
                text-align: center;
            }
            .img-preview {
                float: left;
                margin-right: 10px;
                margin-bottom: 10px;
                overflow: hidden;
            }
            .preview-lg {
                width: 263px;
                height: 148px;
            }

            .btn-file {
                position: relative;
                overflow: hidden;
            }
            .btn-file input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                min-width: 100%;
                min-height: 100%;
                font-size: 100px;
                text-align: right;
                filter: alpha(opacity=0);
                opacity: 0;
                outline: none;
                background: white;
                cursor: inherit;
                display: block;
            }

            .sortable-placeholder {
                background: #F29B1A;
                height: 160px;
            }

            div[draggable=true] {
                cursor: move;
            }
        </style>
        <?php
    }

    static public function pushScripts() {
        $fieldName = self::fieldName();
        ?>
        <div id="SlideshowAddScripts"></div>
        <script>
            if (typeof $.fn.Slick === 'undefined') {
                var s = document.createElement( 'script' );
                s.setAttribute( 'src', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' );
                document.body.appendChild( s );
                s = document.createElement( 'link' );
                s.setAttribute( 'rel', 'stylesheet' );
                s.setAttribute( 'href', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
                document.body.appendChild( s );
                s = document.createElement( 'link' );
                s.setAttribute( 'rel', 'stylesheet' );
                s.setAttribute( 'href', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css' );
                document.body.appendChild( s );
            }
            if (typeof sortable === "undefined") {
                document.write('<scr' + 'ipt src="https://cdnjs.cloudflare.com/ajax/libs/html5sortable/0.9.17/html5sortable.min.js"></scr' + 'ipt>');
            }
            if (typeof $.fn.cropper === 'undefined') {
                var s = document.createElement( 'script' );
                s.setAttribute( 'src', "<?php echo asset('packages/cropperjs/dist/cropper.min.js'); ?>" );
                document.body.appendChild( s );
            }
        </script>
        <script>
            this['<?php echo self::classSlug(); ?>'] = {};

            this['<?php echo self::classSlug(); ?>'].beforeSort = function (element) {}

            this['<?php echo self::classSlug(); ?>'].afterSort = function (element) {}

            this['<?php echo self::classSlug(); ?>'].init = function (element) {
                var updateData = function(){
                    var gallery = [];
                    element.find('div[data-preview] .file-preview').each(function(){
                        var imageData = $(this).data("gallery-data");
                        if (typeof imageData == "string") {
                            imageData = $.parseJSON(imageData);
                        }
                        gallery.push(imageData);
                    });
                    gallery = JSON.stringify(gallery);
                    element.find('input[name='+element.data("id")+']').val(gallery);
                }

                if (element.data("id") === "VEBlockName") {
                    element.find('input[name=VEBlockName]').attr("name", "<?php echo $fieldName; ?>");
                    element.data("id", "<?php echo $fieldName; ?>").attr("data-id", "<?php echo $fieldName; ?>");
                }

                element.find("#slideshow_file_input").change(function(){
                    var $container = element.find(".row.sortable");
                    let files = $(this)[0].files;
                    let error = false;

                    for (var i = 0; i < files.length; i++) {
                        if (/^image\/\w+$/.test(files[i].type)) {
                            //upload files e aggiunta al box
                            var formData = new FormData();
                            formData.append("file", files[i]);
                            formData.append("_token", "<?php echo csrf_token(); ?>");

                            var xhttp = new XMLHttpRequest();
                            xhttp.open('POST', "<?php echo route('fields.slideshow.saveImage'); ?>", true);
                            xhttp.addEventListener('progress', function(e){});
                            xhttp.addEventListener('load', function(e) {
                                if (e.target.status === 200 && e.target.readyState === e.target.DONE) {
                                    newel = element.find(".new-elements > div").clone();
                                    newel.appendTo($container);
                                    newel.data("gallery-data", e.target.response).attr("data-gallery-data", e.target.response);
                                    var response = JSON.parse(e.target.response);
                                    newel.children("img").attr("src", "<?php echo asset(\Storage::disk('public')->url("")); ?>" + response.image);

                                    sortable('.sortable');

                                    if ($container.children("div.text-danger").length > 0) {
                                        $container.children("div.text-danger").remove();
                                    }

                                    updateData();
                                } else {
                                    error = true;
                                }
                            });
                            xhttp.send(formData);
                        }
                    }
                    if (error) {
                        new Noty({
                            type: "error",
                            text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.error_upload'); ?>",
                        }).show();
                    }
                    element.find("#slideshow_file_input").val("");
                });

                element.on("click", ".file-clear-button", function(e) {
                    var $container = $(this).closest(".sortable");
                    var $currentImage = $(this).closest(".file-preview");
                    var imageData = $currentImage.data("gallery-data");

                    if (typeof imageData == "string") {
                        imageData = $.parseJSON(imageData);
                    }
                    if (typeof imageData.sizes == 'undefined' || imageData.sizes.length === 0) {
                        imageData.sizes = {};
                    }

                    element.find("#remove").click();

                    $.ajax({
                        url: "<?php echo route('fields.slideshow.deleteImage'); ?>",
                        method: 'POST',
                        data: {
                            '_token': '<?php echo csrf_token(); ?>',
                            'image': imageData.image,
                            'delete': Object.keys(imageData.sizes)
                        },
                        success: function (data) {
                            $currentImage.remove();
                            if ($.trim($container.html()) === '') {
                                $('<div class="col-sm-12 text-danger"><?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.no_images'); ?></div>').appendTo($container);
                            }
                            updateData();
                            new Noty({
                                type: "success",
                                text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.deleted_image'); ?>"
                            }).show();
                        }
                    });
                });

                element.find("div[data-preview]").each(function () {
                    // Options either global for all image type fields, or use 'data-*' elements for options passed in via the CRUD controller
                    var options = {
                        viewMode: 2,
                        checkOrientation: false,
                        autoCropArea: 1,
                        responsive: true,
                        preview : $(this).attr('data-preview'),
                        aspectRatio : $(this).attr('data-aspectRatio')
                    };

                    sortable('.sortable', {
                        placeholderClass: 'col-sm-3',
                        forcePlaceholderSize: true
                    })[0].addEventListener('sortupdate', function(e) {
                        updateData();
                        //console.log(e.detail);
                        /*
                        This event is triggered when the user stopped sorting and the DOM position has changed.

                        e.detail.item - {HTMLElement} dragged element

                        Origin Container Data
                        e.detail.origin.index - {Integer} Index of the element within Sortable Items Only
                        e.detail.origin.elementIndex - {Integer} Index of the element in all elements in the Sortable Container
                        e.detail.origin.container - {HTMLElement} Sortable Container that element was moved out of (or copied from)
                        e.detail.origin.itemsBeforeUpdate - {Array} Sortable Items before the move
                        e.detail.origin.items - {Array} Sortable Items after the move

                        Destination Container Data
                        e.detail.destination.index - {Integer} Index of the element within Sortable Items Only
                        e.detail.destination.elementIndex - {Integer} Index of the element in all elements in the Sortable Container
                        e.detail.destination.container - {HTMLElement} Sortable Container that element was moved out of (or copied from)
                        e.detail.destination.itemsBeforeUpdate - {Array} Sortable Items before the move
                        e.detail.destination.items - {Array} Sortable Items after the move
                        */
                    });

                    // Only initialize cropper plugin if crop is set to true
                    $(this).on("click", "#remove", function() {
                        $(this).closest("#bottoni_crop").find('#mainImage').attr('src','').cropper("destroy");
                        element.find("#bottoni_crop").hide();
                    });

                    var $parent = $(this);

                    $(this).on("click" , ".file-preview .file-edit-button", function() {
                        var $cropArea = $parent.find("#bottoni_crop");
                        var $currentImage = $(this).closest(".file-preview");
                        var $rotateLeft = $cropArea.find("#rotateLeft");
                        var $rotateRight = $cropArea.find("#rotateRight");
                        var $zoomIn = $cropArea.find("#zoomIn");
                        var $zoomOut = $cropArea.find("#zoomOut");
                        var $reset = $cropArea.find("#reset");
                        var $save = $cropArea.find("#save");
                        var $ratioButtons = $cropArea.find("#aspectRatio select");
                        var $tagliDisponibili = $cropArea.find("#tagliDisponibili");
                        var $mainImage = $cropArea.find('#mainImage');
                        var $image = $currentImage.children("img");
                        var imageData = $currentImage.data("gallery-data");
                        var url = $image.attr("src");
                        var xhttp = new XMLHttpRequest();
                        $save.unbind("click");
                        $rotateLeft.unbind("click");
                        $rotateRight.unbind("click");
                        $zoomIn.unbind("click");
                        $zoomOut.unbind("click");
                        $reset.unbind("click");
                        $ratioButtons.unbind("change");
                        $tagliDisponibili.unbind("click");

                        xhttp.open('HEAD', url);
                        xhttp.onreadystatechange = function () {
                            if (this.readyState == this.DONE && this.status == 200) {
                                var type = this.getResponseHeader("Content-Type");
                                if (/^image\/\w+$/.test(type)) {
                                    $mainImage.cropper(options).cropper("reset", true).cropper("replace", url);
                                    $save.click(function() {
                                        new Noty({
                                            type: "warning",
                                            text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.saving'); ?>",
                                        }).show();
                                        imageData = $currentImage.data("gallery-data");
                                        if (typeof imageData == "string") {
                                            imageData = $.parseJSON(imageData);
                                        }
                                        if (typeof imageData.sizes == 'undefined' || imageData.sizes.length === 0) {
                                            imageData.sizes = {};
                                        }
                                        if (!$ratioButtons.val()) {
                                            new Noty({
                                                type: "error",
                                                text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.select_size_before'); ?>",
                                            }).show();
                                            return false;
                                        }
                                        imageData.sizes[$ratioButtons.val()] = $mainImage.cropper('getData');
                                        var folder = imageData.image.match( /.*\// );
                                        var filename = imageData.image.replace( /.*\//, "" );
                                        imageData.sizes[$ratioButtons.val()].image = folder + $ratioButtons.val() + "_" + filename;
                                        $currentImage.data("gallery-data",imageData).attr("data-gallery-data", JSON.stringify(imageData));
                                        updateData();
                                        $mainImage.cropper('getCroppedCanvas').toBlob(function (blob) {
                                            var formData = new FormData();
                                            formData.append('croppedImage', blob);
                                            formData.append('_token', '<?php echo csrf_token(); ?>');
                                            formData.append('filename', imageData.image);
                                            formData.append('size', $ratioButtons.val());
                                            $.ajax({
                                                url: '<?php echo route('fields.slideshow.saveCrop'); ?>',
                                                method: "POST",
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                success: function(data){
                                                    if ($tagliDisponibili.find("[data-taglio="+$ratioButtons.val()+"]").length == 0) {
                                                        $tagliDisponibili
                                                        .find("#sample")
                                                        .clone()
                                                        .removeAttr("id")
                                                        .show()
                                                        .appendTo($tagliDisponibili.children(".row"));
                                                        $tagliDisponibili.find(".taglio:last").find(".titolo").text($ratioButtons.val());
                                                        $tagliDisponibili.find(".taglio:last").attr("data-taglio", $ratioButtons.val()).data("taglio", $ratioButtons.val());
                                                    }
                                                    d = new Date();
                                                    $tagliDisponibili.find("[data-taglio="+$ratioButtons.val()+"]").find("img").attr("src", "<?php echo \Storage::disk("public")->url(""); ?>"+data+"?"+d.getTime());
                                                    new Noty({
                                                        type: "success",
                                                        text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.saved_crop'); ?>",
                                                    }).show();
                                                    $ratioButtons.val("");
                                                }
                                            });
                                        });
                                    });
                                    $rotateLeft.click(function() {
                                        $mainImage.cropper("rotate", 90);
                                    });
                                    $rotateRight.click(function() {
                                        $mainImage.cropper("rotate", -90);
                                    });
                                    $zoomIn.click(function() {
                                        $mainImage.cropper("zoom", 0.1);
                                    });
                                    $zoomOut.click(function() {
                                        $mainImage.cropper("zoom", -0.1);
                                    });
                                    $reset.click(function() {
                                        $mainImage.cropper("reset");
                                    });
                                    $ratioButtons.change(function () {
                                        $mainImage.cropper("setAspectRatio", $(this).find("option:selected").data("ratio"));
                                    });
                                    $tagliDisponibili.find(".taglio:not(#sample)").remove();
                                    if (typeof imageData.sizes != 'undefined') {
                                        d = new Date();
                                        var basename = imageData.image.replace(/\\/g,'/').replace(/.*\//, '');
                                        var dirname = imageData.image.match(/(.*)\//)[1];
                                        $.each(imageData.sizes, function(key, value){
                                            var filename = dirname+"/"+key+"_"+basename;
                                            $tagliDisponibili
                                            .find("#sample")
                                            .clone()
                                            .removeAttr("id")
                                            .show()
                                            .appendTo($tagliDisponibili.children(".row"));
                                            $tagliDisponibili.find(".taglio:last").find(".titolo").text(key);
                                            $tagliDisponibili.find(".taglio:last").find("img").attr("src","<?php echo \Storage::disk("public")->url(""); ?>"+filename+"?"+d.getTime());
                                            $tagliDisponibili.find(".taglio:last").attr("data-taglio", key).data("taglio", key);
                                        });
                                    }
                                    $tagliDisponibili.on("click", ".cancella_miniatura", function(){
                                        var $this = $(this);
                                        var taglio = $(this).closest("[data-taglio]").data("taglio");
                                        $.ajax({
                                            url: "<?php echo route('fields.slideshow.deleteCrop'); ?>",
                                            method: 'POST',
                                            data: {
                                                '_token': '<?php echo csrf_token(); ?>',
                                                'image': imageData.image,
                                                'delete': taglio
                                            },
                                            success: function (data) {
                                                if (data != 1) {
                                                    new Noty({
                                                        type: "error",
                                                        text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.error_delete_crop'); ?>"
                                                    }).show();
                                                    return false;
                                                }
                                                delete imageData.sizes[taglio];
                                                $currentImage.data("gallery-data", imageData).attr("data-gallery-data", JSON.stringify(imageData));
                                                updateData();
                                                $this.closest("[data-taglio]").remove();
                                                new Noty({
                                                    type: "success",
                                                    text: "<?php echo trans('visual-editor-for-backpack::blocks/' . self::$name . '.deleted_crop'); ?>"
                                                }).show();
                                            }
                                        });
                                    });
                                    element.find("#bottoni_crop").show();
                                } else {
                                    alert("Please choose an image file.");
                                }
                            }
                        };
                        xhttp.send();
                    });
                });
            }

            this['<?php echo self::classSlug(); ?>'].destroy = function (element) {}
        </script>
        <?php
    }
}