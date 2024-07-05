<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $institution;

    public function mount()
    {
        $this->institution = Auth::user()->institution;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
