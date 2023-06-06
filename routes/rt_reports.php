<?php
Route::group(['prefix' => 'reports'], function(){
    Route::get('getSalesxMonth', 'ReportsController@getSalesxMonth');    
    Route::get('getSalesxDay', 'ReportsController@getSalesxDay'); 
    Route::get('Ingreso-Egreso', 'ReportsController@index_income_expeses'); 
    Route::get('dt_expenses_month', 'ReportsController@dt_expenses_month'); 
    Route::get('chart_income_service', 'ReportsController@chart_income_service'); 
});
