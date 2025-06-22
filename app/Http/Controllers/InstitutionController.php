<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index(){
        return view('pages.institution.index');
    }


    public function createForm(){

        return view('pages.institution.create');
    }


    public function show($id){

        
        return view('pages.institution.show', ['institution' => Institution::findOrFail($id)]);
    }


    public function pendingApproval(){

        return view('pages.institution.pending-approval');
    }


}
