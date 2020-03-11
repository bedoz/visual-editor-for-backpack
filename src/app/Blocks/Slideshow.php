<?php

namespace Bedoz\VisualEditorForBackpack\Blocks;

class Slideshow extends Block {
    public static $name = 'slideshow';
    public static $label = 'Slideshow';
    public static $hint = 'Hint';
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
        ?>
        <script src="<?php echo asset('packages/cropperjs/dist/cropper.min.js'); ?>"></script>
        <script>
            jQuery(document).ready(function($) {
                $(".file-clear-button").click(function(e) {
                    e.preventDefault();
                    var container = $(this).closest(".sortable");
                    var parent = $(this).closest(".file-preview");
                    // remove the filename and button
                    parent.remove();
                    // if the file container is empty, remove it
                    if ($.trim(container.html())=='') {
                        container.remove();
                    }
                    $("<input type='hidden' name='clear_slideshow[]' value='"+$(this).data('filename')+"'>").insertAfter("#slideshow_file_input");
                });
                // Loop through all instances of the image field
                $('div[data-block="<?php echo addslashes(addslashes(self::class)); ?>"]').each(function(index){
                    // Options either global for all image type fields, or use 'data-*' elements for options passed in via the CRUD controller
                    var options = {
                        viewMode: 2,
                        checkOrientation: false,
                        autoCropArea: 1,
                        responsive: true,
                        preview : $(this).attr('data-preview'),
                        aspectRatio : $(this).attr('data-aspectRatio')
                    };

                    // Only initialize cropper plugin if crop is set to true
                    $(this).find("#remove").click(function() {
                        $(this).closest("#bottoni_crop").find('#mainImage').attr('src','').cropper("destroy");
                        $("#bottoni_crop").hide();
                    });

                    $(this).find(".file-preview").find(".file-edit-button").click(function() {
                        var $parent = $(this).closest(".form-group.gallery");
                        var $cropArea = $parent.find("#bottoni_crop");
                        var $currentImage = $(this).closest(".file-preview");
                        var $rotateLeft = $cropArea.find("#rotateLeft");
                        var $rotateRight = $cropArea.find("#rotateRight");
                        var $zoomIn = $cropArea.find("#zoomIn");
                        var $zoomOut = $cropArea.find("#zoomOut");
                        var $reset = $cropArea.find("#reset");
                        var $save = $cropArea.find("#save");
                        var $edit = $(this);
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
                                        imageData = $currentImage.data("gallery-data");
                                        if (typeof imageData == "string") {
                                            imageData = $.parseJSON(imageData);
                                        }
                                        if (typeof imageData.sizes == 'undefined') {
                                            imageData.sizes = {};
                                        }
                                        imageData['sizes'][$ratioButtons.val()] = $mainImage.cropper('getData');
                                        var folder = imageData.image.match( /.*\// );
                                        var filename = imageData.image.replace( /.*\//, "" );
                                        imageData['sizes'][$ratioButtons.val()]['image'] = folder + $ratioButtons.val() + "_" + filename;
                                        $currentImage.data("gallery-data",imageData).attr("data-gallery-data", JSON.stringify(imageData));
                                        $.ajax({
                                            url: "<?php echo route('fields.slideshow.crop'); ?>",
                                            method: 'POST',
                                            data: {
                                                '_token': '<?php echo csrf_token(); ?>',
                                                'model': 'Slideshow',
                                                'id': '', //id elemento
                                                'field': 'slideshow',
                                                'value': $edit.closest(".file-preview").parent().find("[data-gallery-data]").map(function(){return $(this).data('gallery-data');}).get()
                                            },
                                            success: function () {
                                                new PNotify({
                                                    title: "Immagine Salvata",
                                                    text: "Il nuovo taglio di immagine è stato salvato correttamente",
                                                    type: "success"
                                                });
                                            }
                                        });
                                        $mainImage.cropper('getCroppedCanvas').toBlob(function (blob) {
                                            var formData = new FormData();
                                            formData.append('croppedImage', blob);
                                            formData.append('_token', '<?php echo csrf_token(); ?>');
                                            formData.append('filename', imageData.image);
                                            formData.append('size', $ratioButtons.val());
                                            $.ajax({
                                                url: '<?php echo route('fields.slideshow.saveImage'); ?>',
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
                                        $.ajax({
                                            url: "<?php echo route('fields.slideshow.deleteImage'); ?>",
                                            method: 'POST',
                                            data: {
                                                '_token': '<?php echo csrf_token(); ?>',
                                                'model': 'Slideshow',
                                                'id': '', //id elemento
                                                'field': 'slideshow',
                                                'image': imageData.image,
                                                'delete': $(this).closest("[data-taglio]").data("taglio")
                                            },
                                            success: function (data) {
                                                if (typeof data == "string") {
                                                    data = $.parseJSON(data);
                                                }
                                                $edit.closest(".file-preview").data("gallery-data", data).attr("data-gallery-data", JSON.stringify(data));
                                                $this.closest("[data-taglio]").remove();
                                                new PNotify({
                                                    title: "Immagine Cancellata",
                                                    text: "Il taglio di immagine è stato cancellato",
                                                    type: "success"
                                                });
                                            }
                                        });
                                    });
                                    $("#bottoni_crop").show();
                                } else {
                                    alert("Please choose an image file.");
                                }
                            }
                        };
                        xhttp.send();
                    });
                });

                /*$('.sortable').sortable({
                    placeholderClass: 'col-sm-3'
                }).bind('sortupdate', function(e, ui) {
                    $.ajax({
                        url: "<?php echo route('fields.slideshow.order'); ?>",
                        method: 'POST',
                        data: {
                            '_token': '<?php echo csrf_token(); ?>',
                            'model': 'Slideshow',
                            'id': '', //id elemento
                            'field': 'slideshow',
                            'value': $(ui.item).parent().find("[data-gallery-data]").map(function(){return $(this).data('gallery-data');}).get()
                        },
                        success: function () {
                            new PNotify({
                                title: "Ordine Salvato",
                                text: "L'ordine corrente è stato salvato",
                                type: "success"
                            });
                        }
                    });
                });*/
            });
        </script>
        <?php
    }
}