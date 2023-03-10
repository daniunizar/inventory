<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private User $user;
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            Log::debug("Intentando Loguer");
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'La combinacion de email y contraseña no coincide',
                ], 401);
            }

            $this->user = User::where('email', $request->email)->first();
            
            // Enabled/Disabled user validation
            if($this->user->isDisabled()){
                throw new \ErrorException('El usuario se encuentra actualmente deshabilitado');
            }

            return response()->json([
                'status' => true,
                'message' => 'Usuario autenticado con exito',
                'token' => $this->user->createToken("API TOKEN")->plainTextToken
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {        
        return response()->json(auth()->user());
    }
}