<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(){

        return view('pages.certificates.index');    
    }


    public function create(){


        return view('pages.certificates.issue');
    }


    public function verify($id){

        // Here you would typically fetch the certificate details from the database
        // using the $id and pass it to the view.
        // For now, we'll just return a view with a placeholder.

        return view('pages.certificates.verify', ['id' => $id]);

    }
}
