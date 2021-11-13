<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class AdminProductFormComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $regular_price;
    public $sale_price;
    public $SKU;
    public $stock_status;
    public $featured;
    public $quantity;
    public $image;
    public $new_image;
    public $category_id;
    public $product_id;

    public function mount($product_id = 0)
    {
        $this->product_id = $product_id;
        $product = Product::find($this->product_id);
        // dd($product);
        if(isset($product)){
            $this->name = $product->name;
            $this->slug = $product->slug;
            $this->short_description = $product->short_description;
            $this->description = $product->description;
            $this->regular_price = $product->regular_price;
            $this->sale_price = $product->sale_price;
            $this->SKU = $product->SKU;
            $this->stock_status = $product->stock_status;
            $this->featured = $product->featured;
            $this->quantity = $product->quantity;
            $this->image = $product->image;
            $this->category_id = $product->category_id;
        }else{
            $this->stock_status = 'instock';
            $this->featured = 0;
        }        
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name, '-');
    }

    protected $rules = [
        'name' => 'required|min:10',
        'short_description' => 'required|max:255',
        'regular_price' => 'required|numeric',
        'SKU' => 'required',
        'quantity' => 'required',
        // 'image' => 'required|image|max:1024',
        // 'new_image' => 'required|image|max:1024',
        'category_id' => 'required'
    ];

    public function updated($property_name)
    {
        $this->validateOnly($property_name);
    }

    public function storeProduct()
    {
        $valid_data = $this->validate();
        // dd($valid_data);
        
        $product = new Product();
        $product->name = $valid_data['name'];
        $product->slug = $this->slug;
        $product->short_description = $valid_data['short_description'];
        $product->description = $this->description;
        $product->regular_price = $valid_data['regular_price'];
        $product->sale_price = $this->sale_price;
        $product->SKU = $valid_data['SKU'];
        $product->stock_status = $this->stock_status;
        $product->featured = $this->featured;
        $product->quantity = $valid_data['quantity'];
        $image_name = Carbon::now()->timestamp.'.'.$this->image->extension();
        $this->image->storeAs('products', $image_name);
        $product->image = Carbon::now()->timestamp;
        $product->category_id = $valid_data['category_id'];
        $product->save();
        session()->flash('message', 'Product hase benn created successfully.');
    }

    public function updateProduct()
    {
        // dd('ddd');
        $product = Product::find($this->product_id);
        // dd($product);
        $valid_data = $this->validate();
        $product->name = $valid_data['name'];
        $product->slug = $this->slug;
        $product->short_description = $valid_data['short_description'];
        $product->description = $this->description;
        $product->regular_price = $valid_data['regular_price'];
        $product->sale_price = $this->sale_price;
        $product->SKU = $valid_data['SKU'];
        $product->stock_status = $this->stock_status;
        $product->featured = $this->featured;
        $product->quantity = $valid_data['quantity'];

        if($this->new_image){
            $image_name = Carbon::now()->timestamp.'.'.$this->new_image->extension();
            $this->new_image->storeAs('products', $image_name);
            $product->image = Carbon::now()->timestamp;
        }

        $product->category_id = $valid_data['category_id'];
        $product->save();
        session()->flash('message', 'Product hase benn updated successfully.');
        // return redirect()->route('admin.products');
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-product-form-component', ['categories' => $categories])->layout('layouts.base');
    }
}
