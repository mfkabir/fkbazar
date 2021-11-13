<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class CartComponent extends Component
{
    public $have_coupon_code;
    public $coupon_code;
    public $discount;
    public $subtotal_after_discount;
    public $tax_after_discount;
    public $total_after_discount;
    
    public function increaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        $this->emitTo('cart-count-component', 'refreshComponent');
    }

    public function decreaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        $this->emitTo('cart-count-component', 'refreshComponent');
    }

    public function removeItem($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('success_message', 'Item removed from the Cart');
    }

    public function removeAll()
    {
        Cart::instance('cart')->destroy();
        $this->emitTo('cart-count-component', 'refreshComponent');
    }

    public function moveItemToSaveForLater($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        Cart::instance('cart')->remove($rowId);
        Cart::instance('saveForLater')->add($item->id, $item->name, 1, $item->price)->associate(Product::class);
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('success_message', 'Item hase been saved for later');
    }

    public function moveToCart($rowId)
    {
        $item = Cart::instance('saveForLater')->get($rowId);
        Cart::instance('saveForLater')->remove($rowId);
        Cart::instance('cart')->add($item->id, $item->name, 1, $item->price)->associate(Product::class);
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('s_success_message', 'Item hase been re-added to Cart');
    }

    public function removeFromSaved($rowId)
    {
        Cart::instance('saveForLater')->remove($rowId);
        session()->flash('s_success_message', 'Item hase been removed from Saved');
    }

    public function applyCouponCode()
    {
        // dd(Cart::instance('cart')->subtotal());
        $coupon = Coupon::where('code', $this->coupon_code)->where('cart_value', '<=', Cart::instance('cart')->subtotal())->where('expire_date', '>=', Carbon::today())->first();
        if(!$coupon){
            session()->flash('coupon_msg', 'Coupon code is invalid!');
            return;
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value,
        ]);
    }

    public function calculateDiscount()
    {
        if(session()->has('coupon')){
            if(session()->get('coupon')['type'] == 'fixed'){
                $this->discount = session()->get('coupon')['value'];
            }else{
                $this->discount = (Cart::instance('cart')->subtotal() * session()->get('coupon')['value'])/100;
            }

            $this->subtotal_after_discount = Cart::instance('cart')->subtotal() - $this->discount;
            $this->tax_after_discount = ($this->subtotal_after_discount * config('cart.tax'))/100;
            $this->total_after_discount = $this->subtotal_after_discount + $this->tax_after_discount;
        }
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
    }

    public function checkout()
    {
        if (Auth::check()) {
            return redirect()->route('checkout');
        } else {
            return redirect()->route('login');
        }        
    }

    public function setAmountForCheckout()
    {
        if(!Cart::instance('cart')->count() > 0){
            session()->forget('checkout');
            return;
        }

        if (session()->has('coupon')) {
            session()->put('checkout', [
                'discount' => $this->discount,
                'subtotal' => $this->subtotal_after_discount,
                'tax' => $this->tax_after_discount,
                'total' => $this->total_after_discount,
            ]);
        } else {
            session()->put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }        
    }
    
    public function render()
    {
        if(session()->has('coupon')){
            if(Cart::instance('cart')->subtotal() < session()->get('coupon')['cart_value']){
                session()->forget('coupon');
            }else{
                $this->calculateDiscount();
            }
        }
        $this->setAmountForCheckout();
        return view('livewire.cart-component')->layout('layouts.base');
    }
}
