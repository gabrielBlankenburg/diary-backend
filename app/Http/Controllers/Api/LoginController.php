<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Login as LoginRequest;
use App\Http\Requests\Register as RegisterRequest;

use App\User;

class LoginController extends Controller
{
    public function __construct()
    {
    	$this->middleware('guest');
    }

    public function register(RegisterRequest $request)
    {
    	$user = new User();

    	$user->name = $request->input('name');
    	$user->email = $request->input('email');
    	$user->password = Hash::make($request->input('password'));

    	if ($user->save()) {
	        
	        $tokenResult = $user->createToken('Laravel Personal Access Client');

	        return response(
	        	[
	        		'response' => 'User created successfully',
	        		'data' => $user,
	        		'accessToken' => $tokenResult->accessToken,
	    		], 200); 
    	} else {
    		return response(['response' => 'Unable to create a new user']);
    	}
    }

    public function login(LoginRequest $request){ 
        $attempt = Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);
        
        if ($attempt) { 
            $user = Auth::user(); 
            
            $tokenResult =  $user->createToken('MyApp'); 
            
            return response(
	        	[
	        		'response' => 'Logged in',
	        		'accessToken' => $tokenResult->accessToken,
	    		], 200); 
        } else { 
            return response()->json(['response' => 'E-mail and password don\'t match'], 401); 
        } 
    }
}
