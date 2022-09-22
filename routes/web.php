<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ContactNoteController;
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

    //You can also chain a name function to the controller class like below. In that case, you only need to indicate the view name with the route declaration:

    // Route::controller(ContactController::class)->name('contacts.)->group(function () { 
    //     Route::get('/contacts', 'index')->name('index');
    
    //     Route::get('/contacts/create', 'create')->name('create');
        
    //     Route::get('/contacts/{id}', 'show')->where('id', '[0-9]+')->name('show'); 
    // });



    // Below is how you define your controller/route/view when you are not grouping multiple routes that uses same controller:

    // Route::get('/contacts/{id}', [ContactController::class, 'show'])->where('id', '[0-9]+')->name('contacts.show'); //Note: You can also replace the where constraint above with whereNumber('id') without having to use the regular expression as used above

    //RESOURCE CONTROLLER
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

    //We can also control the CRUD action to include while creating a route by using the 'only' and 'except' method like so:

    Route::resource('/activities', ActivityController::class)->only([ 
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
    //The above will create a route with only the specified CRUD actions in the array

    // Route::resource('/activities', ActivityController::class)->except([ 
    //     'create', 'store', 'edit', 'update', 'destroy'
    // ]);
    //The above will create a route with the CRUD action not listed in the array

    //You can check these route with the command:
    //=>php artisan route:list --name=activities

    //API RESOURCE ROUTES
    //API doesn't need the edit or create CRUD action
    //Laravel provides a better way of creating API controller resources
    //=>php artisan make:controller API\<controller name> --api
    //This will create the specified controller in the API folder
    //You can check the API route definition in the api.php file

    
    //NESTED RESOURCES
    //Nested resources defines routes that are children of another route
    Route::resource('/contacts.notes', ContactNoteController::class);
    //From above, 'contacts is the parent resource and 'notes' is the child resource

    //CUSTOMIZING THE RESOURCE ROUTE
    Route::resource('/activities', ActivityController::class)->names([
        'index' => 'activities.all',
        'show' => 'activities.view'
    ]);
    //If we run the command to show this route list
    //=>php artisan route:list --name=activities
    //We won't see the 'activities.index' and 'activities.show' resources in the output, rather, it would be replaced by 'activities.all' and 'activities/view' respectively

    //NOTE: By default, the route resource will create a route parameter, which is a singular form of the route resource name, e.g. 'activities' will create 'activity', 'people' will create 'person'. A sample route list command output is show below that shows the parameter - activity:

    //PUT|PATCH       admin/activities/{activity} ....................... activities.update › ActivityController@update

    //Should you want to change the parameter name for any reason, you can use the parameter method like so:
        // Route::resource('/activities', ActivityController::class)->parameters([
        //     'activites' => 'active'
        // ]);
    //When you check with the route list command, you'll get the below:

    //PUT|PATCH       admin/activities/{active} ....................... activities.update › ActivityController@update

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
