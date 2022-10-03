<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository 
{
    public function pluck()
    {
        // return [
        //     1 => 'Smile Communications Ltd',
        //     2 => 'Smile Communications Ltd',
        //     3 => 'Smile Communications Ltd',
        // ];
        //return Company::orderBy('name')->pluck('name', 'id');

        $data = [];

        $companies = Company::orderBy('name')->get();

        foreach ($companies as $company) {
            $data[$company->id] = $company->name . " (" . $company->contacts()->count() . ")";
        }
        return $data;
    }
}