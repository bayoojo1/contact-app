<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;

use Illuminate\Http\Request;

use App\Models\Contact;

//use Illuminate\Support\Facades\DB;

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

        //$contacts = $this->getContacts();
        //$contacts = Contact::latest()->get();
        //DB::enableQueryLog();
        $query = Contact::query();
        if(request()->query('trash')) {
            $query->onlyTrashed();
        }
        $contacts = $query->latest()->where(function ($query) {
            if($companyId = request()->query("company_id")) {
                $query->where("company_id", $companyId);
            }
        })->where(function ($query) {
            if($search = request()->query('search')) {
                $query->where("first_name", "LIKE", "%{$search}%");
                $query->orWhere("last_name", "LIKE", "%{$search}%");
                $query->orWhere("email", "LIKE", "%{$search}%");
                $query->orWhere("phone", "LIKE", "%{$search}%");
                $query->orWhere("company_id", "LIKE", "%{$search}%");
                $query->orWhere("address", "LIKE", "%{$search}%");
            }
        })->paginate(10);
        //dump(DB::getQueryLog());
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
        $contact = new Contact();
        
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function store(Request $request)
    {
        //dd($request->all());

        //dd($request->collect());

        //dd($request->input('first_name'));

        //dd($request->only(['first_name', 'last_name']));

        // if($request->has('first_name')) {
        //     dd($request->first_name);
        // }

        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        
        Contact::create($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }

    public function show($id) 
    {
        //$contacts = $this->getContacts();
        $contact = Contact::findOrFail($id);
        //abort_if(empty($contact), 404); //If you try to access an id that does not exit, this laravel function would redirect to a 404 page

        //$contact = $contacts[$id];
        return view('contacts.show')->with('contact', $contact);
    }

    // protected function getContacts() 
    // {
    //     return [
    //         1 => ['name' => 'Adebayo Ojo', 'phone' => '+2348023950246', 'email' => 'bayo.ojo@smilecoms.com'],
    //         2 => ['name' => 'Abiodun Abodunde', 'phone' => '+2348033432378', 'email' => 'abiodun.abodunde@smilecoms.com'],
    //         3 => ['name' => 'Mfon Umoh', 'phone' => '+2348037047789', 'email' => 'mfon.umoh@smilecoms.com'],
    //     ];
    // }

    public function edit($id) 
    {
        $companies = $this->company->pluck();
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        
        $contact->update($request->all());

        return redirect()->route('contacts.show', $contact->id)->with('message', 'Contact has been updated successfully');
    }

    public function destroy($id) 
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())
        ->with('message', 'Contact has been moved to trash')
        ->with('undoRoute', $this->getUndoRoute('contacts.restore', $contact));
    }

    public function restore($id) 
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();
        return back()
        ->with('message', 'Contact has been restored from trash')
        ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
    }

    protected function getUndoRoute($name, $resource) 
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }

    public function forceDelete($id) 
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();
        return back()
        ->with('message', 'Contact has been permanently deleted');
    }
}
