<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
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


Route::get('/', WelcomeController::class);

Route::prefix('admin')->group(function () {

    Route::controller(ContactController::class)->group(function () { //This is how you define multiple route that share the same controller
        Route::get('/contacts', 'index')->name('contacts.index');
    
        Route::get('/contacts/create', 'create')->name('contacts.create');
        
        Route::get('/contacts/{id}', 'show')->where('id', '[0-9]+')->name('contacts.show'); //Note: You can also replace the where constraint above with whereNumber('id') without having to use the regular expression as used above
    });
    // Below is how you define your controller/route/view when you are not grouping multiple routes that uses same controller:

    // Route::get('/contacts/{id}', [ContactController::class, 'show'])->where('id', '[0-9]+')->name('contacts.show'); //Note: You can also replace the where constraint above with whereNumber('id') without having to use the regular expression as used above

    //We can create controllers that contains all the CRUD resources with the command
    //=>php artisan make:controller <Controller Name> --resource
    //OR
    //=>php artisan make:controller <Controller Name> -r
    //And below shows the way to create routes to reference those controller resources:

    Route::resource('/companies', CompanyController::class);
    //We can also register some resources as one, by defining the resources method, and passing an associative array containing our controllers along with the paths
    Route::resources([
        '/tags' => TagController::class,
        '/tasks' => TaskController::class
    ]);
    //We can view all the route list by path with the below commands:
    //=>php artisan route:list --name=tags
    //=>php artisan route:list --name=tasks

//You can also chain a name function to the controller class like below. In that case, you only need to indicate the view name with the route declaration:

    // Route::controller(ContactController::class)->name('contacts.)->group(function () { 
    //     Route::get('/contacts', 'index')->name('index');
    
    //     Route::get('/contacts/create', 'create')->name('create');
        
    //     Route::get('/contacts/{id}', 'show')->where('id', '[0-9]+')->name('show'); 
    // });
    
    
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
