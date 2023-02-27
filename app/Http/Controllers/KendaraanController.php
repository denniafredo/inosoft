<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Services\KendaraanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    public function stock(KendaraanService $kendaraanService) 
    {
        $stocks = $kendaraanService->getStock();
        return response()->json(
            [
                'data' => $stocks
            ], Response::HTTP_OK);   
    }

    public function validation($request)
    {
        $rules = new KendaraanService;
        $validator = Validator::make($request->all(), $rules->getRules());
        if($validator->fails()){
            return response()->json(
                [
                    'error'=> $validator->errors(),
                ],    
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json(
            $request->all(),    
        Response::HTTP_OK);
    }
}
