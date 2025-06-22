<?php

namespace App\Livewire\Budget;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetPending extends Component
{
    use WithPagination;
    
    public $search = '';
    public $selectedBudget = null;
    public $reviewComments = '';
    public $approvedAmount = '';
    public $action = '';
    
    public function selectBudget($budgetId, $action)
    {
        $this->selectedBudget = Budget::with(['budgetItems', 'creator', 'institution'])->findOrFail($budgetId);
        $this->action = $action;
        $this->approvedAmount = $this->selectedBudget->total_amount;
        $this->reviewComments = '';
    }
    
    public function processReview()
    {
        $this->validate([
            'reviewComments' => 'required|string|min:10',
            'approvedAmount' => $this->action === 'approve' ? 'required|numeric|min:0' : 'nullable'
        ]);
        
        $status = match($this->action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'revision' => 'revision_required'
        };
        
        $updateData = [
            'status' => $status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $this->reviewComments
        ];
        
        if ($this->action === 'approve') {
            $updateData['approved_by'] = Auth::id();
            $updateData['approved_at'] = now();
            $updateData['approved_amount'] = $this->approvedAmount;
            $updateData['remaining_amount'] = $this->approvedAmount;
        } elseif ($this->action === 'reject') {
            $updateData['rejection_reason'] = $this->reviewComments;
        }
        
        $this->selectedBudget->update($updateData);
        
        $this->selectedBudget = null;
        $this->reset(['reviewComments', 'approvedAmount', 'action']);
        
        session()->flash('message', 'Budget review processed successfully!');
    }
    
    public function render()
    {
        $budgets = Budget::with(['institution', 'creator', 'budgetItems'])
            ->where('status', 'submitted')
            ->when($this->search, function($q) {
                return $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('budget_code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        return view('livewire.budget.budget-pending', compact('budgets'));
    }
}