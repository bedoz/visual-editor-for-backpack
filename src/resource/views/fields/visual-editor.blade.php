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
        <?php dd(view()->getFactory()->getFinder()); ?>
        @foreach(view() as $template)
            <div class="visual-editor-row"
                 data-template="{{ $template }}"
                 data-template-label="{{ trans("visualcomposer::templates.{$template::$name}.name") }}">
                <div class="vc-handle"></div>
                <div class="vc-icons">
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
                <div class="vc-content">
                    {!! $template::renderCrud() !!}
                </div>
            </div>
        @endforeach
    </div>

    <select name="templates">
        <option value="" disabled selected>
            {{ trans('visualcomposer::interface.choose_a_template') }}
        </option>
        @foreach($field['templates'] ?? config('visualcomposer.templates') as $template)
            <option value="{{ $template }}">
                {{ trans("visualcomposer::templates.{$template::$name}.name") }}
            </option>
        @endforeach
    </select>
    <button class="add">
        {{ trans('visualcomposer::interface.add_template') }}
    </button>
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