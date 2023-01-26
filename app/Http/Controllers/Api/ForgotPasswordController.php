<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\ConfirmPasswordResetRequest;
use Carbon\Carbon;
use Exception;

class ForgotPasswordController extends Controller
{
    /**
     * This function send an email for reset your password (if the mail is in our database)
     */
    public function requestPasswordResetToken(ResetPasswordRequest $request): JsonResponse
    {
        $request->validated();

        // Generate the password token
        $token = Str::random(64);

        if(User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->firstOrFail();

            // Remove old requested tokens
            DB::table('password_resets')->where('email', $request->email)->delete();
            // Insert the new token
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            // Send the request email
            $user->sendPasswordResetNotification($token);
        }

        $payload = [
            'success'        => true,
            'message'        => 'Recovery password mail sent',
            'data'		     => [
                'email'      => $request->email,
                'token'      => $token,
                'created_at' => Carbon::now()
            ]
        ];
        return response()->json($payload, 201);
    }

    /**
     * This function change the password in database
     */
    public function handlePasswordResetToken(ConfirmPasswordResetRequest $request)
    {
        $request->validated();

        $result = DB::table('password_resets')->where('token', $request->token)->select('email')->first();
        if ($result) {
            $targetEmail = $result->email;
        }
        else {
            $payload = json_encode([
                'success'   => false,
                'message'   => 'Token not found.',
            ]);

            return response($payload, 404);
        }
        
        try {
            $user = User::where('email', $targetEmail)->firstOrFail();

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $payload = json_encode([
                'success'   => true,
                'message'   => 'Usuario actualizado',
                'data'      => $user
            ]);

            // Remove old requested tokens
            DB::table('password_resets')->where('email', $user->email)->delete();

            return response($payload, 200);
        }
        catch (Exception $e) {
            Log::error("Error en la generacion de la nueva contraseña:");
            $payload = json_encode([
                'success'   => false,
                'message'   => $e->getMessage(),
            ]);
            return response($payload, 400);
        }
    }
}