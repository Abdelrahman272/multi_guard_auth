<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'hospital'=>'required',
            'email'=>'required|email|unique:doctors,email',
            'password' => 'required|min:5|max:30',
            'cpassword' => 'required|min:5|max:30|same:password',
        ]);

        $doctor = new Doctor();
        $doctor->name = $request->name;
        $doctor->email = $request->email;
        $doctor->hospital = $request->hospital;
        $doctor->password = Hash::make($request->password);
        $save = $doctor->save();

        if( $save ){
            return redirect()->back()->with('success','You are now registered successfully');
        }else{
            return redirect()->back()->with('fail','Something went wrong, failed to register');
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:doctors,email',
            'password' => 'required|min:5|max:30',
        ],[
            'email.exists'=>'This email is not exists on doctors table'
        ]);

        $creds = $request->only('email', 'password');
        if( Auth::guard('doctor')->attempt($creds) ){
            return redirect()->route('doctor.home');
        }else{
            return redirect()->route('doctor.login')->with('fail','Incorrect credentials');
        }
    }

    public function logout()
    {
        Auth::guard('doctor')->logout();
        return redirect('/');
    }
}
