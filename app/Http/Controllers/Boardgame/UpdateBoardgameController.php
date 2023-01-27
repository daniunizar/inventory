<?php

namespace App\Http\Controllers\Boardgame;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boardgame\UpdateBoardgameRequest;
use App\Models\Boardgame;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

/**
 * This function return all boardgames of the user gotten by param
 */
class UpdateBoardgameController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateBoardgameRequest $request)
    {
        try{
            $boardgame = Boardgame::findOrFail($request->input('id'));

            $data = [
                'label' => $request->input('label') ?? $boardgame->label,
                'description' => $request->input('description')??null,
                'editorial' => $request->input('editorial')??null,
                'min_players' => $request->input('min_players')??null,
                'max_players' => $request->input('max_players')??null,
            ];

            $boardgame->update($data);
        
            return response()->json([
                'success' => true,
                'data' => $boardgame,
                'message' => 'OK',
            ], 200);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
