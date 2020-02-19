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

    <div class="visual-editor-templates">
        {{-- Load available templates --}}
        @foreach(config('visual-editor.blocks') as $block)
            <div class="row"
                 data-block="{{ $block }}"
                 data-block-label="{{ trans("visual-editor-for-backpack::blocks.{$block::$name}.name") }}">
                <div class="col">
                    <div class="visual-editor-content">
                        {!! $block::renderBackend() !!}
                    </div>
                </div>
                <div class="col">
                    <div class="visual-editor-icons">
                        <button class="up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button class="down">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button class="trash">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-row">
        <div class="col">
            <select name="templates" class="form-control">
                <option value="" disabled selected>
                    {{ trans('visual-editor-for-backpack::interface.choose_a_block') }}
                </option>
                @foreach($field['templates'] ?? config('visual-editor.blocks') as $block)
                    <option value="{{ $block }}">
                        {{ trans("visual-editor-for-backpack::blocks.{$block::$name}.name") }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <button class="add btn btn-default">
                {{ trans('visual-editor-for-backpack::interface.add_block') }}
            </button>
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
        <style type="text/css">

        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script>
            function bpFieldInitToggleElement(element) {
                // element will be a jQuery wrapped DOM node

            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}