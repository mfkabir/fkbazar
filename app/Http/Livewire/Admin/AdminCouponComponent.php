<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminCouponComponent extends Component
{
    public $code;
    public $type;
    public $value;
    public $cart_value;
    public $coupon_id;
    public $expire_date;
    protected $listeners = ['forcedCloseModal'];
    public $btn_text;

    public function mount($coupon_id = 0)
    {
        $this->coupon_id = $coupon_id;
        $coupon = Coupon::find($this->coupon_id);

        if(isset($coupon)){
            $this->code = $coupon->code;
            $this->type = $coupon->type;
            $this->value = $coupon->value;
            $this->cart_value = $coupon->cart_value;
            $this->expire_date = $coupon->expire_date;
        }else{
            $this->type = '';
        }

        $this->btn_text = "Submit";
    }
    
    protected $rules = [
        'code' => 'required|unique:coupons,id',
        'type' => 'required',
        'value' => 'required|numeric',
        'cart_value' => 'required|numeric',
        'expire_date' => 'required'
    ];

    public function updated($property_name)
    {
        $this->validateOnly($property_name);
    }

    public function addNew()
    {
        $this->img_flag = true;
        $this->dispatchBrowserEvent('show-form');
    }

    public function storeCoupon()
    {
        $valid_data = $this->validate();
        $coupon = new Coupon();
        $coupon->code = $valid_data['code'];
        $coupon->value = $valid_data['value'];
        $coupon->cart_value = $valid_data['cart_value'];
        $coupon->type = $this->type;
        $coupon->expire_date = $valid_data['expire_date'];
        // dd($valid_data['expire_date']);
        $coupon->save();
        session()->flash('message', 'Coupon hase benn added successfully.');
        $this->dispatchBrowserEvent('postUpdated');
    }

    public function openUpdateForm($coupon_id)
    {
        $this->coupon_id = $coupon_id;
        $coupon = Coupon::find($coupon_id);
        
        $this->code = $coupon->code;
        $this->value = $coupon->value;
        $this->cart_value = $coupon->cart_value;
        $this->type = $coupon->type;
        $this->expire_date = $coupon->expire_date;

        $this->btn_text = "Update";
        $this->dispatchBrowserEvent('show-form');
    }
    
    public function updateCoupon()
    {
        $coupon = Coupon::find($this->coupon_id);
        $valid_data = $this->validate();

        $coupon->code = $valid_data['code'];
        $coupon->value = $valid_data['value'];
        $coupon->cart_value = $valid_data['cart_value'];
        $coupon->type = $this->type;
        $coupon->expire_date = $valid_data['expire_date'];
        // dd($valid_data['expire_date']);
        $coupon->save();
        $this->dispatchBrowserEvent('postUpdated');
        session()->flash('message', 'Coupon hase been updated successfully.');
    }

    public function forcedCloseModal()
    {
        $this->btn_text = "Submit";
        $this->code = '';
        $this->value = '';
        $this->cart_value = '';
        $this->type = 0;
        $this->expire_date = Date('Y-m-d');
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        session()->flash('message', 'Coupon has been deleted!');
    }
    
    public function render()
    {
        $coupons = Coupon::all();
        return view('livewire.admin.admin-coupon-component', ['coupons' => $coupons])->layout('layouts.base');
    }
}
