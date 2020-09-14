@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All User</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary" type="button">Add User</a></li>
            </ul>
        </div>
{{--        <div class="card-body">--}}
{{--            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                <input type="file" name="file" class="form-control">--}}
{{--                <br>--}}
{{--                <button class="btn btn-success">Import data</button>--}}
{{--                <a class="btn btn-warning" href="{{ route('export') }}">Export Data</a>--}}
{{--            </form>--}}
{{--        </div>--}}
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">User Table</h3>
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
                 <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="10%">SL</th>
                        <th width="10%">Name</th>
                        <th width="10%">Email</th>
                        <th width="10%">Role</th>
                        <th width="15%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)
                    <tr style="{{$user->user_role == 2 ? 'opacity:.5' : ''}};">
                        <td>{!! $key+1 !!}</td>
                        <td>{!! $user->name !!}</td>
                        <td>{!! $user->email !!}</td>
                        <td>
                            @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                    <label class="badge badge-success">{{ $v }}</label>
                                @endforeach
                            @endif
                        </td>
                        <td>
{{--                            <a href="" class="btn{{$user->status == 0 ? ' btn-dark' : ' btn-primary' }}" onclick="event.preventDefault();document.getElementById('visibility-form-{{$user->id}}').submit();"><i class="{{$user->status == 0 ? 'fas fa-ban' : 'fas fa-skiing' }}"></i></a>--}}
{{--                            <form id="visibility-form-{{$user->id}}" action="{{ route('user.active') }}" method="POST" style="display: none;">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="soft_delete" value="{{$user->id}}">--}}
{{--                            </form>--}}

                            {{--<a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>--}}
                            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                            {{--<a href="" class="btn{{$user->user_role == 2 ? ' btn-dark' : ' btn-primary' }}" onclick="event.preventDefault();document.getElementById('visibility-form-{{$user->id}}').submit();"><i class="{{$user->user_role == 2 ? 'fas fa-ban' : 'fas fa-skiing' }}"></i></a>
                            <form id="visibility-form-{{$user->id}}" action="" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="soft_delete" value="{{$user->id}}">
                            </form>
                            <a href="" class="btn btn-danger" onclick="return confirm('ARE YOU SURE DELETE THIS !')" > Delete</a>--}}
                        </td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="tile-footer">
              {{$users->links()}}
                </div>
            </div>
        </div>
    </main>
@endsection


