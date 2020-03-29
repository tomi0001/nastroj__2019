<?php

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

Route::get('/', "ControllerMain@Main");
Route::get('/User/Register', "ControllerUser@Register");
Route::post('/User/Register_action', "ControllerUser@register_action");
Route::post('/User/Login_action', "ControllerUser@login_action");
Route::get('/User/Login', "ControllerUser@login");
Route::get('/User/Logout', "ControllerUser@logout_action");
Route::get('/User/Setting', "ControllerUser@Setting");
Route::get('/User/changeHour', "ControllerUser@changeHour");
Route::post('/User/changePassword', "ControllerUser@changePassword");
Route::get('/User/changeHash', "ControllerHash@updateHash");

Route::get('/UserDr/Logout', "ControllerDrUser@logout_action");

Route::post('/UserDr/Login_action', "ControllerDrUser@loginAction");


Route::get('/Main/{year?}/{month?}/{day?}/{action?}', "ControllerMain@main");

Route::get('/MainDr/{year?}/{month?}/{day?}/{action?}', "ControllerDrMain@main");

Route::get('/Mood/add', "ControllerMood@add");
Route::get('/Mood/showDescription',"ControllerMood@showDescription");
Route::get('/Mood/delete',"ControllerMood@delete");
Route::get("/Mood/addDescription","ControllerMood@addDescription");
Route::get("/Mood/editDescription","ControllerMood@editDescription");
Route::get("/Mood/edit","ControllerMood@editMood");
Route::get("/Mood/editAction","ControllerMood@editMoodAction");

Route::get('/MoodDr/showDescription',"ControllerDrMood@showDescription");


Route::get("/Produkt/Search","ControllerSearch@main");
Route::get("/Produkt/Search_action","ControllerSearch@searchAction");
Route::get("/Produkt/actionAI","ControllerSearch@searchAI");
Route::get("/Produkt/actionCountMood","ControllerSearch@searchMood");


Route::get("/ProduktDr/Search","ControllerDrSearch@main");
Route::get("/ProduktDr/Search_action","ControllerDrSearch@searchAction");
Route::get("/ProduktDr/actionAI","ControllerDrSearch@searchAI");


Route::get("/Drugs/addDrugs","ControllerMood@addDrugs");
Route::get("/Drugs/show","ControllerMood@showDrugs");
Route::get("/Drugs/Delete","ControllerMood@deleteDrugs");

Route::get("/DrugsDr/show","ControllerDrMood@showDrugs");


Route::get('/Sleep/add', "ControllerMood@addSleep");
Route::get('/Sleep/delete', "ControllerMood@deleteSleep");

Route::get("/PDF/generate","ControllerSearch@savePDF");
Route::get("/PDFDr/generate","ControllerDrSearch@savePDF");
//Route::get("/Edit","ControllerMain@editMood ");

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');