<?php
Route::group(['prefix' => 'docentes'], function(){
    Route::get('inicio', 'DocenteController@index');
    Route::get('creacion', 'DocenteController@index_create');
    Route::post('create', 'DocenteController@create');
    Route::post('update', 'DocenteController@update');
    Route::get('show/{id}', 'DocenteController@showUser');
});
Route::get('/dt_user', 'DocenteController@dt_user');
Route::get('/showTeacher', 'DocenteController@showTeacher');
Route::get('/showGradesAssign', 'DocenteController@gradeAssignments');
Route::get('/assignmentCourseTeacher', 'DocenteController@assignmentCourseTeacher');