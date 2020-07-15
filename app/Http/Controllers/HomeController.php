<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function displayCompany($id)
    {
        $company = Companies::where('id', $id)->first();

        $params = [
            'id'      => $company->id,
            'name'    => $company->name,
            'logo'    => $company->logo,
            'email'   => $company->email,
            'website' => $company->website,
        ];
        
        return view('company', ['companyDetails' => $params]);
    }
}
