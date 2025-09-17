<?php

namespace App\Livewire\Public\Page;

use App\Models\Enquiry;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ContactPage extends Component
{
  
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('nullable|email|max:255')]
    public $email = '';
    
    #[Rule('required|string|max:20')]
    public $phone = '';
    
    #[Rule('nullable|string|max:255')]
    public $subject = '';
    
    #[Rule('required|string|max:1000')]
    public $message = '';

    public $successMessage = '';

    public function submitEnquiry()
    {
        $this->validate();

        Enquiry::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->successMessage = 'Thank you for your enquiry! We will get back to you soon.';
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);

        // Hide success message after 5 seconds
        $this->dispatch('hide-success-message');
    }

   

    public function render()
    {
        return view('livewire.public.page.contact-page');
    }
}
