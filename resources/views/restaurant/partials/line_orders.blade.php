@forelse($line_orders as $order)
	<div class="col-md-3 col-xs-6 line_order_div">
		<div class="small-box bg-gray">
            <div class="inner">
            	<h4 class="text-center">#{{$order->invoice_no}}</h4>
            	<table class="table no-margin no-border table-slim">
            		<tr><th>@lang('restaurant.placed_at')</th><td>{{@format_date($order->created_at)}} {{ @format_time($order->created_at)}}</td></tr>
            		<tr><th>@lang('restaurant.order_status')</th><td><span class="label @if($order->res_order_status == 'cooked' ) bg-red @elseif($order->res_order_status == 'served') bg-green @else bg-light-blue @endif">@lang('restaurant.order_statuses.' . $order->res_line_order_status) </span></td></tr>
            		<tr><th>@lang('contact.customer')</th><td>{{$order->customer_name}}</td></tr>
            		<tr><th>@lang('restaurant.table')</th><td>{{$order->table_name}}</td></tr>
                        <tr><th>@lang('restaurant.service_staff')</th><td>{{$order->service_staff_name ?? ''}}</td></tr>
            		<tr><th>@lang('sale.location')</th><td>{{$order->business_location}}</td></tr>
                        <tr>
                              <th>
                                    @lang('sale.product')
                              </th>
                              <td>
                                    {{$order->product_name}}
                                    @if($order->product_type == 'variable')
                                           - {{$order->product_variation_name}} - {{$order->variation_name}}
                                    @endif
                                    @if(!empty($order->modifiers) && count($order->modifiers) > 0)
                                          @foreach($order->modifiers as $key => $modifier)
                                                <br>{{$modifier->product->name ?? ''}}
                                                @if(!empty($modifier->variations))
                                                      - {{$modifier->variations->name ?? ''}}
                                                      @if(!empty($modifier->variations->sub_sku))
                                                            ({{$modifier->variations->sub_sku ?? ''}})
                                                      @endif
                                                @endif
                                          @endforeach
                                    @endif
                              </td>
                        </tr>
                        <tr><th>@lang('lang_v1.quantity')</th><td>{{$order->quantity}}{{$order->unit}}</td></tr>
                        <tr><th>@lang('lang_v1.description')</th><td> {!! nl2br($order->sell_line_note ?? '') !!}</td></tr>
            	</table>
            </div>
            @if($orders_for == 'kitchen')
            	<a href="#" class="btn btn-flat small-box-footer bg-yellow mark_as_cooked_btn" data-href="{{action([\App\Http\Controllers\Restaurant\KitchenController::class, 'markAsCooked'], [$order->id])}}"><i class="fa fa-check-square-o"></i> @lang('restaurant.mark_as_cooked')</a>
            @elseif($orders_for == 'waiter' && $order->res_order_status != 'served')
            	<a href="{{action([\App\Http\Controllers\Restaurant\OrderController::class, 'markLineOrderAsServed'], [$order->id])}}" class="btn btn-flat small-box-footer bg-yellow mark_line_order_as_served"><i class="fa fa-check-square-o"></i> @lang('restaurant.mark_as_served')</a>
            @else
            	<div class="small-box-footer bg-gray">&nbsp;</div>
            @endif
            <div class="small-box-footer bg-green print_line_order cursor-pointer" data-id="{{$order->id}}">
                  @lang('messages.print')
            </div>
         </div>
	</div>
	@if($loop->iteration % 4 == 0)
		<div class="hidden-xs">
			<div class="clearfix"></div>
		</div>
	@endif
	@if($loop->iteration % 2 == 0)
		<div class="visible-xs">
			<div class="clearfix"></div>
		</div>
	@endif
@empty
<div class="col-md-12" style="margin-bottom:40px;">
	<h4 class="text-center">@lang('restaurant.no_orders_found')</h4>
</div>
@endforelse
