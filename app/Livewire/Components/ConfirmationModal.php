<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ConfirmationModal extends Component
{
    public $title;
    public $message;
    public $confirmText;
    public $action;
    public $data = [];

    protected $listeners = ['openConfirmation'];

    public function openConfirmation($title, $message, $confirmText, $action, $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->confirmText = $confirmText;
        $this->action = $action;
        $this->data = $data;
        $this->dispatch('open-confirm');
    }

    public function confirm()
    {
        $this->dispatch($this->action, $this->data);
        $this->dispatch('close-confirm');
        $this->reset();
    }

    public function cancel()
    {
        $this->dispatch('close-confirm');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.components.confirmation-modal');
    }
}
