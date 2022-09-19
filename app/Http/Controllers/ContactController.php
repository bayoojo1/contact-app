<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    //The protected property and construct function below shows how to inject dependencies into the controller and how the dependency is accessed and used in the index function below:
    protected $company;

    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }

    public function index()
    {
        // $companies = [
        //     1 => ['name' => 'Smile Communications Ltd', 'contacts' => 1],
        //     2 => ['name' => 'Smile Communications Ltd', 'contacts' => 2],
        //     3 => ['name' => 'Smile Communications Ltd', 'contacts' => 3],
        // ];
        $companies = $this->company->pluck();

        $contacts = $this->getContacts();
        
        return view('contacts.index', compact('contacts', 'companies')); //Note: compact function creates an array from variables and their values. Instead of using compact function, we can also pass 'contacts' => $contacts as the second argument of the view.
    }

    public function create()
    {
        // $companies = [
        //     1 => ['name' => 'Smile Communications Ltd', 'contacts' => 1],
        //     2 => ['name' => 'Smile Communications Ltd', 'contacts' => 2],
        //     3 => ['name' => 'Smile Communications Ltd', 'contacts' => 3],
        // ];
        $companies = $this->company->pluck();
        
        return view('contacts.create', compact('companies'));
    }

    public function show($id) 
    {
        $contacts = $this->getContacts();
        
        abort_if(!isset($contacts[$id]), 404); //If you try to access an id that does not exit, this laravel function would redirect to a 404 page

        $contact = $contacts[$id];
        return view('contacts.show')->with('contact', $contact);
    }

    protected function getContacts() 
    {
        return [
            1 => ['name' => 'Adebayo Ojo', 'phone' => '+2348023950246', 'email' => 'bayo.ojo@smilecoms.com'],
            2 => ['name' => 'Abiodun Abodunde', 'phone' => '+2348033432378', 'email' => 'abiodun.abodunde@smilecoms.com'],
            3 => ['name' => 'Mfon Umoh', 'phone' => '+2348037047789', 'email' => 'mfon.umoh@smilecoms.com'],
        ];
    }
}
