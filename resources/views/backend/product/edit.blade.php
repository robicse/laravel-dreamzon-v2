@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Edit Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Product</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Edit Product</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('products.update',$product->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Name <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" placeholder="Name" name="name" value="{{$product->name}}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Product Category <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <select name="product_category_id" id="product_category_id" class="form-control">
                                    <option value="">Select One</option>
                                    @foreach($productCategories as $productCategory)
                                        <option value="{{$productCategory->id}}" {{$productCategory->id == $product->product_category_id ? 'selected' : ''}}>{{$productCategory->name}}</option>
                                    @endforeach()
                                </select>
                                @if ($errors->has('product_category_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Product Sub Category</label>
                            <div class="col-md-8">
                                <select name="product_sub_category_id" id="product_sub_category_id" class="form-control">
                                    <option value="">Select One</option>
                                    @foreach($productSubCategories as $productSubCategory)
                                        <option value="{{$productSubCategory->id}}" {{$productSubCategory->id == $product->product_sub_category_id ? 'selected' : ''}}>{{$productSubCategory->name}}</option>
                                    @endforeach()
                                </select>
                                @if ($errors->has('product_sub_category_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_sub_category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Product Brand <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <select name="product_brand_id" id="product_brand_id" class="form-control">
                                    <option value="">Select One</option>
                                    @foreach($productBrands as $productBrand)
                                        <option value="{{$productBrand->id}}" {{$productBrand->id == $product->product_brand_id ? 'selected' : ''}}>{{$productBrand->name}}</option>
                                    @endforeach()
                                </select>
                                @if ($errors->has('product_brand_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_brand_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">SKU</label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('sku') ? ' is-invalid' : '' }}" type="text" placeholder="SKU" name="sku" value="{{$product->sku}}">
                                @if ($errors->has('sku'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sku') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Model</label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('model') ? ' is-invalid' : '' }}" type="text" placeholder="Model" name="model" value="{{$product->model}}">
                                @if ($errors->has('model'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('model') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Image <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <img src="{{Storage::url('uploads/product/'.$product->image)}}" alt="" width="100px;">
                                <input class="{{ $errors->has('image') ? ' is-invalid' : '' }}" type="file" placeholder="Image" name="image" value="{{$product->image}}">
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                                <br/>
                                <img src="{{asset('uploads/product/'.$product->image)}}" height="100px" width="100px"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Status <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Barcode <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('barcode') ? ' is-invalid' : '' }}" type="text" placeholder="barcode" name="barcode" value="{{$product->barcode}}">
                                @if ($errors->has('barcode'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('barcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Product</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tile-footer">
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    <script>
        $('#product_category_id').change(function(){
            var product_category_id = $(this).val();
            //alert(product_category_id);
            $.ajax({
                url : "{{URL('sub-category-list')}}",
                method : "get",
                data : {
                    product_category_id : product_category_id
                },
                success : function (res){
                    console.log(res)
                    $('#product_sub_category_id').html(res.data)
                },
                error : function (err){
                    console.log(err)
                }
            })
        })
    </script>
@endpush


