<div>
    <label>{{$class::$label}}</label>
</div>
<div data-preview="[data-id='{{$id}}'] #bottoni_crop .img-preview"
     data-aspectRatio="0"
     data-crop="true"
>
    <!-- Wrap the image or canvas element with a block element (container) -->
    <div class="row sortable">
        @if (isset($value) && !empty($value))
            @php
                $valueDecoded = json_decode($value);
            @endphp
        @endif
        @if (isset($valueDecoded) && is_array($valueDecoded) && count($valueDecoded))
            @foreach($valueDecoded as $key => $file_path)
                <div class="file-preview col-sm-3" style="margin-bottom: 20px;" data-gallery-data="{{json_encode($file_path)}}">
                    <img src="{{asset(\Storage::disk('public')->url($file_path->image))}}">
                    <div class="btn-group pull-right">
                        <a id="edit_button" href="javascript:;" class="btn btn-primary btn-xs file-edit-button" title="Edit image"><i class="fa fa-edit"></i></a>
                        <a id="clear_button" href="javascript:;" class="btn btn-danger btn-xs file-clear-button" title="Clear image"><i class="fa fa-remove"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            @endforeach
        @else
            <div class="col-sm-12 text-danger">
                {{ trans('visual-editor-for-backpack::blocks/' . $class::$name . '.no_images') }}
            </div>
        @endif
    </div>
    <div class="row" id="bottoni_crop" style="display: none;">
        <div class="col-sm-6" style="margin-bottom: 20px;">
            <img id="mainImage" src="">
        </div>
        <div class="col-sm-6">
            <div class="row" id="dettaglioTaglio">
                <div class="col-sm-12" style="padding-bottom: 10px;">
                    <div class="btn-group">
                        <button class="btn btn-success" id="save" type="button"><i class="fa fa-save"></i></button>
                        <button class="btn btn-default" id="rotateLeft" type="button"><i class="fa fa-rotate-left"></i></button>
                        <button class="btn btn-default" id="rotateRight" type="button"><i class="fa fa-rotate-right"></i></button>
                        <button class="btn btn-default" id="zoomIn" type="button"><i class="fa fa-search-plus"></i></button>
                        <button class="btn btn-default" id="zoomOut" type="button"><i class="fa fa-search-minus"></i></button>
                        <button class="btn btn-warning" id="reset" type="button"><i class="fa fa-undo"></i></button>
                        <button class="btn btn-danger" id="remove" type="button"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="col-sm-12" style="padding-bottom: 10px;" id="aspectRatio">
                    <div>{{ trans('visual-editor-for-backpack::blocks/' . $class::$name . '.ratio') }}:</div>
                    <select>
                        <option selected disabled hidden>{{ trans('visual-editor-for-backpack::blocks/' . $class::$name . '.select_ratio') }}</option>
                        @foreach($class::$sizes as $titolo => $data)
                            <option value="{{$titolo}}" data-ratio="{{$data['ratio']}}">{{$titolo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="docs-preview col-sm-12">
                    <div class="img-preview preview-lg">
                        <img src="" style="display: block; min-width: 0 !important; min-height: 0 !important; max-width: none !important; max-height: none !important; margin-left: -32.875px; margin-top: -18.4922px; transform: none;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="tagliDisponibili">
            <div class="row">
                <div class="col-sm-2 taglio" id="sample" style="display: none;">
                    <div class="titolo"></div>
                    <img src="">
                    <a href="javascript:;" class="btn btn-danger btn-xs pull-right cancella_miniatura" title="Delete cut"><i class="fa fa-remove"></i></a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- upload multiple input -->
<div class="galleryMultipleUpload">
    <label>{{ trans('visual-editor-for-backpack::blocks/' . $class::$name . '.upload_images') }}</label>
    <input type="hidden" name="{{$id}}" value="{{$value}}">
    <input
        type="file"
        accept="image/*"
        id="slideshow_file_input"
        class="form-control"
        multiple
    >
    {{-- HINT --}}
    @if (isset($class::$hint))
        <p class="help-block">{!! $class::$hint !!}</p>
    @endif
</div>

<div class="d-none new-elements">
    <div class="file-preview col-sm-3" style="margin-bottom: 20px;" data-gallery-data="">
        <img src="">
        <div class="btn-group pull-right">
            <a id="edit_button" href="javascript:;" class="btn btn-primary btn-xs file-edit-button" title="Edit image"><i class="fa fa-edit"></i></a>
            <a id="clear_button" href="javascript:;" class="btn btn-danger btn-xs file-clear-button" title="Clear image"><i class="fa fa-remove"></i></a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>