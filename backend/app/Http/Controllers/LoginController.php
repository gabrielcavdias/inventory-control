<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiResponses;

    public function authenticate(LoginRequest $request)
    {
        /**
         * @var array{email:string,password:string}
         *  */
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return $this->unauthorized([
                'message' => 'Usuário não encontrado.'
            ]);
        }
        $user = User::query()->where('email', $credentials['email'])->first();

        return $this->created([
            'token' => $user->createToken($credentials['email'])->plainTextToken
        ]);
    }
}
