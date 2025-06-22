<?php

namespace App\Livewire\Budget;

use Livewire\Component;
use App\Models\Budget;
use App\Models\BudgetItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetYearly extends Component
{
    public $selectedYear;
    public $yearlyData = [];
    public $categoryBreakdown = [];
    public $institutionComparison = [];
    
    public function mount()
    {
        $this->selectedYear = date('Y');
        $this->loadYearlyData();
    }
    
    public function updatedSelectedYear()
    {
        $this->loadYearlyData();
    }
    
    public function loadYearlyData()
    {
        $query = Budget::with(['budgetItems', 'institution'])
            ->where('financial_year', $this->selectedYear)
            ->where('status', 'approved');
            
        // Role-based filtering
        if (Auth::user()->role !== 'tra_officer') {
            $query->where('institution_id', Auth::user()->currentInstitution->id);
        }
        
        $budgets = $query->get();
        
        // Calculate yearly summary
        $this->yearlyData = [
            'total_budgets' => $budgets->count(),
            'total_allocated' => $budgets->sum('approved_amount'),
            'total_spent' => $budgets->sum('spent_amount'),
            'total_remaining' => $budgets->sum('remaining_amount'),
            'avg_budget_size' => $budgets->avg('approved_amount'),
            'completion_rate' => $budgets->where('status', 'approved')->count() / max($budgets->count(), 1) * 100
        ];
        
        // Category breakdown
        $this->categoryBreakdown = BudgetItem::select('category', DB::raw('SUM(total_cost) as total_amount'))
            ->whereHas('budget', function($q) {
                $q->where('financial_year', $this->selectedYear)
                  ->where('status', 'approved');
                  
                if (Auth::user()->role !== 'tra_officer') {
                    $q->where('institution_id', Auth::user()->currentInstitution->id);
                }
            })
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get()
            ->toArray();
            
        // Institution comparison (only for TRA officers)
        if (Auth::user()->role === 'tra_officer') {
            $this->institutionComparison = Budget::select('institution_id', DB::raw('SUM(approved_amount) as total_amount'))
                ->with('institution:id,name,code')
                ->where('financial_year', $this->selectedYear)
                ->where('status', 'approved')
                ->groupBy('institution_id')
                ->orderByDesc('total_amount')
                ->get()
                ->toArray();
        }
    }
    
    public function render()
    {
        $years = Budget::distinct('financial_year')->pluck('financial_year')->sort()->reverse();
        
        return view('livewire.budget.budget-yearly', compact('years'));
    }
}