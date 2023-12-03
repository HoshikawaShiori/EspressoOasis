<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
   public function getSignup(){
    return view('user.signup');
   }
   public function postSignup(Request $request){
    $this->validate($request, [
    'email'=> 'email|required|unique:users',
    'username' => 'required|min:6',
    'password'=> 'required|min:8']);

    $user = new User([
        'email'=> $request->input('email'),
        'username' => $request->input('username'),
        'password'=> bcrypt($request->input('password')),
    ]);
    $user-> save();
    $user->assignRole('user');
    Auth::login($user);

    return redirect()->route('coffee.shop')->with('success','');
   }

   public function postAdmin(Request $request, $role){
    $this->validate($request, [
    'email'=> 'email|required|unique:users',
    'username' => 'required|min:6',
    'password'=> 'required|min:8']);

    $user = new User([
        'email'=> $request->input('email'),
        'username' => $request->input('username'),
        'password'=> bcrypt($request->input('password')),
    ]);
    $user-> save();
    $user->assignRole($role);

    return redirect()->route('accounts')->with('success','Account Added');
   }

   public function editAdmin(Request $request, $id){
       
    $this->validate($request, [
        'editemail' => [
            'email',
            'required',
            Rule::unique('users', 'email')->ignore($id),
        ],
        'editusername' => 'required|min:6',
    ], [
        'editemail.email' => 'The email must be a valid email address.',
        'editemail.required' => 'The email field is required.',
        'editemail.unique' => 'The email has already been taken.',
        'editusername.required' => 'The username field is required.',
        'editusername.min' => 'The username must be at least :min characters.',
        'editpassword.min' => 'The password must be at least :min characters.'
    ]);
    
        $selectedRoleId = $request->input('editrole');
        $role = Role::findOrFail($selectedRoleId);

        $user = User::findOrFail($id);
        $user->email=$request->input('editemail');
        $user->username=$request->input('editusername');

        if ($request->filled('editpassword')) {
            $user->password = bcrypt($request->input('editpassword'));
        }
        $user->roles()->sync([$role->id]);
        $user->save();  
        return redirect()->route('accounts')->with('success','Account Editted');
  
   }


   public function getSignin(){
    return view('user.signin');
   }
   public function postSignin(Request $request)
   {
       $this->validate($request, [
           'email' => 'email|required',
           'password' => 'required|min:4'
       ]);
   
       if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
           $user = Auth::user();
   
           if ($user->hasUserRole()) {
               return redirect()->route('coffee.index')->with('success', 'Logged in successfully!');
           } else {
               Auth::logout();
               return redirect()->back()->with('error', 'Invalid Account');
           }
       }
   
       return redirect()->back()->with('error', 'Invalid credentials');
   }
   

   public function redirectToGoogle()
   {
       return Socialite::driver('google')->redirect();
   }

   
public function handleGoogleCallback()
{
    try {
        $user = Socialite::driver('google')->user();

        // Check if the user already exists in your database
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            if ($existingUser->hasUserRole()) {
                Auth::login($existingUser);
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Invalid Account');
            }
            
        } else {
            // Create a new user record in the database
            $newUser = User::create([
                'email' => $user->getEmail(),
                'username' => $this->generateUniqueUsername($user->getName()), // Generate a unique username
                'password' => bcrypt(Str::random(12)), // Generate a random password
            ]);
            $newUser->assignRole('user');
            Auth::login($newUser);
        }

        return redirect()->route('coffee.shop')->with('success', 'Logged in successfully!');
    } catch (\Exception $e) {
        // Handle exception, show error, or redirect as needed
        return $e;
    }
}

// Function to generate a unique username based on the user's name
protected function generateUniqueUsername($name)
{
    // Logic to generate a unique username based on the user's name or other criteria
    // Example: Convert the name to lowercase and append a random number
    return Str::lower(str_replace(' ', '', $name)) . rand(100, 999);
}

   public function getProfile(){
    $orders = Auth::user()->orders;
    $orders-> transform(function($order, $key){
        $order->cart=unserialize($order->cart);
        return $order;
    });
    return view('user.profile',['orders'=> $orders]);
}
    public function getLogout(){
        Auth::logout();
        return redirect()->back()->with('success','');
    }
}
