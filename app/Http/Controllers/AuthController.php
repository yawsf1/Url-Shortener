<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Guest;
use App\Models\Url;
use App\Models\User;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        if($request->urls){
            foreach($request->urls as $url){
                Url::where('short_url', $url['shortened'])
                    ->whereNull('user_id')
                    ->update(['user_id' => $user->id]);
            }
        }
        session()->flash('success', 'Successfully Registered');
        return redirect()->route('home');
    }

    public function logout(){
        Auth::logout();
        session()->flash('success', 'Successfully Logged Out');
        return redirect()->route('home');
    }
    public function login(LoginRequest $request){
        $remember = $request->boolean('remember');

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)){
            $request->session()->regenerate(); 

            if($request->urls){
                foreach($request->urls as $url){
                    Url::where('short_url', $url['shortened'])
                        ->whereNull('user_id')
                        ->update(['user_id' => Auth::id()]);
                }
            }

            session()->flash('success', 'Successfully Logged In');
            return redirect()->route('home');
        }

        session()->flash('error', 'Invalid Credentials');
        return redirect()->route('loginPage');
    }
}