<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\HomeSlider;
use App\Models\Product;
use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        
        $sliders = HomeSlider::where('status', 1)->get();
        $latest_products = Product::latest('created_at')->take(8)->get();
        $categories = Category::select('id','name')->get();

        $tab_arr = [];
        for($i=0;$i<sizeof($categories);$i++){          
            $products = Product::where('category_id', $categories[$i]->id)->take(5)->get();
            if(sizeof($products) >= 4){
                $tab_arr[] = array(
                    'title' => $categories[$i]->name,
                    'content' => $products
                );
            }
        }

        $on_sale_products = Product::where('sale_price', '>', 0)->inRandomOrder()->get()->take(8);

        return view('livewire.home-component', ['sliders' => $sliders, 'latest_products' => $latest_products, 'tab_arr' => $tab_arr, 'on_sale_products' => $on_sale_products])->layout('layouts.base');
    }
}
