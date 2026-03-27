<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $listeners = ['userSaved' => '$refresh'];

    public function delete($id)
    {
        // Prevent deleting the currently authenticated user
        if (Auth::id() == $id) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.']);
            return;
        }

        $user = User::findOrFail($id);
        $user->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Akun pengguna berhasil dihapus.']);
    }

    public function render()
    {
        $users = User::with('roles')->orderBy('name')->paginate(10);
        return view('livewire.user.index', compact('users'));
    }
}
