<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class SearchComponent extends Component
{
    public $page_size;
    public $sorting;
    public $search;
    public $product_cat;
    public $product_cat_id;
    use WithPagination;

    public function mount()
    {
        $this->page_size = 12;
        $this->sorting = 'default';
        $this->fill(request()->only('search', 'product_cat', 'product_cat_id'));
    }

    public function store($product_id, $product_name, $product_price)
    {
        Cart::add($product_id, $product_name, 1, $product_price)->associate(Product::class);
        session()->flash('success_message', 'Iteam added to Cart');
        return redirect()->route('product.cart');
    }

    public function render()
    {   
        switch ($this->sorting) {
            case 'date':
                $products = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('created_at', 'DESC')->paginate($this->page_size);
                break;
            case 'price':
                $products = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('regular_price', 'ASC')->paginate($this->page_size);
                break;
            case 'price-desc':
                $products = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('regular_price', 'DESC')->paginate($this->page_size);
                break;
            default:
                $products = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->paginate($this->page_size);
                break;
        }

        $categories = Category::all();

        return view('livewire.search-component', ['products' => $products, 'categories' => $categories])->layout('layouts.base');
    }
}
