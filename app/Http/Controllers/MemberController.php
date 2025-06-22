<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(){

        return view('pages.member.index'); 
    }

    public function pendingApproval(){
        return view('pages.member.pending-approval');
    }

    public function leadersAndSupervisors(){
        return view('pages.member.leaders-and-supervisors');
    }

    public function create(){

        return view('pages.member.create');
    }
}
