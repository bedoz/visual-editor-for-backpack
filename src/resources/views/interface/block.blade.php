<div class="row"
     data-block="{{ $block }}"
     data-block-label="{{ trans("visual-editor-for-backpack::blocks.{$block::$name}.name") }}">
    <div class="col">
        <div class="visual-editor-content">
            {!! $block::renderBackend($crud, $entry) !!}
        </div>
    </div>
    <div class="col-auto">
        <div class="visual-editor-icons">
            <a href="javascript:;" class="up btn btn-primary">
                <i class="fa fa-arrow-up"></i>
            </a>
            <a href="javascript:;" class="down btn btn-primary">
                <i class="fa fa-arrow-down"></i>
            </a>
            <a href="javascript:;" class="trash btn btn-danger">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</div>