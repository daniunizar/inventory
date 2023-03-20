<?php

namespace App\Http\Controllers\Boardgame;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boardgame\StoreBoardgameRequest;
use App\Models\Boardgame;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * This function return all boardgames of the user gotten by param
 */
class StoreBoardgameController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreBoardgameRequest $request)
    {
        try{
            $user = User::findOrFail(Auth::id());
            $data = [
                'label' => $request->input('label'),//can not be null
                'description' => $request->input('description')??null,
                'editorial' => $request->input('editorial')??null,
                'min_players' => $request->input('min_players')??null,
                'max_players' => $request->input('max_players')??null,
                'min_age' => $request->input('min_age')??null,
                'max_age' => $request->input('max_age')??null,
                'user_id' => $user->id,//cant not be null
            ];

            $boardgame = Boardgame::create($data);
        
            return response()->json([
                'success' => true,
                'data' => $boardgame,
                'message' => 'OK',
            ], 201);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
