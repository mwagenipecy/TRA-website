<?php

// app/Http/Livewire/Budget/BudgetIndex.php
namespace App\Livewire\Budget;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Budget;
use App\Models\Institution;
use Illuminate\Support\Facades\Auth;

class BudgetIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $yearFilter = '';
    public $institutionFilter = '';
    
    protected $queryString = ['search', 'statusFilter', 'typeFilter', 'yearFilter'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = Budget::with(['institution', 'creator', 'budgetItems'])
            ->when($this->search, function($q) {
                return $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('budget_code', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function($q) {
                return $q->where('type', $this->typeFilter);
            })
            ->when($this->yearFilter, function($q) {
                return $q->where('financial_year', $this->yearFilter);
            })
            ->when($this->institutionFilter, function($q) {
                return $q->where('institution_id', $this->institutionFilter);
            });
            
        // Role-based filtering
        // if (Auth::user()->role !== 'tra_officer') {
        //     $query->where('institution_id', Auth::user()->currentInstitution->id);
        // }
        
        $budgets = $query->latest()->paginate(10);
        
        $institutions = Institution::where('status', 'active')->get();
        $years = Budget::distinct('financial_year')->pluck('financial_year')->sort();
        
        return view('livewire.budget.budget-index', [
            'budgets' => $budgets,
            'institutions' => $institutions,
            'years' => $years
        ]);
    }
    
    public function deleteBudget($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);
        
        // Authorization check
        if (Auth::user()->role !== 'tra_officer' && $budget->created_by !== Auth::id()) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }
        
        $budget->delete();
        session()->flash('message', 'Budget deleted successfully.');
    }
}