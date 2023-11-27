<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class adminController extends Controller
{
    public function getSignin(){
        return view('admin.signin');
       }

       public function postSignin(Request $request)
       { 
           $this->validate($request, [
               'email' => 'email|required',
               'password' => 'required|min:4'
           ]);
       
           if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
               $user = Auth::user();
       
               if ($user->hasAuthorizedRole()) {
                   return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
               } else {
                   Auth::logout();
                   return redirect()->back()->with('error', 'Invalid Account');
               }
           }
       
           return redirect()->back()->with('error', 'Invalid credentials');
       }
}
