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

Route::get('/', function () {
    return redirect('books');
});


/*
 * Resource route for Book Controller
 * @author sonny.parungao
 * @created 10/15/2018
 */
Route::resource("books","BookController");
//Custom route for updating book
Route::post('books/update', 'BookController@update');
//Custom route for deleting book
Route::post('books/destroy', 'BookController@destroy');
//Custom route for exporting books record
Route::post("export","BookController@export");

