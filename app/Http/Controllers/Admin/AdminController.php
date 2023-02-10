<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:admins,email',
            'password' => 'required|min:5|max:30',
        ],[
            'email.exists'=>'This email is not exists on admins table'
        ]);

        $creds = $request->only('email', 'password');
        if( Auth::guard('admin')->attempt($creds) ){
            return redirect()->route('admin.home');
        }else{
            return redirect()->route('admin.login')->with('fail','Incorrect credentials');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
