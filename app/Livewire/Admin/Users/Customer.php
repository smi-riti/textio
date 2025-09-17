<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Customer extends Component
{

    public $users;


    public function mount(){
        $this->users=User::select('id','name','email','created_at')->get();
    }

     public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->dispatch('success', ['message' => 'Customer deleted successfully']);
    }


    #[Layout('components.layouts.admin')]
    public function render()
    {

        return view('livewire.admin.users.customer');
    }
}
