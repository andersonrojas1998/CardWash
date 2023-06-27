<?php

Route::group(['prefix' => 'venta'], function(){
    Route::get('', 'VentaController@index')->name('venta.index');
    Route::get('create', 'VentaController@create')->name('venta.create');
    Route::get('create-market', 'VentaController@createMarket')->name('venta.create-market');
    Route::get('{venta}', 'VentaController@show')->name('venta.show');
    Route::get('{venta}/edit', 'VentaController@edit')->name('venta.edit');
    Route::post('', 'VentaController@store')->name('venta.store');
    Route::put('{venta}', 'VentaController@update')->name('venta.update');    
});

Route::get('ticketPrint', 'ticketController@ticketPrint');
Route::get('createPp', 'ticketController@create');