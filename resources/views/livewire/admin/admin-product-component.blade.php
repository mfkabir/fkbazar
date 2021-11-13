<div>
    <style>
        nav svg{
            height: 20px;
        }
        nav .hidden{
            display: block !important;
        }
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">All Products</div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.addproduct') }}" class="btn btn-success pull-right">Add New</a>
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
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Short Description</th>
                                    <th>Description</th>
                                    <th>Regular Price</th>
                                    <th>Sale Price</th>
                                    <th>SKU</th>
                                    <th>Stock Status</th>
                                    <th>Featured</th>
                                    <th>Quantity</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td><img src="{{ asset('assets/images/products') }}/{{ $product->image }}.jpg" alt="{{ $product->name }}" style="width: 100px;"></td>
                                        <td>{{ \Illuminate\Support\Str::limit($product->short_description, 80, $end='...') }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($product->description, 80, $end='...') }}</td>
                                        <td>{{ $product->regular_price }}</td>
                                        <td>{{ $product->sale_price }}</td>
                                        <td>{{ $product->SKU }}</td>
                                        <td>{{ $product->stock_status }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @if($product->featured == 1) checked @endif disabled>
                                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                            </div>
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.editproduct', ['product_id' => $product->id]) }}"><i class="fa fa-edit fa-2x"></i></a>
                                            <a href="" onclick="return confirm('Are you sure?')" wire:click.prevent="deleteProduct({{ $product->id }})" style="margin-left: 5px;"><i class="fa fa-times fa-2x text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
