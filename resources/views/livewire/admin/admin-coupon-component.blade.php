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
                            <div class="col-md-6">All Coupons</div>
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
                                    <th class="text-center">No.</th>
                                    <th>Coupon Code</th>
                                    <th>Coupon Type</th>
                                    <th class="text-center">Coupon Value</th>
                                    <th class="text-center">Cart Value</th>
                                    <th class="text-center">Expire Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $key=>$coupon)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ ucfirst(trans($coupon->type)) }}</td>
                                    @if($coupon->type=='fixed')
                                        <td class="text-center">${{ $coupon->value }}</td>
                                    @else
                                        <td class="text-center">{{ $coupon->value }}%</td>
                                    @endif                                    
                                    <td class="text-center">{{ $coupon->cart_value }}</td>                                    
                                    <td class="text-center">{{ $coupon->expire_date }}</td>                                    
                                    <td class="text-center">
                                        <a href="" wire:click.prevent="openUpdateForm({{ $coupon->id }})"><i class="fa fa-edit fa-2x"></i></a>
                                        <a href="" onclick="return confirm('Are you sure?')" wire:click.prevent="deleteCoupon({{ $coupon->id }})" style="margin-left: 5px;"><i class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    <h4 class="modal-title" id="myModalLabel">@if($coupon_id == 0) Add New @else Edit @endif Coupon</h4>
                </div>
                <form class="form-horizontal" enctype="multipart/formp-data">
                    @csrf                      
                    <div class="modal-body">                      
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="code">Coupon Code</label>
                            <div class="col-md-6">
                                <input type="text" name="code" id="code" class="form-control input-md" wire:model="code" value="{{ old('code') }}" />
                                @error('code')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="type">Coupon Type</label>
                            <div class="col-md-6">
                                <select name="type" id="type" class="form-control" wire:model="type">
                                    <option value="">Select</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="percent">Percent</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="value">Coupon Value</label>
                            <div class="col-md-6">
                                <input type="text" name="value" id="value" class="form-control input-md" wire:model="value" value="{{ old('value') }}" />
                                @error('value')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="cart_value">Cart Value</label>
                            <div class="col-md-6">
                                <input type="text" name="cart_value" id="cart_value" class="form-control input-md" wire:model="cart_value" value="{{ old('cart_value') }}" />
                                @error('cart_value')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="expire_date">Expire Date</label>
                            <div class="col-md-6">
                                <div class="input-group date" data-provider="datepicker">
                                    <input wire:model="expire_date" type="text" class="form-control input-md" placeholder="Select Date" autocomplete="off" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" onchange="this.dispatchEvent(new InputEvent('input'))" />
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                                
                                @error('expire_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                                           
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="@if($coupon_id == 0) storeCoupon() @else updateCoupon({{ $coupon_id }}) @endif">{{ $btn_text }}</button>
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
		});
	</script>
</div>

