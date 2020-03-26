<?php

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'Bedoz\VisualEditorForBackpack\app\Http\Controllers\Fields',
], function () {
    Route::post('fields/slideshow/saveImage', 'SlideshowController@saveImage')->name('fields.slideshow.saveImage');
    Route::post('fields/slideshow/saveCroppedImage', 'SlideshowController@saveCrop')->name('fields.slideshow.saveCrop');
    Route::post('fields/slideshow/deleteCroppedImage', 'SlideshowController@deleteCrop')->name('fields.slideshow.deleteCrop');
    Route::post('fields/slideshow/deleteImage', 'SlideshowController@deleteImage')->name('fields.slideshow.deleteImage');

    Route::post('fields/slideshow/order', 'SlideshowController@order')->name('fields.slideshow.order');
    Route::post('fields/slideshow/crop', 'SlideshowController@crop')->name('fields.slideshow.crop');
});