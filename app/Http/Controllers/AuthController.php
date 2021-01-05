<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login() {
        //echo app('hash')->make('Rockaroma$ystem2');
        return view('login');
    }

    public function loginAuth(Request $request) {
        $username = $request->username;
        $password = $request->password;

        $data = User::where('email',$username)->first();
        //echo json_encode($data); die();
        if($data){
            if(Hash::check($password,$data->password)){
                $request->session()->put('name',$data->name);
                $request->session()->put('email',$data->email);
                $request->session()->put('isLogin',TRUE);
                return redirect('dashboard-page');
            }
            else{
                //$request->session()->flash('message', 'Password anda salah');
                return redirect()->route('/')->with('message', 'Password anda salah');
            }
        }
        else{
            //$request->session()->flash('message', 'Username tidak ditemukan');
            return redirect()->to('/')->with(['message'=>'Username tidak ditemukan']);
        }
    }

    public function logoutAuth(Request $request) {
        $request->session()->flush();
        return redirect('/');
    }

}
