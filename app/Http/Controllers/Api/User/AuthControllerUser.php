<?php

namespace App\Http\Controllers\Api\User;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

// use Tymon\JWTAuth\Contracts\Providers\Auth;

class AuthControllerUser extends Controller
{
    use GeneralTrait;

    public function login(Request $request){
        $rules = [
            "email" => 'required',
            "password" => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        $credentials = $request->only(['email', 'password']);
        $token = auth()->guard('user-api')->attempt($credentials);
        if (!$token) {
            return $this->returnError('E001','Invalid credentials');
        }
        $user = auth()->guard('user-api')->user();
        $user -> token_api = $token;
        return $this->returnData('user',$user,'تم بنجاح');
    }

    public function logout(Request $request){
        $token = $request->header('auth-token');
        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError('E5001','some error occurred1');
            }
            return $this->returnSuccessMessage('logged out successfully');
        }else {
            return $this->returnError('E5001','some error occurred2');
        }

    }
}
