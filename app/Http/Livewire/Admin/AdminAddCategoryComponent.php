<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminAddCategoryComponent extends Component
{
    public $category_slug;
    public $category_id;
    public $name;
    public $slug;

    public function mount($category_slug='')
    {
        $this->category_slug = $category_slug;
        $category = Category::where('slug', $category_slug)->first();
        if(isset($category)){
            $this->category_id = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
        }        
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    protected $rules = [
        'name' => 'required|min:4|max:30',
        'slug' => 'required|unique:categories'
    ];

    public function updated($property_name)
    {
        $this->validateOnly($property_name);
    }

    public function storeCategory()
    {
        $valid_data = $this->validate();
        $category = new Category();
        $category->name = $valid_data['name'];
        $category->slug = $this->slug;
        $category->save();
        session()->flash('message', 'Category has added successfully!');
    }

    public function updateCategory()
    {
        $category = Category::find($this->category_id);
        $valid_data = $this->validate();
        $category->name = $valid_data['name'];
        $category->slug = $this->slug;
        $category->save();
        session()->flash('message', 'Category has updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.admin-add-category-component')->layout('layouts.base');
    }
}
