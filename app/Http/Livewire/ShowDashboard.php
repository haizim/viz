<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowDashboard extends Component
{
    public $components = [];

    public function render()
    {
        return view('livewire.show-dashboard');
    }

    public function mount($components = null)
    {
        if (isset($components)) {
            $componentsUse = $components ? json_decode($components, true) : $this->components;
            $this->components = $componentsUse;
        }
    }
}
