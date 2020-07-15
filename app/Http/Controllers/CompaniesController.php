<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Employees;
use App\Http\Resources\Companies as CompaniesResource;
use App\Http\Resources\Employees as EmployeesResource;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Companies::paginate(10);

        return CompaniesResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $id = $request->input('id');

        $request->validate([
            'name' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100'
        ]);

        $name     = $request->input('name');
        $email    = $request->input('email');
        $website  = $request->input('website');
        $file     = $request->file('file');
        $fileName = '';
        
        if (!is_null($file)) {
            $time     = time();
            $fileExt  = $file->extension();
            $fileName = str_replace(' ', '_', trim($name));
            $fileName = strtolower($fileName);
            $fileName = "{$fileName}{$time}.{$fileExt}";

            $path = $file->storeAs('public', $fileName);
        }

        $company = (is_null($id)) ?
            new Companies() : Companies::findOrFail($id);

        $company->name    = $name;
        $company->logo    = $fileName;
        $company->email   = $email;
        $company->website = $website;
        
        if ($company->save()) {
            return $company->id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employees = Employees::where('company_id_fk', $id)->paginate(10);

        return EmployeesResource::collection($employees);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Companies::destroy($id);
    }
}
