<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employees;

class EmployeesController extends Controller
{

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
            'firstname'  => 'required',
            'lastname'   => 'required',
            'company_id' => 'required',
        ]);

        $employee = (is_null($id)) ?
            new Employees() : Employees::findOrFail($id);

        $employee->firstname     = $request->input('firstname');
        $employee->lastname      = $request->input('lastname');
        $employee->company_id_fk = $request->input('company_id');
        $employee->email         = $request->input('email');
        $employee->phone         = $request->input('phone');

        if ($employee->save()) {
            return $employee->id;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employees::destroy($id);
    }
}
