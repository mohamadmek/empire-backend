<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\BaseController as BaseController;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Email and password required', 500, $validator->errors());
            }

            $email = $request->email;
            $password = $request->password;

            if (User::where('email', $email)->doesntExist()) {
                User::create([
                    "email" => $email,
                    "password" => Hash::make($password),
                    "unit" => "F"
                ]);
                $user = User::with('favorites')->where('email', $email)->first();
                $token = $user->createToken('auth-token');
                $user['auth_token'] = $token->plainTextToken;
                return $this->sendResponse($user, 'Successfully registered'); 
            } else {
                return $this->sendError('Your email already exists', 500);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Email or password is incorrect', 500, $validator->errors());
            }

            $user = User::with('favorites')->where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                return $this->sendError('password is incorrect', 500, ['password' => "Password is incorrect"]);
            }
            $token = $user->createToken('auth-token');
            $user['auth_token'] = $token->plainTextToken;
            return $this->sendResponse($user, 'Logged in successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->currentAccessToken()->delete();
            return $this->sendResponse([], 'Successfully Logged out');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'unit' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Unit is required', 500, $validator->errors());
            }

            $user = Auth::user();
            $user->unit = $request->unit;
            $user->update();
            return $this->sendResponse([], 'Successfully Updated!');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    public function getUserWithToken()
    {
        try {
            $user = Auth::user();
            $user = User::with('favorites')->where('id', $user->id)->first();
            return $this->sendResponse($user, 'Returned successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

}
