@php
    $enabled_modules = app(\App\Utils\TransactionUtil::class)->allModulesEnabled();
@endphp

<style>
.gallery-wrap,
#gallery {
  overflow: hidden;
}

        
#filters {
  margin: 1%;
  padding: 0;
  list-style: none;
  overflow: hidden;
}

#filters li {
  float: left;
  color:black;
}
.text p{margin:0; text-align:center;}
#filters li span {
  display: block;
  padding: 5px 20px;
  text-decoration: none;
  color: #666;
  cursor: pointer;
  text-transform: capitalize;
  transition: all ease-in-out 0.25s;
   border-bottom:2px solid transparent;
}

#filters li:hover span {
  color: #000;
}

#filters li span.active {
  border-bottom:2px solid #604be8;
  color: #604be8;
  font-weight: 700;
}
#product_category_div{
	margin-left:16px;
}
    </style>

	
<div class="row" id="featured_products_box" style="display: none;">
@if(!empty($featured_products))
	@include('sale_pos.partials.featured_products')
@endif
</div>



<div class="row">
@if(!in_array('kitchen', $enabled_modules))
<div class="col-md-6 filter_cust" style="margin-left:16px">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default bg-white btn-flat form-control" data-toggle="modal"
                        data-target="#configure_search_modal" title="{{ __('lang_v1.configure_product_search') }}"><i
                            class="fas fa-search-plus"></i></button>
                </div>
                {!! Form::text('search_product', null, [
                    'class' => 'form-control mousetrap',
                    'id' => 'search_product',
                    'placeholder' => __('lang_v1.search_product_placeholder'),
                    'disabled' => is_null($default_location) ? true : false,
                    'autofocus' => is_null($default_location) ? false : true,
                ]) !!}
                <span class="input-group-btn">

                    <!-- Show button for weighing scale modal -->
                    @if (isset($pos_settings['enable_weighing_scale']) && $pos_settings['enable_weighing_scale'] == 1)
                        <button type="button" class="btn btn-default bg-white btn-flat form-control" id="weighing_scale_btn"
                            data-toggle="modal" data-target="#weighing_scale_modal" title="@lang('lang_v1.weighing_scale')"><i
                                class="fa fa-digital-tachograph text-primary fa-lg"></i></button>
                    @endif


                    <button type="button" class="btn btn-default bg-white btn-flat pos_add_quick_product form-control"
                        data-href="{{ action([\App\Http\Controllers\ProductController::class, 'quickAdd']) }}"
                        data-container=".quick_add_product_modal"><i
                            class="fa fa-plus-circle text-primary fa-lg"></i></button>
                </span>
            </div>
        </div>
    </div>
	@endif
@if(in_array('kitchen', $enabled_modules))
	@if(!empty($categories))
		<div class="col-md-4 ml-10" id="product_category_div">
			<select class="select2 form-control" id="product_category" style="width:100% !important">

				<option value="all">@lang('lang_v1.all_category')</option>

				@foreach($categories as $category)
					<option value="{{$category['id']}}">{{$category['name']}}</option>
				@endforeach

				@foreach($categories as $category)
					@if(!empty($category['sub_categories']))
						<optgroup label="{{$category['name']}}">
							@foreach($category['sub_categories'] as $sc)
								<i class="fa fa-minus"></i> <option value="{{$sc['id']}}">{{$sc['name']}}</option>
							@endforeach
						</optgroup>
					@endif
				@endforeach
			</select>
		</div>
	@endif
	@endif

	@if(!empty($brands))
		<div class="col-sm-4 " id="product_brand_div">
			{!! Form::select('size', $brands, null, ['id' => 'product_brand', 'class' => 'select2 form-control', 'name' => null, 'style' => 'width:100% !important']) !!}
		</div>
	@endif

	<!-- used in repair : filter for service/product -->
	<div class="col-md-6 hide" id="product_service_div">
		{!! Form::select('is_enabled_stock', ['' => __('messages.all'), 'product' => __('sale.product'), 'service' => __('lang_v1.service')], null, ['id' => 'is_enabled_stock', 'class' => 'select2 form-control', 'name' => null, 'style' => 'width:100% !important']) !!}
	</div>

	<div class="col-sm-4 @if(empty($featured_products)) hide @endif" id="feature_product_div">
		<button type="button" class="btn btn-primary btn-flat" id="show_featured_products">@lang('lang_v1.featured_products')</button>
	</div>
</div>

<br>

@if(!in_array('kitchen', $enabled_modules))
<div class="gallery-wrap ml-2">
<ul id="filters" class="clearfix">
<li><span class="filter active" data-filter=".print, .strategy, .logo, .web"><a href="#"  class="category_tab" data-val="">All</a></span></li>
@foreach($categories as $category)
            <li><span class="filter" data-filter="{{$category['id']}}"><a href="#"  class="category_tab" data-val="{{$category['id']}}">{{$category['name']}}</a></span></li>
            
			@endforeach
          </ul>
	
</div>
<br>
@endif
<div class="row">
	<input type="hidden" id="suggestion_page" value="1">
	<div class="col-md-12">
		<div class="eq-height-row" id="product_list_body"></div>
	</div>
	<div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
		<i class="fa fa-spinner fa-spin fa-2x"></i>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
    const filters = document.querySelectorAll('#filters .filter');

    filters.forEach(function (filter) {
        filter.addEventListener('click', function (event) {
            // Prevent the default link behavior
            event.preventDefault();

            // Remove 'active' class from all filters
            filters.forEach(function (f) {
                f.classList.remove('active');
            });

            // Add 'active' class to the clicked filter
            this.classList.add('active');
        });
    });
});

	</script>