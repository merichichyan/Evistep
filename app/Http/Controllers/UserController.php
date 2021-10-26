<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function login(LoginRequest $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $result = [
                'name'  => Auth::user()->name,
                'token' => Auth::user()->createToken('auth_token')->plainTextToken
            ];
            return Response::successJson($result, 'User signed in.');
        }
        return Response::errorJson([], 'Unauthorised.');
    }

    public function register(RegisterRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('register_token')->plainTextToken;

        $result = [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return Response::successJson($result, 'User created successfully.');
    }
}
