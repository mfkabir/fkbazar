<?php

namespace App\Http\Livewire\Admin;

use App\Models\HomeSlider;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class AdminHomeSliderComponent extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $title;
    public $subtitle;
    public $price;
    public $link;
    public $image;
    public $new_image;
    public $status;
    public $slider_id;
    public $img_flag;
    protected $listeners = [ 'forcedCloseModal'];
    public $btn_text;

    public function mount($slider_id = 0)
    {
        $this->slider_id = $slider_id;
        $slider = HomeSlider::find($this->slider_id);
        if(isset($slider)){
            $this->title = $slider->title;
            $this->subtitle = $slider->subtitle;
            $this->price = $slider->price;
            $this->link = $slider->link;
            $this->image = $slider->image;
            $this->status = $slider->status;
        }else{
            $this->status = 0;
        }

        $this->img_flag = false;
        $this->btn_text = "Submit";
    }

    protected $rules = [
        'title' => 'required|min:10',
        'subtitle' => 'required|min:10',
        'price' => 'required|numeric',
        'link' => 'required'
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

    public function storeSlider()
    {
        $valid_data = $this->validate();
        $slider = new HomeSlider();
        $slider->title = $valid_data['title'];
        $slider->subtitle = $valid_data['subtitle'];
        $slider->price = $valid_data['price'];
        $slider->link = $valid_data['link'];
        $slider->status = $this->status;
        $image_name = Carbon::now()->timestamp.'.'.$this->image->extension();
        $this->image->storeAs('sliders', $image_name);
        $slider->image = $image_name;
        $slider->save();
        session()->flash('message', 'Slider hase benn added successfully.');
        $this->img_flag = false;
        $this->dispatchBrowserEvent('postUpdated');
    }

    public function openUpdateForm($slider_id)
    {
        $this->slider_id = $slider_id;
        $slider = HomeSlider::find($slider_id);
        
        $this->title = $slider->title;
        $this->subtitle = $slider->subtitle;
        $this->price = $slider->price;
        $this->link = $slider->link;
        $this->image = $slider->image;
        $this->status = $slider->status;

        $this->img_flag = true;
        $this->btn_text = "Update";
        $this->dispatchBrowserEvent('show-form');
    }
    
    public function updateSlider()
    {
        $slider = HomeSlider::find($this->slider_id);
        $valid_data = $this->validate();

        $slider->title = $valid_data['title'];
        $slider->subtitle = $valid_data['subtitle'];
        $slider->price = $valid_data['price'];
        $slider->link = $valid_data['link'];
        $slider->status = $this->status;

        if($this->new_image){
            if(File::exists('assets/images/sliders/'.$slider->image)){
                File::delete('assets/images/sliders/'.$slider->image);
            }

            $image_name = Carbon::now()->timestamp.'.'.$this->new_image->extension();
            $this->new_image->storeAs('sliders', $image_name);
            $slider->image = $image_name;            
        }

        $slider->save();
        $this->dispatchBrowserEvent('postUpdated');
        session()->flash('message', 'Slider hase benn updated successfully.');
        $this->img_flag = false;
        // $this->btn_text = "Submit";
    }

    public function forcedCloseModal()
    {
        $this->btn_text = "Submit";
        $this->title = '';
        $this->subtitle = '';
        $this->price = 0;
        $this->link = '';
        $this->image = '';
        $this->new_image = '';
        $this->status = 0;
    }

    public function deleteSlider($id)
    {
        $slider = HomeSlider::find($id);
        $slider->delete();
        session()->flash('message', 'Slider has been deleted!');
    }
    
    public function render()
    {
        $sliders = HomeSlider::paginate(10);
        return view('livewire.admin.admin-home-slider-component', ['sliders' => $sliders])->layout('layouts.base');
    }
}
