<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Builder;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRole = '';
    public $selectedStatus = '';
    public $selectedInstitution = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // Form fields
    public $userId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $national_id = '';
    public $date_of_birth = '';
    public $gender = '';
    public $role = 'student';
    public $status = 'pending';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:255',
        'national_id' => 'nullable|string|unique:users,national_id',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|in:male,female',
        'role' => 'required|in:student,leader,supervisor,tra_officer',
        'status' => 'required|in:active,inactive,pending,suspended',
        'password' => 'required|min:8|confirmed',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedRole()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function createUser()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->national_id = $user->national_id;
        $this->date_of_birth = $user->date_of_birth?->format('Y-m-d');
        $this->gender = $user->gender;
        $this->role = $user->role;
        $this->status = $user->status;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        
        $this->showEditModal = true;
    }

    public function saveUser()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'national_id' => $this->national_id,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'role' => $this->role,
            'status' => $this->status,
        ];

        if ($this->password) {
            $userData['password'] = bcrypt($this->password);
        }

        if ($this->userId) {
            // Update existing user
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
            $this->rules['national_id'] = 'nullable|string|unique:users,national_id,' . $this->userId;
            $this->rules['password'] = 'nullable|min:8|confirmed';
            
            $this->validate();
            
            $user = User::findOrFail($this->userId);
            $user->update($userData);
        } else {
            // Create new user
            $user = User::create($userData);
        }

        // Sync roles
        if (!empty($this->selectedRoles)) {
            $user->roles()->sync($this->selectedRoles);
        }

        $this->closeModal();
        session()->flash('message', $this->userId ? 'User updated successfully!' : 'User created successfully!');
        $this->resetForm();
    }

    public function confirmDelete($userId)
    {
        $this->userId = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        User::findOrFail($this->userId)->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'User deleted successfully!');
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);
        session()->flash('message', 'User status updated successfully!');
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->national_id = '';
        $this->date_of_birth = '';
        $this->gender = '';
        $this->role = 'student';
        $this->status = 'pending';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedRole, function (Builder $query) {
                $query->where('role', $this->selectedRole);
            })
            ->when($this->selectedStatus, function (Builder $query) {
                $query->where('status', $this->selectedStatus);
            })
            ->with('roles')
            ->latest()
            ->paginate(15);

        $roles = Role::all();
        $institutions = Institution::where('status', 'active')->get();

        return view('livewire.admin.user-management', compact('users', 'roles', 'institutions'));
    }
}