<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ConfirmationModal extends Component
{
    public $title;
    public $message;
    public $confirmText;
    public $action;

    protected $listeners = ['openConfirmation'];

    public function openConfirmation($title, $message, $confirmText, $action)
    {
        $this->title = $title;
        $this->message = $message;
        $this->confirmText = $confirmText;
        $this->action = $action;
        $this->dispatch('open-confirm');
    }

    public function confirm()
    {
        $this->dispatch($this->action);
        $this->dispatch('close-confirm');
    }

    public function cancel()
    {
        $this->dispatch('close-confirm');
    }

    public function render()
    {
        return view('livewire.components.confirmation-modal');
    }
}
