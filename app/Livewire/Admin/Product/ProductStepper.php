<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;

class ProductStepper extends Component
{
    public $steps = [
        1 => ['name' => 'Basic Info', 'description' => 'Product details'],
        2 => ['name' => 'Pricing', 'description' => 'Set prices'],
        // 3 => ['name' => 'Images', 'description' => 'Upload photos'],
        3 => ['name' => 'Variants', 'description' => 'Add variations'],
        4 => ['name' => 'SEO & Review', 'description' => 'Final details'],
    ];

    public $currentStep = 1;
    public $completedSteps = [];

    public function mount($currentStep = 1, $completedSteps = [])
    {
        $this->currentStep = $currentStep;
        $this->completedSteps = $completedSteps;
    }

    public function goToStep($step)
    {
        if (in_array($step - 1, $this->completedSteps) || $step === 1) {
            $this->currentStep = $step;
            $this->dispatch('stepChanged', $step);
        }
    }

    public function nextStep()
    {
        if ($this->currentStep < count($this->steps)) {
            $this->completedSteps[] = $this->currentStep;
            $this->currentStep++;
            $this->dispatch('stepChanged', $this->currentStep);
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('stepChanged', $this->currentStep);
        }
    }

    public function markStepComplete($step)
    {
        if (!in_array($step, $this->completedSteps)) {
            $this->completedSteps[] = $step;
        }
    }

    public function render()
    {
        return view('livewire.admin.product.product-stepper');
    }
}
