<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        return view('welcome');
    }
}

//When you have a single action in your controller like this, the best approach is to replace your method with a php invoke magic method. In your route declaration, you don't need to pass the method. Just pass the name of your controller
//You can tell artison to create a single action controller with the below command:
// =>php artisan make:controller WelcomeController --invoke
// OR
// =>php artisan make:controller WelcomeController -i
