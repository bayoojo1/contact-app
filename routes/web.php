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

function getContacts() 
{
    return [
        1 => ['name' => 'Adebayo Ojo', 'phone' => '+2348023950246', 'email' => 'bayo.ojo@smilecoms.com'],
        2 => ['name' => 'Abiodun Abodunde', 'phone' => '+2348033432378', 'email' => 'abiodun.abodunde@smilecoms.com'],
        3 => ['name' => 'Mfon Umoh', 'phone' => '+2348037047789', 'email' => 'mfon.umoh@smilecoms.com'],
    ];
}


Route::get('/', function () {
   return view('welcome'); 
});

Route::prefix('admin')->group(function () {
    Route::get('/contacts', function () {

        $companies = [
            1 => ['name' => 'Smile Communications Ltd', 'contacts' => 1],
            2 => ['name' => 'Smile Communications Ltd', 'contacts' => 2],
            3 => ['name' => 'Smile Communications Ltd', 'contacts' => 3],
        ];

        $contacts = getContacts();
    return view('contacts.index', compact('contacts', 'companies')); //Note: compact function creates an array from variables and their values. Instead of using compact function, we can also pass 'contacts' => $contacts as the second argument of the view.
    })->name('contacts.index');
    
    Route::get('/contacts/create', function () {
        return view('contacts.create');
    })->name('contacts.create');
    
    Route::get('/contacts/{id}', function ($id) {
        $contacts = getContacts();
        
        abort_if(!isset($contacts[$id]), 404); //If you try to access an id that does not exit, this laravel function would redirect to a 404 page

        $contact = $contacts[$id];
        return view('contacts.show')->with('contact', $contact);
    })->where('id', '[0-9]+')->name('contacts.show'); //Note: You can also replace the where constraint above with whereNumber('id') without having to use the regular expression as used above
    
    Route::get('/companies/{name?}', function ($name = null) {
        if ($name) {
            return "Company " . $name;
        } else {
            return "All companies";
        }
    })->where('name', '[a-zA-Z0-9]+')->name('companies.show'); //Note: You can use the whereAlpha('name') or the whereAlphaNumeric('name') 
});

Route::fallback(function () {
    return "<h1>Sorry, the requested page cannot be found!</h1>";
});
