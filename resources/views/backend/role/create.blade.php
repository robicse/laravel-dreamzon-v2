@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Add Role</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary" type="button">All View Role</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Add Role</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                        {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Role Name') }} <span style="color: red">*</span></label>
                                <div class="col-md-6">
                                    {!! Form::text('name', null, array('placeholder' => 'Role Name','class' => 'form-control')) !!}
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            {{--<div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Permissions') }}</label>
                                <div class="col-md-6">
                                    {!! Form::text('name', null, array('placeholder' => 'Role Name','class' => 'form-control')) !!}
                                    <ul class="list-unstyled">
                                        @foreach($permission as $value)
                                        <li>{{$value->controller_name}}
                                            <ul>
                                                <li>action</li>
                                            </ul>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>--}}


                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Permission') }} <span style="color: red">*</span></label>
                                <div class="col-md-6">
                                    <div class="row">
                                        @foreach($permission as $value)
                                        <div class="col-md-6">
                                            <label>{{ $value->controller_name }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                                {{ $value->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
{{--                                <div class="col-md-2">--}}
{{--                                    <a href="" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#exampleModal">Create Permission List</a>--}}
{{--                                </div>--}}
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                </div>
                <div class="tile-footer">
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Create Permission List</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{URL('roles/permission')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="due">Enter Controller <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" id="controller_permission" aria-describedby="emailHelp" name="controller_name" placeholder="ProductController">
                                    </div>
                                    <div class="form-group">
                                        <label for="due">Enter Controller Action <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" id="permission" aria-describedby="emailHelp" name="name" placeholder="product-list">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


