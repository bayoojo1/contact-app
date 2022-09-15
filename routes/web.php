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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
   $html = "
    <h1>Contact App</h1>

    <div>
        <a href='" . route('contacts.index') . "'>All Contacts</a>
        <a href='" . route('contacts.create') . "'>Add Contact</a>
        <a href='" . route('contacts.show', 1) . "'>Show Contact</a>
        <a href='" . route('companies.show') . "'>Show Companies</a>
    </div>
   ";
   return $html; 
});


Route::get('/contacts', function () {
    return "<h1>All Contacts</h1>";
})->name('contacts.index');

Route::get('/contacts/create', function () {
    return "<h1>Add New Contacts</h1>";
})->name('contacts.create');

Route::get('/contacts/{id}', function ($id) {
    return "Contact " . $id;
})->where('id', '[0-9]+')->name('contacts.show'); //Note: You can also replace the where constraint above with whereNumber('id') without having to use the regular expression as used above

Route::get('/companies/{name?}', function ($name = null) {
    if ($name) {
        return "Company " . $name;
    } else {
        return "All companies";
    }
})->where('name', '[a-zA-Z0-9]+')->name('companies.show'); //Note: You can use the whereAlpha('name') or the whereAlphaNumeric('name') 