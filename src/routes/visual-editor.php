<?php

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Fields',
], function () {
    Route::post('fields/slideshow/order', 'SlideshowController@order')->name('fields.slideshow.order');
    Route::post('fields/slideshow/crop', 'SlideshowController@crop')->name('fields.slideshow.crop');
    Route::post('fields/slideshow/saveCroppedImage', 'SlideshowController@saveImage')->name('fields.slideshow.saveImage');
    Route::post('fields/slideshow/deleteCroppedImage', 'SlideshowController@deleteImage')->name('fields.slideshow.deleteImage');
});