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

    public function store(Request $request, KendaraanService $kendaraanService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $kendaraan = $kendaraanService->create($request);
            return response()->json(
                [
                    'data' => $kendaraan
                ], Response::HTTP_CREATED);
        }
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }       
    }

    public function show($id,KendaraanService $kendaraanService)
    {
        if($kendaraan = $kendaraanService->findById($id)){
            $kendaraan = $kendaraanService->recompileData($kendaraan);
            return response()->json(
                [
                    'data' => $kendaraan
                ], Response::HTTP_OK);  
        }
        else{
            return response()->json(
                [
                    'message' => 'Kendaraan Not Found',
                    'data' => '',
                ], Response::HTTP_NOT_FOUND);  
        }
    }

    public function update($id,Request $request, KendaraanService $kendaraanService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $kendaraan = $kendaraanService->updateById($id,$request);
            if(!$kendaraan){
                return response()->json(
                    [
                        'message' => "Kendaraan Not Found",
                        'data' => []
                    ], Response::HTTP_NOT_FOUND); 
            }
            return response()->json(
                [
                    'data' => $kendaraan
                ], Response::HTTP_OK); 
        }
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }    
    }

    public function destroy($id,KendaraanService $kendaraanService)
    {
        $kendaraan = $kendaraanService->deleteById($id);
        if(!$kendaraan){
            return response()->json(
                [
                    'message' => "Kendaraan Not Found",
                    'data' => []
                ], Response::HTTP_NOT_FOUND); 
        }
        return response()->json(
            [
                'data' => $kendaraan
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
