<?php

namespace App\Http\Controllers;

use App\Models\User;
use Validator;
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

    public function doLogin(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ],
            [
                'email.required' => 'Email cannot be empty',
                'email.email' => 'Invalid email format',
                'password.required' => 'Password cannot be empty',
            ]);

        if($validator->fails()){
            return response()->json(array('status'=>'fail','message'=>$validator->errors()->first()), 200);
        }

        $user = User::with('role')->where('email', $request->email)->first();

        if(empty($user)){
            return response()->json(array('status'=>'fail','message'=>'Email not found'), 200);
        } else {
            if(empty($user->role)) {
                return response()->json(array('status'=>'fail','message'=>'Make sure you already register'), 200);
            }
            $roleCheck = 0;
            foreach($user->role as $val) {
                if($val->id == 1){
                    $roleCheck = 1;
                }
            }

            if($roleCheck == 1) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put('name',$user->name);
                    $request->session()->put('email',$user->email);
                    $request->session()->put('isLogin',TRUE);
                    return response()->json(array('status'=>'success',$user), 200);
                } else {
                    return response()->json(array('status'=>'fail',$user), 200);
                }
            } else {
                return response()->json(array('status'=>'fail','message'=>'Your account is not able to login'), 200);
            }

        }
    }

}
