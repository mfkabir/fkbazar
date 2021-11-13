<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class CategoryComponent extends Component
{
    public $page_size;
    public $sorting;
    public $category_slug;
    public $category_id;
    use WithPagination;

    public function mount($category_slug)
    {
        $this->page_size = 12;
        $this->sorting = 'default';
        $this->category_slug = $category_slug;
    }

    public function store($product_id, $product_name, $product_price)
    {
        Cart::add($product_id, $product_name, 1, $product_price)->associate(Product::class);
        session()->flash('success_message', 'Iteam added to Cart');
        return redirect()->route('product.cart');
    }

    public function render()
    {   
        $category = Category::where('slug', $this->category_slug)->first();
        switch ($this->sorting) {
            case 'date':
                $products = Product::where('category_id', $category->id)->orderBy('created_at', 'DESC')->paginate($this->page_size);
                break;
            case 'price':
                $products = Product::where('category_id', $category->id)->orderBy('regular_price', 'ASC')->paginate($this->page_size);
                break;
            case 'price-desc':
                $products = Product::where('category_id', $category->id)->orderBy('regular_price', 'DESC')->paginate($this->page_size);
                break;
            default:
                $products = Product::where('category_id', $category->id)->paginate($this->page_size);
                break;
        }

        $categories = Category::all();

        return view('livewire.category-component', ['products' => $products, 'categories' => $categories, 'category_name' => $category->name])->layout('layouts.base');
    }
}
