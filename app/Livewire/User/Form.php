<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role;


    public $isOpen = false;

    protected $listeners = ['openUserForm' => 'open', 'closeUserForm' => 'close'];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
        ];

        // Password is required on create, optional on update
        if (!$this->userId) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    public function open($id = null)
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'role']);

        if ($id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles->first()?->name;
        }

        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['userId', 'name', 'email', 'password', 'role']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);

        if ($this->role) {
            $user->syncRoles($this->role);
        }

        $this->dispatch('userSaved');
        $this->dispatch('notify', ['type' => 'success', 'message' => $this->userId ? 'Akun berhasil diperbarui.' : 'Akun baru berhasil ditambahkan.']);
        
        $this->close();
    }

    public function render()
    {
        return view('livewire.user.form', [
            'roles' => \Spatie\Permission\Models\Role::all()
        ]);
    }
}
