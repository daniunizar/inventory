<?php

namespace App\Http\Controllers\Tag;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Log;

class GetTagListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try{
            $tags = Tag::all();
            return response()->json([
                'success' => true,
                'data' => $tags,
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
