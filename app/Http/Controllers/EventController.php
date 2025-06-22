<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){

        return view('pages.events.index');
    }

    public function create(){

        return view('pages.events.create');
    }

    public function calendar(){

        return view('pages.events.calendar');
    }


    public function registrations(){

        return view('pages.events.registrations');
    }
}
