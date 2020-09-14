<?php

namespace App\Http\Controllers;

use App\Party;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PartyController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:party-list|party-create|party-edit|party-delete', ['only' => ['index','show']]);
        $this->middleware('permission:party-create', ['only' => ['create','store']]);
        $this->middleware('permission:party-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:party-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $parties = Party::latest()->get();
        return view('backend.party.index',compact('parties'));
    }


    public function create()
    {
        return view('backend.party.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'type'=> 'required',
            //'name' => 'required|unique:parties,name',
            'name' => 'required',
            'phone'=> 'required',
            'email'=> '',
            'address'=> '',
        ]);
        $parties = new Party();
        $parties->type = $request->type;
        $parties->name = $request->name;
        $parties->slug = Str::slug($request->name);
        $parties->phone = $request->phone;
        $parties->email = $request->email;
        $parties->address = $request->address;
        $parties->status = $request->status;
       // dd($parties);
        $parties->save();
        Toastr::success('Party Created Successfully', 'Success');
        return redirect()->route('party.index');

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {

        $parties = Party::find($id);
       // dd($parties);
        return view('backend.party.edit',compact('parties'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type'=> 'required',
            'name'=> 'required',
//            'name' => [
//                'required',
//                Rule::unique('parties')->ignore($id),
//            ],
            'phone'=> 'required',
            'email'=> '',
            'address'=> '',
        ]);

        $parties = Party::find($id);
        $parties->type = $request->type;
        $parties->name = $request->name;
        $parties->slug = Str::slug($request->name);
        $parties->phone = $request->phone;
        $parties->email = $request->email;
        $parties->address = $request->address;
        $parties->status = $request->status;
        // dd($parties);
        $parties->save();
        Toastr::success('Party Updtaed Successfully', 'Success');
        return redirect()->route('party.index');
    }


    public function destroy($id)
    {
        $parties = Party::find($id);
        $parties->delete();
        Toastr::success('Party Deleted Successfully Done!');
        return redirect()->route('party.index');
    }
}
