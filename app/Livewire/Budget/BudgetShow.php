<?php

namespace App\Livewire\Budget;

use Livewire\Component;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetShow extends Component
{
    public $budget;
    public $budgetId;
    
    public function mount($id)
    {
        $this->budgetId = $id;
        $this->budget = Budget::with(['budgetItems', 'creator', 'institution', 'reviewer', 'approver'])
            ->findOrFail($id);
            
        // Authorization check
        // if (Auth::user()->role !== 'tra_officer' && 
        //     $this->budget->institution_id !== Auth::user()->currentInstitution->id) {
        //     abort(403, 'Unauthorized access to budget.');
        // }


    }
    
    public function submitForReview()
    {
        if ($this->budget->status !== 'draft') {
            session()->flash('error', 'Only draft budgets can be submitted for review.');
            return;
        }
        
        $this->budget->update([
            'status' => 'submitted'
        ]);
        
        session()->flash('message', 'Budget submitted for review successfully!');
    }
    
    public function render()
    {
        return view('livewire.budget.budget-show');
    }
}