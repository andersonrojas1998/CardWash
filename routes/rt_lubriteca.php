<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'lubriteca'], function () {
    Route::get('', 'LubritecaController@index')->name('lubriteca.index');
    Route::post('', 'LubritecaController@store')->name('lubriteca.store');
    Route::get('sticker/{id}', 'LubritecaController@sticker')->name('lubriteca.sticker');
    Route::get('scanner', 'LubritecaController@scanner')->name('lubriteca.scanner');
});
