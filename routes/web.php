<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();



Route::middleware('auth')->group(function(){

    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::post('/markAsRead', 'HomeController@markNotif')->name('mark_as_seen');
    
    Route::resource('/task', 'TaskController');
    Route::post('/task/complete', 'TaskController@completeTask')->name('task.update.status');
    Route::post('/task/filter', 'TaskController@filterData')->name("task.filter");
    Route::post('/task/date/clicked', 'TaskController@taskByDateClicked')->name("task.date.clicked");
    
    Route::resource('/setting', 'SettingController');
    Route::put('/settings/update', 'SettingController@updateData')->name('setting.update.data');
});