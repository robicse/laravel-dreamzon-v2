<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $users=User::orderBy('id','DESC')->paginate(5);
        return view('backend.user.index',compact('users'));
    }

    public function create()
    {
        //$roles = Role::pluck('name','name')->all();
        $roles = Role::where('name','!=','Admin')->pluck('name','name')->all();
        return view('backend.user.create',compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);


        $user = User::create($input);
        $user->assignRole($request->input('roles'));


        /*return redirect()->route('users.index')
            ->with('success','User created successfully');*/
        Toastr::success('User Created Successfully');
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        //$roles = Role::where('name','!=','Admin')->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        return view('backend.user.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }


        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();


        $user->assignRole($request->input('roles'));


        Toastr::success('User Updated Successfully');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        Toastr::success('User Deleted Successfully');
        return redirect()->route('users.index');
    }

    public function activeDeactive (Request $request){
        $softDelete = User::findOrFail($request->soft_delete);
        if($softDelete->status == 1 ){
            $softDelete->status = 0;
        }else{
            $softDelete->status = 1;
        }
        $softDelete->save();
        //return back() ->withResponse('Successfully');
        Toastr::success('Status Changed Successfully');
        return redirect()->route('users.index');
    }

    public function importExportView()
    {
        return view('import');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import()
    {
        Excel::import(new UsersImport,request()->file('file'));

        return back();
    }
}
