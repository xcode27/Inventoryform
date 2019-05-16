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

Route::get('/', "PagesController@index");
Route::get('/home', "PagesController@home");
Route::get('/CreateMenu', "PagesController@CreateMenu");
Route::get('/MappedMenu', "PagesController@MappedMenu");
Route::get('/CreateUser', "PagesController@CreateUser");
Route::get('/setUpChartOfAccounts/{id}', "PagesController@setUpChartOfAccounts");
Route::get('/chartofaccounts/{id}', "PagesController@chartofaccounts");
Route::get('/receivedInventory/{id}', "PagesController@receivedInventory");
Route::get('/monitoringreport/{id}', "PagesController@monitoringreport");
Route::get('/createPO/{id}', "PagesController@createPO");
Route::get('/poServe/{id}', "PagesController@poServe");
Route::get('/storeMapping/{id}', "PagesController@storeMapping");
Route::get('/actualinventory/{id}', "PagesController@actualinventory");
Route::get('/inventoryHistory/{id}', "PagesController@inventoryHistory");
//inventory
Route::post('/saveInventory', 'invreceivingform@saveInventory');
Route::get('/displayInventory',"invreceivingform@displayInventory")->name('displayInventory');
Route::get('/getMonitoring/{year}',"invreceivingform@getMonitoring")->name('getMonitoring');
Route::get('/downloadReport/{param}',"invreceivingform@downloadReport");
Route::post('/updateInventory', 'invreceivingform@updateInventory');
Route::get('/deleteInventory/{id}',"invreceivingform@deleteInventory");
Route::post('/addform', 'invreceivingform@addform');
Route::get('/displayformsubmitted/{param}',"invreceivingform@displayformsubmitted")->name('displayformsubmitted');
Route::get('/removeForm/{id}',"invreceivingform@removeForm");
Route::get('/deleteInventorySubmitted/{id}',"invreceivingform@deleteInventorySubmitted");
Route::post('/updatesubmittedInventory', 'invreceivingform@updatesubmittedInventory');
//created PO
Route::post('/addToList', 'PoController@addToList');
Route::post('/savePoHead', 'PoController@savePoHead');
Route::get('/getPoCreate/{po}',"PoController@getPoCreate");
Route::post('/savePoServe', 'PoController@savePoServe');
Route::post('/getDataFromInventory', 'PoController@getDataFromInventory');
Route::get('/removeProductDetails/{id}',"PoController@removeProductDetails");
Route::get('/removeHeaders/{id}',"PoController@removeHeaders");
Route::post('/updatePoHead', 'PoController@updatePoHead');
Route::get('/displayHeader',"PoController@displayHeader")->name('displayHeader');
Route::post('/displayOrderProductDetails', 'PoController@displayOrderProductDetails');
Route::get('/displayraw/{tranno}',"PoController@displayraw")->name('displayraw');
Route::get('/displayPOservecreated/{user}',"PoController@displayPOservecreated")->name('displayPOservecreated');
Route::get('/removePoServe/{id}',"PoController@removePoServe");
//store mapping
Route::post('/saveStore', 'StoreMappingController@saveStore');
Route::post('/saveForm', 'StoreMappingController@saveForm');
Route::get('/displayFormperOutlet/{storecode}',"StoreMappingController@displayFormperOutlet")->name('displayFormperOutlet');
Route::get('/removeform/{id}',"StoreMappingController@removeform");
Route::get('/displayStoreMapped',"StoreMappingController@displayStoreMapped")->name('displayStoreMapped');
Route::post('/updateStore', 'StoreMappingController@updateStore');
Route::get('/removestore/{id}',"StoreMappingController@removestore");
Route::get('/getForm/{store}',"StoreMappingController@getForm");
