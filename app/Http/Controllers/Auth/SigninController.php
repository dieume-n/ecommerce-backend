<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\SigninRequest;
use App\Http\Resources\PrivateUserResource;

class SigninController extends Controller
{
    public function signin(SigninRequest $request)
    {
        if (!$token = Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'errors' => [
                    'email' => ['could not sign you in with those credentials']
                ]
            ], 422);
        }
        return response()->json([
            'token' => $token
        ]);
        // $this->respondWithToken($token);
        // return (new PrivateUserResource($request->user()))
        //     ->additional([
        //         'meta' => [
        //             'token' => $token
        //         ]
        //     ]);
    }
}
