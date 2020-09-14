@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Add Store</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('stores.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Store</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Add Store</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('stores.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Store User <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Select One</option>
                                    @foreach($users as $user)
                                        @php
                                            $check_user_store = \App\Store::where('user_id',$user->id)->pluck('name')->first();
                                        @endphp
                                        <option value="{{$user->id}}">{{$user->name}} {{$check_user_store ? ' => '.$check_user_store : ''}}</option>
                                    @endforeach()
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Store Name <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" placeholder="Name" name="name">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Store Phone <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" type="text" placeholder="Phone" name="phone">
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Store Address <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" type="text" placeholder="Address" name="address">
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Store Logo <span style="color: red">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control{{ $errors->has('logo') ? ' is-invalid' : '' }}" type="file" name="logo">
                                @if ($errors->has('logo'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('logo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Store</button>
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


