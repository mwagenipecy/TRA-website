<?php
namespace App\Livewire\Budget;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Institution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BudgetCreate extends Component
{
    use WithFileUploads;
    
    public $title;
    public $type = 'yearly';
    public $description;
    public $objectives = [];
    public $total_amount = 0;
    public $financial_year;
    public $start_date;
    public $end_date;
    public $priority_level = 3;
    public $is_recurring = false;
    public $recurrence_pattern;
    public $attachments = [];
    
    // Budget Items
    public $budgetItems = [];
    public $newItem = [
        'category' => '',
        'item_name' => '',
        'description' => '',
        'quantity' => 1,
        'unit_cost' => 0,
        'unit_of_measure' => '',
        'justification' => '',
        'priority' => 3,
        'is_mandatory' => true
    ];
    
    public $categories = [
        'Training Events' => 'Training Events',
        'Welcome Ceremonies' => 'Welcome Ceremonies',
        'Graduations' => 'Graduations',
        'Joint Activities' => 'Joint Activities',
        'Equipment' => 'Equipment',
        'Materials' => 'Materials',
        'Transport' => 'Transport',
        'Accommodation' => 'Accommodation',
        'Meals' => 'Meals',
        'Speakers/Facilitators' => 'Speakers/Facilitators',
        'Venue' => 'Venue',
        'Marketing' => 'Marketing',
        'Others' => 'Others'
    ];
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'type' => 'required|in:yearly,event,project,emergency,equipment',
        'description' => 'required|string',
        'financial_year' => 'required|integer|min:2020|max:2030',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'priority_level' => 'required|integer|min:1|max:5',
        'budgetItems.*.category' => 'required|string',
        'budgetItems.*.item_name' => 'required|string|max:255',
        'budgetItems.*.quantity' => 'required|integer|min:1',
        'budgetItems.*.unit_cost' => 'required|numeric|min:0'
    ];
    
    public function mount()
    {
        $this->financial_year = date('Y');
        $this->start_date = date('Y-01-01');
        $this->end_date = date('Y-12-31');
    }
    
    public function addObjective()
    {
        $this->objectives[] = '';
    }
    
    public function removeObjective($index)
    {
        unset($this->objectives[$index]);
        $this->objectives = array_values($this->objectives);
    }
    
    public function addBudgetItem()
    {
        $this->validate([
            'newItem.category' => 'required|string',
            'newItem.item_name' => 'required|string|max:255',
            'newItem.quantity' => 'required|integer|min:1',
            'newItem.unit_cost' => 'required|numeric|min:0'
        ]);
        
        $item = $this->newItem;
        $item['total_cost'] = $item['quantity'] * $item['unit_cost'];
        
        $this->budgetItems[] = $item;
        $this->calculateTotal();
        
        // Reset form
        $this->newItem = [
            'category' => '',
            'item_name' => '',
            'description' => '',
            'quantity' => 1,
            'unit_cost' => 0,
            'unit_of_measure' => '',
            'justification' => '',
            'priority' => 3,
            'is_mandatory' => true
        ];
    }
    
    public function removeBudgetItem($index)
    {
        unset($this->budgetItems[$index]);
        $this->budgetItems = array_values($this->budgetItems);
        $this->calculateTotal();
    }
    
    public function calculateTotal()
    {
        $this->total_amount = collect($this->budgetItems)->sum('total_cost');
    }
    
    public function saveBudget()
    {
        $this->validate();
        
        if (empty($this->budgetItems)) {
            session()->flash('error', 'Please add at least one budget item.');
            return;
        }
        
        $budget = Budget::create([
            'title' => $this->title,
            'budget_code' => $this->generateBudgetCode(),
            'institution_id' => Auth::user()->currentInstitution->id,
            'created_by' => Auth::id(),
            'type' => $this->type,
            'description' => $this->description,
            'objectives' => json_encode(array_filter($this->objectives)),
            'total_amount' => $this->total_amount,
            'financial_year' => $this->financial_year,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority_level' => $this->priority_level,
            'is_recurring' => $this->is_recurring,
            'recurrence_pattern' => $this->recurrence_pattern,
            'remaining_amount' => $this->total_amount
        ]);
        
        // Create budget items
        foreach ($this->budgetItems as $item) {
            BudgetItem::create([
                'budget_id' => $budget->id,
                'category' => $item['category'],
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'total_cost' => $item['total_cost'],
                'unit_of_measure' => $item['unit_of_measure'],
                'justification' => $item['justification'],
                'priority' => $item['priority'],
                'is_mandatory' => $item['is_mandatory']
            ]);
        }
        
        session()->flash('message', 'Budget created successfully!');
        return redirect()->route('budgets.index');
    }
    
    private function generateBudgetCode()
    {
        $code=time();

        return $code;

        // $institution = Auth::user()->currentInstitution;
        // $prefix = strtoupper(substr($institution->code, 0, 3));
        // $year = substr($this->financial_year, -2);
        // $type = strtoupper(substr($this->type, 0, 3));
        // $number = str_pad(Budget::where('institution_id', $institution->id)
        //                   ->where('financial_year', $this->financial_year)
        //                   ->count() + 1, 3, '0', STR_PAD_LEFT);
        
        // return "{$prefix}-{$year}-{$type}-{$number}";
    }
    
    public function render()
    {
        return view('livewire.budget.budget-create');
    }
}