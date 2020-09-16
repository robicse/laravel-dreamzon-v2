@extends('backend._partial.dashboard')
<style>
    .requiredCustom{
        font-size: 20px;
        color: red;
        margin-top: 20px;
    }
</style>
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Add Sales Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('productSales.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Sales Product</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Add Sales Product</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('productSales.store') }}">
                        @csrf
{{--                        <div class="form-group row">--}}
{{--                            <label class="control-label col-md-3 text-right">Party  <small class="requiredCustom">*</small></label>--}}
{{--                            <div class="col-md-5">--}}
{{--                                <select name="party_id" id="customer" class="form-control select2">--}}
{{--                                    <option value="">Select One</option>--}}
{{--                                    @foreach($parties as $party)--}}
{{--                                        <option value="{{$party->id}}">{{$party->name}} </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3"><a type="button" class="test btn btn-primary btn-sm" onclick="modal_customer()" data-toggle="modal"><i class="fa fa-plus"></i></a></div>--}}
{{--                        </div>--}}
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Customer Name <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Customer Phone <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="phone" value="" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Customer Email</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control" name="email" value="" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Customer Address</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="address"></textarea>
                            </div>
                        </div>
                        <div class="form-group row" @if(Auth::user()->roles[0]->name == 'User') style="display: none" @endif>
                            <label class="control-label col-md-3 text-right">Store  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="store_id" id="store_id" class="form-control" >
                                    <option value="">Select One</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}" {{Auth::user()->roles[0]->name == 'User' ? 'selected':''}}>{{$store->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Payment Type  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="payment_type" id="payment_type" class="form-control" >
                                    <option value="">Select One</option>
                                    <option value="cash">cash</option>
                                    <option value="online">online</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Delivery Services  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="delivery_service" id="delivery_service" class="form-control" >
                                    <option value="">Select One</option>
                                    <option value="Sundorban Kuriar Service">Sundorban Kuriar Service</option>
                                    <option value="SA Paribahan">SA Paribahan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Delivery Services Charge <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="delivery_service_charge" value="" />
                            </div>
                        </div>

                        <input type="button" class="btn btn-primary add " style="margin-left: 804px;" value="Add More Product">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th >ID</th>
                                <th>Product <small class="requiredCustom">*</small></th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Brand</th>
                                <th>Return</th>
                                <th>Stock Qty</th>
                                <th>Qty <small class="requiredCustom">*</small></th>
                                <th>Price <small class="requiredCustom">*</small></th>
                                <th>Sub Total</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody class="neworderbody">
                            <tr>
                                <td width="5%" class="no">1</td>
                                <td>
                                    <select class="form-control product_id select2" name="product_id[]" id="product_id_1" onchange="getval(1,this);" required>
                                        <option value="">Select  Product</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div id="product_category_id_1">
                                        <select class="form-control product_category_id select2" name="product_category_id[]"  required>
                                            <option value="">Select  Category</option>
                                            @foreach($productCategories as $productCategory)
                                                <option value="{{$productCategory->id}}">{{$productCategory->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div id="product_sub_category_id_1">
                                        <select class="form-control product_sub_category_id select2" name="product_sub_category_id[]">
                                            <option value="">Select  Sub Category</option>
                                            @foreach($productSubCategories as $productSubCategory)
                                                <option value="{{$productSubCategory->id}}">{{$productSubCategory->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div id="product_brand_id_1">
                                        <select class="form-control product_brand_id select2" name="product_brand_id[]" required>
                                            <option value="">Select  Brand</option>
                                            @foreach($productBrands as $productBrand)
                                                <option value="{{$productBrand->id}}">{{$productBrand->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <select name="return_type[]" id="return_type_id_1" class="form-control" >
                                        <option value="returnable" selected>returnable</option>
                                        <option value="not returnable">not returnable</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" id="stock_qty_1" class="stock_qty form-control" name="stock_qty[]" value="" readonly >
                                </td>
                                <td>
                                    <input type="number" min="1" max="" class="qty form-control" name="qty[]" value="" required >
                                </td>
                                <td>
                                    <input type="number" id="price_1" min="1" max="" class="price form-control" name="price[]" value="" readonly required >
                                </td>
                                <td>
                                    <input type="text" class="amount form-control" name="sub_total[]">
                                </td>
                            </tr>

                            </tbody>

                            <tfoot>
                            <tr>
                                <th>&nbsp;</th>
                                <th colspan="2">
                                    Discount Type:
                                    <select name="discount_type" id="discount_type" class="form-control" >
                                        <option value="flat" selected>flat</option>
                                        <option value="percentage">percentage</option>
                                    </select>
                                </th>
                                <th colspan="2">
                                    Discount Amount:
                                    <input type="text" id="discount_amount" class="form-control" name="discount_amount">
                                </th>
                                <th colspan="2">
                                    Total:
                                    <input type="text" id="total_amount" class="form-control" name="total_amount">
                                </th>
                                <th colspan="2">
                                    Paid Amount:
                                    <input type="text" id="paid_amount" class="getmoney form-control" name="paid_amount">
                                </th>
                                <th colspan="2">
                                    Due Amount:
                                    <input type="text" id="due_amount" class="backmoney form-control" name="due_amount">
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="form-group row">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Product Sale</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tile-footer">
                </div>
            </div>
        </div>

        <!-- Credit Sale -->
        <div id="customar_modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:green; color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="customerErrr3" class="alert hide"> </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-body">
                                        <form action="{{ route('parties.store.new') }}" id="customer_insert" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Name <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="hidden" name="type" value="customer">
                                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" placeholder="Customer Name" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Phone <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" type="text" placeholder="Customer Phone" name="phone">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Email <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="Customer Email" name="email">
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Address <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <textarea rows="5" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" type="text" placeholder="Customer Address" name="address"></textarea>
                                                    @if ($errors->has('address'))
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3"></label>
                                                <div class="col-md-8">
                                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Party</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('js')
    <script>

        function totalAmount(){
            var t = 0;
            $('.amount').each(function(i,e){
                var amt = $(this).val()-0;
                t += amt;
            });
            $('#total_amount').val(t);
            //$('#paid_amount').val(0);
            //$('#due_amount').val(0);
        }
        $(function () {
            $('#discount_amount').change(function(){
                var discount_type = $('#discount_type').val();
                var total = $('#total_amount').val();
                var getmoney = $(this).val();
                if(discount_type == 'flat'){
                    var t = total - getmoney;
                }
                else{
                    var per = (total*getmoney)/100;
                    var t = total-per;
                }
                $('#total_amount').val(t);
            });
            $('.getmoney').change(function(){
                var total = $('#total_amount').val();
                var getmoney = $(this).val();
                var t = total - getmoney;
                $('.backmoney').val(t);
            });
            $('.add').click(function () {
                var productCategory = $('.product_category_id').html();
                var productSubCategory = $('.product_sub_category_id').html();
                var productBrand = $('.product_brand_id').html();
                var product = $('.product_id').html();
                var n = ($('.neworderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' +
                    '<td><select class="form-control product_id select2" name="product_id[]" id="product_id_'+n+'" onchange="getval('+n+',this);" required>' + product + '</select></td>' +
                    '<td><div id="product_category_id_'+n+'"><select class="form-control product_category_id select2" name="product_category_id[]" required>' + productCategory + '</select></div></td>' +
                    '<td><div id="product_sub_category_id_'+n+'"><select class="form-control product_sub_category_id select2" name="product_sub_category_id[]" required>' + productSubCategory + '</select></div></td>' +
                    '<td><div id="product_brand_id_'+n+'"><select class="form-control product_brand_id select2" name="product_brand_id[]" id="product_brand_id_'+n+'" required>' + productBrand + '</select></div></td>' +
                    '<td><select name="return_type[]" id="return_type_id_'+n+'" class="form-control" ><option value="returnable" selected>returnable</option><option value="not returnable">not returnable</option></select></td>' +
                    '<td><input type="number" id="stock_qty_'+n+'" class="stock_qty form-control" name="stock_qty[]"></td>' +
                    '<td><input type="number" min="1" max="" class="qty form-control" name="qty[]" required></td>' +
                    '<td><input type="text" id="price_'+n+'" min="1" max="" class="price form-control" name="price[]" value="" readonly required></td>' +
                    //'<td><input type="number" min="0" value="0" max="100" class="dis form-control" name="discount[]" required></td>' +
                    '<td><input type="text" class="amount form-control" name="sub_total[]" required></td>' +
                    '<td><input type="button" class="btn btn-danger delete" value="x"></td></tr>';

                $('.neworderbody').append(tr);

                //initSelect2();

                $('.select2').select2();

            });
            $('.neworderbody').delegate('.delete', 'click', function () {
                $(this).parent().parent().remove();
                totalAmount();
            });

            $('.neworderbody').delegate('.qty, .price', 'keyup', function () {
                var gr_tot = 0;
                var tr = $(this).parent().parent();
                var qty = tr.find('.qty').val() - 0;
                var stock_qty = tr.find('.stock_qty').val() - 0;
                if(qty > stock_qty){
                    alert('You have limit cross of stock qty!');
                    tr.find('.qty').val(0)
                }

                //var dis = tr.find('.dis').val() - 0;
                var price = tr.find('.price').val() - 0;

                //var total = (qty * price) - ((qty * price)/100);
                //var total = (qty * price) - ((qty * price * dis)/100);
                //var total = price - ((price * dis)/100);
                //var total = price - dis;
                var total = (qty * price);

                tr.find('.amount').val(total);
                //Total Price
                $(".amount").each(function() {
                    isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
                });
                var final_total = gr_tot;
                console.log(final_total);
                var discount = $("#discount_amount").val();
                var final_total     = gr_tot - discount;
                //$("#total_amount").val(final_total.toFixed(2,2));
                $("#total_amount").val(final_total);
                var t = $("#total_amount").val(),
                    a = $("#paid_amount").val(),
                    e = t - a;
                //$("#remaining_amnt").val(e.toFixed(2,2));
                $("#due_amount").val(e);
                totalAmount();
            });

            $('#hideshow').on('click', function(event) {
                $('#content').removeClass('hidden');
                $('#content').addClass('show');
                $('#content').toggle('show');
            });



        });


        // ajax
        function getval(row,sel)
        {
            var store_id = $('#store_id').val();
            if(store_id){
                //console.log(store_id)
                //alert(row);
                //alert(sel.value);
                var current_row = row;
                var current_product_id = sel.value;

                $.ajax({
                    url : "{{URL('product-sale-relation-data')}}",
                    method : "get",
                    data : {
                        store_id : store_id,
                        current_product_id : current_product_id,
                    },
                    success : function (res){
                        //console.log(res)
                        console.log(res.data)
                        //console.log(res.data.categoryOptions)
                        $("#product_category_id_"+current_row).html(res.data.categoryOptions);
                        $("#product_sub_category_id_"+current_row).html(res.data.subCategoryOptions);
                        $("#product_brand_id_"+current_row).html(res.data.brandOptions);
                        $("#stock_qty_"+current_row).val(res.data.current_stock);
                        $("#price_"+current_row).val(res.data.mrp_price);
                    },
                    error : function (err){
                        console.log(err)
                    }
                })
            }else{
                alert('Please select first store!');
                location.reload();
            }
        }





        function modal_customer(){
            $('#customar_modal').modal('show');
        }

        //new customer insert
        $("#customer_insert").submit(function(e){
            e.preventDefault();
            //var customerMess    = $("#customerMess3");
            //var customerErrr    = $("#customerErrr3");
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function()
                {
                    //customerMess.removeClass('hide');
                    //customerErrr.removeClass('hide');
                },
                success: function(data)
                {
                    console.log(data);
                    if (data.exception) {
                        customerErrr.addClass('alert-danger').removeClass('alert-success').html(data.exception);
                    }else{
                        $('#customer').append('<option value = "' + data.id + '"  selected> '+ data.name + ' </option>');
                        console.log(data.id);
                        $("#customar_modal").modal('hide');
                    }
                },
                error: function(xhr)
                {
                    alert('failed!');
                }
            });
        });

        function hidemodal() {
            var x = document.getElementById("customar_modal");
            x.style.display = "none";
        }
    </script>
@endpush


