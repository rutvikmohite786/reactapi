<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return $this->apiFail($validator->errors()->all(),422);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]); 
        $token = $user->createToken($request->email)->accessToken;
        $user['token'] = $token;
        return $this->apiSuccess('User created',200,$user);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return $this->apiFail('Unauthorized',422);
        }else{
            $user = User::where('email',$request->email)->first();
            $user['token'] =  $user->createToken('Laravel Password Grant Client')->accessToken;
            return $this->apiSuccess('User Log in',200,$user);
        }
    }
}