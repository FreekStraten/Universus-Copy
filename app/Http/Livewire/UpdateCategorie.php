<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class UpdateCategorie extends Component
{
    public $category;
    private $categoryId;
    public $name;
    public $description;

    protected $rules = [
        'name' => ['required', 'regex:/^[\w\s]+$/'],
        'description' => ['required', 'regex:/^[\w\s]+$/']
    ];
    protected $messages = [
        'name.required' => 'The name field is required.',
        'name.alpha' => 'The name is only allowed in alphabetical characters.',
        'description.required' => 'The description field is required.',
        'description.alpha' => 'The description is only allowed in alphabetical characters.'
    ];

    public function validated()
    {
        if ($this->hasErrors()) {
            $this->addError('name', $this->errorMessage('name'));
            $this->addError('description', $this->errorMessage('description'));
        }
    }

    private function errorMessage($field)
    {
        return implode(' ', $this->getErrorMessagesFor($field));
    }

    public function mount($id)
    {
        $this->categoryId = $id;
        $this->category = Category::find($id);
        $this->name = $this->category->name;
        $this->description = $this->category->description;

    }

    public function editCategory($id)
    {
        $this->validate();
        $this->category = Category::find($id);
        $this->category->name = $this->name;
        $this->category->description = $this->description;
        $this->category->save();
        return redirect()->to('/categoryList');

    }

    public function render()
    {
        return view('livewire.update-categorie');
    }
}
