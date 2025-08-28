<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ToggleButton extends Component
{
    public $checked = false;
    public $label = '';
    public $id = '';
    public $wireModel = '';
    public $size = 'md'; // sm, md, lg
    public $type = 'default'; // default, success, warning, danger
    
    public function mount($checked = false, $label = '', $id = '', $wireModel = '', $size = 'md', $type = 'default')
    {
        $this->checked = $checked;
        $this->label = $label;
        $this->id = $id ?: 'toggle_' . uniqid();
        $this->wireModel = $wireModel;
        $this->size = $size;
        $this->type = $type;
    }

    public function toggle()
    {
        $this->checked = !$this->checked;
        if ($this->wireModel) {
            $this->dispatch('toggle-changed', [
                'model' => $this->wireModel,
                'value' => $this->checked
            ]);
        }
    }

    public function render()
    {
        return view('livewire.components.toggle-button');
    }
}
