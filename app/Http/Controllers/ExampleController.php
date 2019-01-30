<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class ExampleController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    protected $nim;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'nim'    => 'required|max:255',
            'sandi' => 'required',
        ]);

        try {

            if (! $token = \Illuminate\Support\Facades\Auth::guard('api')->setTTL(5)->attempt($request->only('nim', 'sandi'))) {
                return response()->json(['user_not_found'], 404);
            }else{
                $token = \Illuminate\Support\Facades\Auth::refresh(true, true);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }

    public function loginDosen(Request $request)
    {
        $this->validate($request, [
            'alamat_email'    => 'required|max:255',
            'sandi' => 'required',
        ]);

        try {

            if (! $token = \Illuminate\Support\Facades\Auth::guard('dosen-api')->attempt($request->only('alamat_email', 'sandi'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }

    public function tes()
    {
        try {
            echo "tokken sudah berfungsi";
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }
    }

    public function logout()
    {
        $log = \Illuminate\Support\Facades\Auth::logout(true);
        if ($log)
        {
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }
}