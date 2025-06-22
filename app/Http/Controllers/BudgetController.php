<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(){
        return view('pages.budgets.index');
    }

    public function create(){
        return view('pages.budgets.create');
    }

    public function pendingApproval(){

        return view('pages.budgets.pending-approval');
    }


    public function yearlyPlans(){

        return view('pages.budgets.yearly-plans');
    }


    public function show($id){

        // Assuming you have a Budget model and you want to show the details of a specific budget
        
        return view('pages.budgets.show', compact('id'));
    }
    
}
