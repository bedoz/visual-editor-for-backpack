<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ $field['label'] }}</label>
    @include('crud::inc.field_translatable_icon')
    <a href="javascript:;" class="btn btn-default visual-editor-preview">{{ trans('visual-editor-for-backpack::interface.preview') }}</a>
    <div class="clear"></div>

    <input type="hidden"
           name="{{ $field['name'] }}"
           value="{!! htmlspecialchars(old($field['name']) ?: $field['value'] ?? json_encode($field['default'] ?? []), ENT_QUOTES, 'UTF-8', true) !!}"
        @include('crud::inc.field_attributes')>

    @if (isset($field['hint']))
        <p class="help-block">
            {!! $field['hint'] !!}
        </p>
    @endif

    <div class="visual-editor-rows bg-light">
        @if($entry)
            @php
                $fields = json_decode($entry->{$field['name']});
            @endphp
            @foreach($fields as $name => $value)
                @php
                    $id = $name;
                    $name = explode("_", $name);
                    array_pop($name);
                    $name = implode("\\", $name);
                @endphp
                @include('visual-editor-for-backpack::interface.block', ['block' => $name, 'crud' => $crud, 'entry' => $entry, 'id' => $id, 'value' => $value])
            @endforeach
        @endif
    </div>

    <div class="visual-editor-templates" style="display: none;">
        {{-- Load available templates --}}
        @foreach(config('visual-editor.blocks') as $block)
            @include('visual-editor-for-backpack::interface.block', ['block' => $block, 'crud' => $crud, 'entry' => $entry ?? null, 'id' => null, 'value' => null])
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            <label>{{ trans('visual-editor-for-backpack::interface.choose_a_block') }}</label>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <select name="visual_editor_templates" class="form-control">
                <option value="" disabled selected>
                    {{ trans('visual-editor-for-backpack::interface.choose_a_block') }}
                </option>
                @foreach($field['templates'] ?? config('visual-editor.blocks') as $block)
                    <option value="{{ $block::classSlug() }}">
                        {{ trans("visual-editor-for-backpack::blocks/{$block::$name}.name") }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <a href="javascript:;" class="add btn btn-default" id="visual_editor_add_block_button">
                {{ trans('visual-editor-for-backpack::interface.add_block') }}
            </a>
        </div>
    </div>
</div>


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        @foreach(config('visual-editor.blocks') as $block)
            {!! $block::pushStyle() !!}
        @endforeach
        <style>
            .visual-editor-preview {float: right; margin-bottom: 10px;}
            .clear {clear: both;}
            .fancybox-slide--html .fancybox-content {
                width: 100vw;
                height: 100vh;
            }
            @media only screen and (min-width: 1024px) {
                .fancybox-slide--html .fancybox-content {
                    width: 80vw;
                    height: 90vh;
                }
            }
            .slick-prev:before, .slick-next:before {
                color: #000 !important;
            }
        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        @foreach(config('visual-editor.blocks') as $block)
            {!! $block::pushScripts() !!}
        @endforeach
        <script>
            if (typeof $.fancybox === 'undefined') {
                var s = document.createElement( 'script' );
                s.setAttribute( 'src', 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js' );
                document.body.appendChild( s );
                s = document.createElement( 'link' );
                s.setAttribute( 'rel', 'stylesheet' );
                s.setAttribute( 'href', 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css' );
                document.body.appendChild( s );
            }

            $(document).ready(function () {
                $("#visual_editor_add_block_button").click(function () {
                    let block = $("select[name=visual_editor_templates]").val()
                    if (block == null) {
                        new Noty({
                            type: "error",
                            text: '{{ trans('visual-editor-for-backpack::interface.select_al_least_one_block') }}',
                        }).show();
                        return false;
                    }
                    let element = $("div.visual-editor-templates").children("[data-block='" + block + "']").clone();
                    element.appendTo("div.visual-editor-rows");
                    window[block].init(element);
                });

                $("div.visual-editor-rows > [data-block]").each(function (index) {
                    window[$(this).data("block")].init($(this));
                });

                $("div.visual-editor-rows").on("click", "div.visual-editor-icons a.trash", function () {
                    const $me = $(this).closest("[data-block]");
                    window[$me.data("block")].destroy($me);
                    $me.remove();
                });

                $("div.visual-editor-rows").on("click", "div.visual-editor-icons a.up", function () {
                    const $me = $(this).closest("[data-block]");
                    const $pos = $me.parent().children().index($me);
                    if ($pos === 0) {
                        new Noty({
                            type: "error",
                            text: '{{ trans('visual-editor-for-backpack::interface.already_first') }}',
                        }).show();
                        return false;
                    }
                    const block = $me.data('block');
                    window[block].beforeSort($me);
                    $me.insertBefore($me.parent().children(":eq("+ ($pos - 1) +")"));
                    window[block].afterSort($me);
                });

                $("div.visual-editor-rows").on("click", "div.visual-editor-icons a.down", function () {
                    const $me = $(this).closest("[data-block]");
                    const $pos = $me.parent().children().index($me);
                    if ($pos === $me.parent().children().length - 1) {
                        new Noty({
                            type: "error",
                            text: '{{ trans('visual-editor-for-backpack::interface.already_last') }}',
                        }).show();
                        return false;
                    }
                    const block = $me.data('block');
                    window[block].beforeSort($me);
                    $me.insertAfter($me.parent().children(":eq("+ ($pos + 1) +")"));
                    window[block].afterSort($me);
                });

                $('a.visual-editor-preview').click(function(){
                    var result = {};
                    $('div.visual-editor-rows > div').each(function(){
                        var id = $(this).data("id");
                        var value = $("[name="+id+"]").val();
                        result[id] = value;
                    });
                    $.ajax({
                        url: '{{route('visualEditor.preview')}}',
                        type: 'POST',
                        data: {
                            '_token': '<?php echo csrf_token(); ?>',
                            'data': JSON.stringify(result)
                        },
                        success: function(data, textStatus, xhr) {
                            $.fancybox.open({
                                src  : '<div>' + data + '</div>',
                                type : 'html',
                                touch: false,
                                afterShow : function() {
                                    $("div.visual-editor-rows > [data-block]").each(function (index) {
                                        $me = $(".fancybox-slide--html .fancybox-content > div:eq("+index+")");
                                        window[$(this).data("block")].onPreview($me);
                                    });
                                }
                            });
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            alert("An error occurred.");
                        }
                    });
                });
            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}