<?php

namespace App\Http\Controllers;

use App\Models\Event;
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


    public function show($id){

        $event=Event::findOrFail($id);
        return view('pages.events.show', compact('event'));
    }
}
