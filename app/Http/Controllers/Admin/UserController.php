<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users= SELECT * FROM users
        //$users = User::all();

        $users = User::paginate(5);
        return view('admin.users.all' , compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("admin.users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Make Validate 
        $validator = Validator::make($request->all() , [
                                     'name' => ['required' , 'string' , 'min:4', 'max:255'],
                                     'email' => ['required' , 'email' , 'unique:users'],
                                     'password'=>['required', 'min:8']  
        ]);
          if($validator->fails()){
              return redirect()->back()->withErrors($validator)->withInput($request->all());
          }                          
       //insert Data in DB 
       User::create([
        'name' => $request->name,
        'email' =>$request->email,
        'password' => Hash::make($request->password),
       ]);
       return redirect()->back()->with(['success' => 'User has been added']);
       //return "done";
        //return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //SELECT * FROM users Where id=? 
       $user = User::findOrFail($id);
       return view('admin.users.edit' , compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //Make Validate 
        $validator = Validator::make($request->all() , [
        'name' => ['required' , 'string' , 'min:4', 'max:255'],
        'email' => ['required' , 'email' , 'unique:users']
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }     
        $user = User::findOrFail($id);
        $user->update([
             "name" => $request->name , 
             "email" => $request->email
        ]);
        return redirect()->back()->with(['success' => 'User has been Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
         $user = User::findOrFail($id);
         $user->delete();
         return redirect()->back()->with(['success' => 'User has been Deleted']);
    }
}
