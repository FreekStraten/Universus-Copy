<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Carbon\Carbon;
use Livewire\Component;

class ListCategorie extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories = Category::whereNull('archived_at')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function boot()
    {
        $this->categories = Category::whereNull('archived_at')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->archived_at = Carbon::now();
        $category->save();
        return redirect()->to('/categoryList');
    }

    public function render()
    {
        return view('livewire.list-categorie', [
            'categories' => $this->categories,
        ]);
    }
}
