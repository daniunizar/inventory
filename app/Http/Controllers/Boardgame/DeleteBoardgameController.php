<?php

namespace App\Http\Controllers\Boardgame;

use App\Http\Controllers\Controller;
use App\Models\Boardgame;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

/**
 * This function return all boardgames of the user gotten by param
 */
class DeleteBoardgameController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $boardgame_id)
    {
        $validator = Validator::make(["boardgame_id"=>$boardgame_id], [
            "boardgame_id" => 'required|integer|exists:boardgames,id',
        ]);
 
        if ($validator->fails()) {
            Log::debug("Error en la validacion");
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => '',
                'data'      => $validator->errors()
            ], 422));
        }
        try{
            $boardgame = Boardgame::findOrFail($boardgame_id);

            Gate::authorize('delete-boardgame', $boardgame); //this sentence do code flow continues if logged user is authorized, and throws exception 403 Unauthorized if logged user is unauthorized and is catched by Catch(Exception $e)

            $boardgame->delete();
            return response()->json([
                'success' => true,
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
