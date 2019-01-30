<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Tymon\JWTAuth\JWTAuth;
use App\User;
use App\Dosen;

class Auth extends Controller
{
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->user = new User;
        $this->admin = new Dosen;
    }

    public function userLogin(Request $request){
        $this->jwt->config->set('jwt.user', 'App\User');
        $this->jwt->config->set('auth.providers.users.model', \App\User::class);
        $credentials = $request->only('nim', 'sandi');
        $token = null;
        try {
            if (!$token = $this->jwt->attempt($credentials)) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'invalid_email_or_password',
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'failed_to_create_token',
            ]);
        }
        return response()->json([
            'response' => 'success',
            'result' => [
                'token' => $token,
                'message' => 'I am front user',
            ],
        ]);
    }

    public function adminLogin(Request $request){
        Config::set('jwt.user', 'App\Admin');
        Config::set('auth.providers.users.model', \App\Dosen::class);
        $credentials = $request->only('email', 'sandi');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'invalid_email_or_password',
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'failed_to_create_token',
            ]);
        }
        return response()->json([
            'response' => 'success',
            'result' => [
                'token' => $token,
                'message' => 'I am Admin user',
            ],
        ]);
    }
}