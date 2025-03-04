@extends('layouts.restaurant')
@section('title', __( 'restaurant.kitchen' ))

@section('content')
<style>
.box-header {
    color: #444;
    display: block;
    padding: 10px;
    position: relative;
}
header:before {
    content: " ";
    display: table;
}
.box, .info-box {
    margin-bottom: 30px;
    box-shadow: 2px 2px 4px rgba(136,152,170,.15)!important;
    border-radius: 5px;
}
.box {
    position: relative;
    border-radius: 3px;
    background: #fff;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
  }
  .small-box.bg-gray:hover {
    color: #000;
    text-decoration: none;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
}
.bg-gray {
    color: #000;
    background-color: #d2d6de!important;
}
th{
  text-align: left;
}
body {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
}
.small-box>.small-box-footer {
    position: relative;
    text-align: center;
    padding: 3px 0;
    color: #fff;
    color: rgba(255,255,255,.8);
    display: block;
    z-index: 10;
    background: rgba(0,0,0,.1);
    text-decoration: none;
}
</style>
<!-- Main content -->
<section class="content min-height-90hv no-print">

<div class="row">
    <div class="col-md-12 text-center">
        <h3>@lang( 'restaurant.all_orders' ) - @lang( 'restaurant.kitchen' ) @show_tooltip(__('lang_v1.tooltip_kitchen'))</h3>
    </div>
</div>


	<div class="box">
        <div class="box-header">
            <button type="button" class="btn btn-sm btn-primary pull-right" id="refresh_orders" style="background: #1572e8;border-color: #1367d1;"><i class="fas fa-sync"></i> @lang( 'restaurant.refresh' )</button>
        </div>
        <div class="box-body">
            <input type="hidden" id="orders_for" value="kitchen">
        	<div class="row" id="orders_div" style="margin-left:10px;">
             @include('restaurant.partials.show_orders', array('orders_for' => 'kitchen'))
            </div>
        </div>
        <div class="overlay hide">
          <i class="fas fa-sync fa-spin"></i>
        </div>
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', 'a.mark_as_cooked_btn', function(e){
                e.preventDefault();
                swal({
                  title: LANG.sure,
                  icon: "info",
                  buttons: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var _this = $(this);
                        var href = _this.data('href');
                        $.ajax({
                            method: "GET",
                            url: href,
                            dataType: "json",
                            success: function(result){
                                if(result.success == true){
                                    toastr.success(result.msg);
                                    _this.closest('.order_div').remove();
                                    window.location.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
