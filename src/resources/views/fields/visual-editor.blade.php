<div @include('crud::inc.field_wrapper_attributes') >
    @include('crud::inc.field_translatable_icon')
    <label>
        {{ $field['label'] }}
    </label>

    <input type="hidden"
           name="{{ $field['name'] }}"
           value="{!! htmlspecialchars(old($field['name']) ?: $field['value'] ?? json_encode($field['default'] ?? []), ENT_QUOTES, 'UTF-8', true) !!}"
        @include('crud::inc.field_attributes')>

    @if (isset($field['hint']))
        <p class="help-block">
            {!! $field['hint'] !!}
        </p>
    @endif

    <div class="visual-editor-rows"></div>

    <div class="visual-editor-templates" style="display: none;">
        {{-- Load available templates --}}
        @foreach(config('visual-editor.blocks') as $block)
            @include('visual-editor-for-backpack::interface.block', ['block' => $block, 'crud' => $crud, 'entry' => $entry ?? null])
        @endforeach
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
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        @foreach(config('visual-editor.blocks') as $block)
            {!! $block::pushScripts() !!}
        @endforeach
        <script>
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
                    window[block](element);
                });

                $("div.visual-editor-rows").on("click", "div.visual-editor-icons a.trash", function () {
                    $(this).closest("[data-block]").remove();
                });
            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}