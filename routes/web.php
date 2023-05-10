<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Route::get('/','PagesController@homeIndex');


// For Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
    /* php artisan cache:clear
    php artisan route:clear
    php artisan config:clear
    php artisan view:clear */
});

// 404 for undefined routes
/*
Route::any('/{page?}',function(){
    return View::make('pages.error-pages.error-404');
})->where('page','.*');*/
Auth::routes();

Route::group(['middleware' => ['auth']], function () {    
    Route::get('/home', 'HomeController@index')->name('home');                    
    require (__DIR__ . '/rt_teacher.php');

    Route::get('marca', 'MarcaController@index')->name('marca.index');
    Route::post('marca', 'MarcaController@store')->name('marca.store');

    Route::get('tipo-producto', 'TipoProductoController@index')->name('tipo-producto.index');
    Route::post('tipo-producto', 'TipoProductoController@store')->name('tipo-producto.store');

    Route::get('unidad-de-medida', 'UnidadDeMedidaController@index')->name('unidad-de-medida.index');
    Route::post('unidad-de-medida', 'UnidadDeMedidaController@store')->name('unidad-de-medida.store');

    Route::get('producto', 'ProductoController@index')->name('producto.index');
    Route::get('producto/data', 'ProductoController@dataTable')->name('producto.data');
    Route::get('producto/{producto}', 'ProductoController@getQuantity')->name('producto.cantidad');
    Route::post('producto', 'ProductoController@store')->name('producto.store');
    Route::put('producto', 'ProductoController@update')->name('producto.update');

    Route::get('condiciones', 'CondicionesController@index')->name('condiciones.index');

    Route::get('estado', 'EstadoController@index')->name('estado.index');

    Route::get('compra', 'CompraController@index')->name('compra.index');
    Route::get('compra/data', 'CompraController@dataTable')->name('compra.data');
    Route::get('compra/products/{compra}', 'CompraController@getProducts')->name('compra.products');
    Route::post('compra', 'CompraController@store')->name('compra.store');
    Route::put('compra', 'CompraController@update')->name('compra.update');
});