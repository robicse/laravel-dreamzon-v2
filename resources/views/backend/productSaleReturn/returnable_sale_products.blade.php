@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            {{--<div>
                <h1><i class=""></i> Add Sales Product</h1>
            </div>--}}
            <!--<ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('productSales.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Sales Product</a>
                </li>
            </ul>-->
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Returnable Sales Product</h3>
                <div class="tile-body tile-footer">
                    <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th >ID</th>
                                    <th>Party</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Brand</th>
                                    <th>Return Condition</th>
                                    <th>Received Qty</th>
                                    <th>Price</th>
                                    <th>Returned</th>
                                </tr>
                            </thead>
                            <tbody class="neworderbody">
                            @foreach($returnable_sale_products as $returnable_sale_product)
                                <tr>
                                    <td width="5%" class="no">1</td>
                                    <td>
                                        @php
                                            $party_name = DB::table('product_sales')
                                                ->join('product_sale_details', 'product_sales.id', '=', 'product_sale_details.product_sale_id')
                                                ->join('parties', 'parties.id', '=', 'product_sales.party_id')
                                                ->where('product_sale_details.id',$returnable_sale_product->id)
                                                ->select('parties.name')
                                                ->first();
                                            //dd($party_name);
                                        @endphp
                                        {{$party_name->name}}
                                    </td>
                                    <td>{{$returnable_sale_product->product->name}}</td>
                                    <td>{{$returnable_sale_product->product->product_category->name}}</td>
                                    <td>{{$returnable_sale_product->product->product_sub_category ? $returnable_sale_product->product->product_sub_category->name : ''}}</td>
                                    <td>{{$returnable_sale_product->product->product_brand->name}}</td>
                                    <td>{{$returnable_sale_product->return_type}}</td>
                                    <td>{{$returnable_sale_product->qty}}</td>
                                    <td>{{$returnable_sale_product->price}}</td>
                                    <td>
                                        <form method="post" action="{{route('sale.product.return')}}">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Qty  <small class="text-danger">*</small></label>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="product_sale_id" class="form-control" value="{{$returnable_sale_product->product_sale_id}}">
                                                    <input type="hidden" name="product_sale_detail_id" class="form-control" value="{{$returnable_sale_product->id}}">
                                                    <input type="number" min="1" name="return_qty" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Amount  <small class="text-danger">*</small></label>
                                                <div class="col-md-8">
                                                    <input type="number" min="1" name="total_amount" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Payment Type  <small class="text-danger">*</small></label>
                                                <div class="col-md-8">
                                                    <select name="payment_type" id="payment_type" class="form-control" >
                                                        <option value="">Select One</option>
                                                        <option value="cash">cash</option>
                                                        <option value="online">online</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Reason</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="reason"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3"></label>
                                                <div class="col-md-8">
                                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tfoot>
                        </table>

                </div>
                <div class="tile-footer">
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
            $('.total').html(t);
        }
        $(function () {
            $('.getmoney').change(function(){
                var total = $('.total').html();
                var getmoney = $(this).val();
                //var t = getmoney - total;
                var t = total - getmoney;
                var t_final_val = t.toFixed(2);
                $('.backmoney').val(t_final_val);
                $('.total').val(total);
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
                    '<td><input type="number" min="1" max="" class="qty form-control" name="qty[]" required></td>' +
                    '<td><input type="text" min="1" max="" class="price form-control" name="price[]" value="" required></td>' +
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
                var tr = $(this).parent().parent();
                var qty = tr.find('.qty').val() - 0;
                //var dis = tr.find('.dis').val() - 0;
                var price = tr.find('.price').val() - 0;

                //var total = (qty * price) - ((qty * price)/100);
                //var total = (qty * price) - ((qty * price * dis)/100);
                //var total = price - ((price * dis)/100);
                //var total = price - dis;
                var total = (qty * price);

                tr.find('.amount').val(total);
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
            //alert(row);
            //alert(sel.value);
            var current_row = row;
            var current_product_id = sel.value;

            $.ajax({
                url : "{{URL('product-relation-data')}}",
                method : "get",
                data : {
                    current_product_id : current_product_id
                },
                success : function (res){
                    //console.log(res)
                    console.log(res.data)
                    //console.log(res.data.categoryOptions)
                    $("#product_category_id_"+current_row).html(res.data.categoryOptions);
                    $("#product_sub_category_id_"+current_row).html(res.data.subCategoryOptions);
                    $("#product_brand_id_"+current_row).html(res.data.brandOptions);
                },
                error : function (err){
                    console.log(err)
                }
            })
        }
    </script>
@endpush


