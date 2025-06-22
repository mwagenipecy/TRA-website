<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function users(){

        return view('pages.users.index');   
    }


    public function roles(){

        return view('pages.roles.index');   
    }


    public function settings(){

        return view('pages.settings.index');   
    }


    public function systemLogs(){

        return view('pages.audit.index');
    }
}
