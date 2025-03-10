<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class CreateClassModal extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $name;
    public $teacherId;
    public $description;
    public $image;
    public $selectedStudents = [];

    protected $rules = [
        'name' => 'required',
        'teacherId' => 'required',
        'image' => 'nullable|image|max:2048',
    ];

    public function nextStep()
    {
        $this->validate([
            $this->currentStep == 1 ? 'name' : 'teacherId' => 'required'
        ]);

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function render()
    {
        return view('livewire.create-class-modal', [
            'teachers' => \App\Models\User::where('role', 'teacher')->get()
        ]);
    }
}