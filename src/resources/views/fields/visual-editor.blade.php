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
            @include('visual-editor-for-backpack::interface.block', ['block' => $block, 'crud' => $crud, 'entry' => $entry ?? null])
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
                        {{ trans("visual-editor-for-backpack::blocks/{$block::$name}.name") }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <a href="javascript:;" class="add btn btn-default">
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