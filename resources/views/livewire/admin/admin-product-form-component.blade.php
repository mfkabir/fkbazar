<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                @if($product_id == 0) Add @else Edit @endif Product
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.products') }}" class="btn btn-success pull-right">All Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                        @endif
                        <form action="" class="form-horizontal" enctype="multipart/formp-data" wire:submit.prevent="@if($product_id == 0) storeProduct @else updateProduct @endif">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="name">Product Name</label>
                                <div class="col-md-4">
                                    <input type="text" name="name" id="name" class="form-control input-md" wire:model="name" wire:keyup="generateSlug" value="{{ old('name') }}" />
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="product_slug">Product Slug</label>
                                <div class="col-md-4">
                                    <input type="text" name="product_slug" id="product_slug" class="form-control input-md" wire:model="slug" value="{{ old('slut') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="short_description">Short Description</label>
                                <div class="col-md-4">
                                    <input type="text" name="short_description" id="short_description" class="form-control input-md" wire:model="short_description" value="{{ old('short_description') }}" />
                                    @error('short_description')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="description">Description</label>
                                <div class="col-md-4">
                                    <input type="text" name="description" id="description" class="form-control input-md" wire:model="description" value="{{ old('description') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="regular_price">Regular Price</label>
                                <div class="col-md-4">
                                    <input type="text" name="regular_price" id="regular_price" class="form-control input-md" wire:model="regular_price" value="{{ old('regular_price') }}" />
                                    @error('regular_price')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="sale_price">Sale Price</label>
                                <div class="col-md-4">
                                    <input type="text" name="sale_price" id="sale_price" class="form-control input-md" wire:model="sale_price" value="{{ old('sale_price') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="SKU">SKU</label>
                                <div class="col-md-4">
                                    <input type="text" name="SKU" id="SKU" class="form-control input-md" wire:model="SKU" value="{{ old('SKU') }}" />
                                    @error('SKU')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="stock_status">Stock Status</label>
                                <div class="col-md-4">
                                    <select name="stock_status" id="stock_status" class="form-control" wire:model="stock_status">
                                        <option value="instock">In Stock</option>
                                        <option value="outofstock">Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="product_slug">Featured</label>
                                <div class="col-md-4">
                                    <select name="featured" id="featured" class="form-control" wire:model="featured">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="quantity">Quantity</label>
                                <div class="col-md-4">
                                    <input type="number" name="quantity" id="quantity" class="form-control input-md" wire:model="quantity" value="{{ old('quantity') }}" />
                                    @error('quantity')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="image">Product Image</label>
                                <div class="col-md-4">
                                    <input type="file" name="image" id="image" class="input-file" @if($product_id == 0) wire:model="image" @else wire:model="new_image" @endif required />
                                    @if($new_image)
                                        <img src="{{ $new_image->temporaryUrl() }}" alt="" width="120" />
                                    @elseif($image)
                                        <img src="@if($product_id == 0) {{ $image->temporaryUrl() }} @else {{ asset('assets/images/products') }}/{{ $image }}.jpg @endif" alt="" width="120" />                                        
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="product_category">Product Category</label>
                                <div class="col-md-4">
                                    <select name="product_category" id="product_category" class="form-control" wire:model="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">@if($product_id == 0) Submit @else Update @endif</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('custom-scripts')
        <script>
            $(function(){
                tinymce.init({
                    selector: '#short_description',
                    setup: function(editor){
                        editor.on('Change', function(e){
                            tinyMCE.triggerSave();
                            var sd_data = $('#short_description').val();
                            @this.set('short_description', sd_data);
                        });
                    }
                });

                tinymce.init({
                    selector: '#description',
                    setup: function(editor){
                        editor.on('Change', function(e){
                            tinyMCE.triggerSave();
                            var sd_data = $('#description').val();
                            @this.set('description', sd_data);
                        });
                    }
                });
            });
        </script>        
    @endpush
</div>
