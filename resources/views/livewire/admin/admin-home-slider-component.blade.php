<div>
    <style>
        nav svg {
            height: 20px;
        }

        nav .hidden {
            display: block !important;
        }

    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">All Home Sliders</div>
                            <div class="col-md-6">
                                <button wire:click="$emitUp('forcedCloseModal')" type="button" class="btn btn-success pull-right" data-toggle="modal" wire:click.prevent="addNew()">Add New</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                        @endif
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Price</th>
                                    <th>Link</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sliders as $slider)
                                <tr>
                                    <td>{{ $slider->title }}</td>
                                    <td>{{ $slider->subtitle }}</td>
                                    <td>{{ $slider->price }}</td>
                                    <td>{{ $slider->link }}</td>
                                    <td><img src="{{ asset('assets/images/sliders') }}/{{ $slider->image }}" alt="{{ $slider->title }}" style="width: 100px;"></td>
                                    <td>{{ ($slider->status == 1) ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="" wire:click.prevent="openUpdateForm({{ $slider->id }})"><i class="fa fa-edit fa-2x"></i></a>
                                        <a href="" onclick="return confirm('Are you sure?')" wire:click.prevent="deleteSlider({{ $slider->id }})" style="margin-left: 5px;"><i class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $sliders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add or Edit Modal -->
    <div wire:ignore.self class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">@if($slider_id == 0) Add New @else Edit @endif Slider</h4>
                </div>
                <form class="form-horizontal" enctype="multipart/formp-data" wire:submit.prevent="@if($slider_id == 0) storeSlider @else updateSlider @endif">
                    @csrf                      
                    <div class="modal-body">                      
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title">Title</label>
                            <div class="col-md-6">
                                <input type="text" name="title" id="title" class="form-control input-md" wire:model="title" value="{{ old('title') }}" />
                                @error('title')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="subtitle">Subtitle</label>
                            <div class="col-md-6">
                                <input type="text" name="subtitle" id="subtitle" class="form-control input-md" wire:model="subtitle" value="{{ old('subtitle') }}" />
                                @error('subtitle')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="price">Price</label>
                            <div class="col-md-6">
                                <input type="text" name="price" id="price" class="form-control input-md" wire:model="price" value="{{ old('price') }}" />
                                @error('price')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="link">Link</label>
                            <div class="col-md-6">
                                <input type="text" name="link" id="link" class="form-control input-md" wire:model="link" value="{{ old('link') }}" />
                                @error('link')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="status">Slider Status</label>
                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control" wire:model="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="image">Slider Image</label>
                            <div class="col-md-6">
                                <input type="file" name="image" id="image" class="input-file" @if($slider_id == 0 && $img_flag) wire:model="image" @else wire:model="new_image" @endif required />
                                @if($new_image)
                                    <img class="thumb-img" src="{{ $new_image->temporaryUrl() }}" alt="" width="120" />
                                @elseif($image)
                                    <img class="thumb-img" src="@if($slider_id == 0 && $img_flag) {{ $image->temporaryUrl() }} @else {{ asset('assets/images/sliders') }}/{{ $image }} @endif" alt="" width="120" />
                                @endif
                            </div>
                        </div>                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="@if($slider_id == 0) storeSlider() @else updateSlider({{ $slider_id }}) @endif">{{ $btn_text }}</button>
                    </div>
                </form>            
            </div>
        </div>
    </div>

    <script>
		window.addEventListener('show-form', e => {
			$('#formModal').modal('show');
		});

		window.addEventListener('postUpdated', e => {
			$('#formModal').modal('hide');
		})
	</script>
</div>


