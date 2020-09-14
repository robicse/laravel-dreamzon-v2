@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Party</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('party.create') !!}" class="btn btn-sm btn-primary" type="button">Add Party</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Party Table</h3>
              <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="5%">#Id</th>
                        <th width="10%">Type</th>
                        <th width="15%">Name</th>
                        <th width="15%">Phone</th>
                        <th width="15%">Email</th>
                        <th width="15%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($parties as $key => $party)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $party->type}}</td>
                        <td>{{ $party->name}}</td>
                        <td>{{ $party->phone}}</td>
                        <td>{{ $party->email}}</td>
                        <td>
                            <a href="{{ route('party.edit',$party->id) }}" class="btn btn-sm btn-primary float-left"><i class="fa fa-edit"></i></a>
                            <form method="post" action="{{ route('party.destroy',$party->id) }}" >
                               @method('DELETE')
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="tile-footer">
                </div>
{{--                {{ $parties->links() }}--}}
            </div>

        </div>
    </main>
@endsection


