<?php

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
    
});