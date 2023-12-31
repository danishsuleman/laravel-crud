<?php

namespace App\Http\Controllers;

use auth;
use session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request) {
        $loginFields = $request->validate([
            'loginUserName' => 'required',
            'loginPassword' => 'required'
        ]);
        
        if(auth()->attempt(['name' => $loginFields['loginUserName'], 'password' => $loginFields['loginPassword']])){
            $request->session()->regenerate();  
        }


        return redirect("/");
    }
public function logout() {
        auth()->logout();
        return redirect("/");
}
public function register(Request $request) {

        $incomingFields = $request->validate([
            'name' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('users', 'name')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                'min:7',
                'max:255'
            ]
            ]);
            $incomingFields['password'] = bcrypt($incomingFields['password']);
            $user = User::create($incomingFields);
            auth()->login($user);
            return redirect('/');
    }
}
