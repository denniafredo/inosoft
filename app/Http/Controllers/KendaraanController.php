<?php

namespace App\Http\Controllers;

use App\Services\KendaraanService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
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
