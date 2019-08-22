<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    //
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    //注册自动登陆
    public function store(Request $request)
    {
       $this->validate($request,[
           'name'=>'required|max:50',
           'email'=>'required|email|unique:users|max:255',
           'password'=>'required|confirmed|min:6'
       ]);

       $user = User::create([
           'name'=>$request->name,
           'email'=>$request->email,
           'password'=>bcrypt($request->password),
       ]);
       Auth::login($user);
       session()->flash('success','欢迎，您将在这里开启一段新的旅程');
       return redirect()->route('users.show',[$user]);
    }

    //编辑用户
    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {

         $this->validate($request,[
             'name'=>'required|max:50',
             'password'=>'nullable|confirmed',
         ]);
         /*$user->update([
             'name'=>$request->name,
             'password'=>bcrypt($request->password)
         ]);*/
         $data = [];
         $data['name'] = $request->name;
         if($request->password)
         {
             $data['password']=$request->password;
         }
         $user->update($data);
         session()->flash('success','个人资料更新成功');

         return redirect()->route('users.show',$user);
    }

}
