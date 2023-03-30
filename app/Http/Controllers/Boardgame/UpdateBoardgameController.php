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
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use App\Events\Boardgame\UpdateBoardgameEvent;

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
    public function __invoke(UpdateBoardgameRequest $request, $boardgame_id)
    {
        //check boardgame_id
        $validator = Validator::make(["id"=>$boardgame_id], [
            "id" => 'required|integer|exists:boardgames,id',
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
            DB::beginTransaction();
            $boardgame = Boardgame::findOrFail($boardgame_id);

            $data = [
                'label' => $request->input('label') ?? $boardgame->label,//can not be null
                'description' => $request->input('description')??null,
                'editorial' => $request->input('editorial')??null,
                'min_players' => $request->input('min_players')??null,
                'max_players' => $request->input('max_players')??null,
                'min_age' => $request->input('min_age')??null,
                'max_age' => $request->input('max_age')??null,
            ];
            $boardgame->update($data);

            //m:n relationships
            UpdateBoardgameEvent::dispatch($boardgame->id, $request->tag_ids);
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $boardgame,
                'message' => 'OK',
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
