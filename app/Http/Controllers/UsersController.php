<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;
 
class UsersController extends Controller
{   
     
public function signup(Request $request)
{
  try {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email', 
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

          
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password); 
        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'Registration successful'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Registration failed: ' . $e->getMessage()], 500);
    }
}

public function loginUser(Request $request)
{
    try {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    } catch (\Exception $e) {
        return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 500);
    }
}
   
   
  
    
}

