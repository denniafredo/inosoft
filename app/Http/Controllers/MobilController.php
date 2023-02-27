<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Services\MobilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MobilController extends Controller
{
    public function index(MobilService $mobilService) : JsonResponse 
    {
        $mobil = $mobilService->findAll();
        
        return response()->json(
            [
                'data' => $mobil
            ], Response::HTTP_OK);    
    }

    public function store(Request $request, MobilService $mobilService) : JsonResponse
    {
        try{
            $mobil = $mobilService->create($this->validation($request));
            return response()->json(
                [
                    'data' => $mobil
                ], Response::HTTP_CREATED);
        }
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }       
    }

    public function show($id,MobilService $mobilService) : JsonResponse
    {
        $mobil = $mobilService->findById($id);
        
        return response()->json(
            [
                'data' => $mobil
            ], Response::HTTP_OK);  
    }

    public function update($id,Request $request, MobilService $mobilService):JsonResponse
    {
        $mobil = $mobilService->updateById($id,$this->validation($request));
        if($mobil->errorCode){
            return response()->json(
                [
                    'message' => "Mobil Not Found",
                    'data' => []
                ], Response::HTTP_NOT_FOUND); 
        }
        return response()->json(
            [
                'data' => $mobil
            ], Response::HTTP_OK);   
    }

    public function destroy($id,MobilService $mobilService) : JsonResponse
    {
        $mobil = $mobilService->deleteById($id);
        if($mobil->errorCode){
            return response()->json(
                [
                    'message' => "Mobil Not Found",
                    'data' => []
                ], Response::HTTP_NOT_FOUND); 
        }
        return response()->json(
            [
                'data' => $mobil
            ], Response::HTTP_OK);  
    }

    public function rules()
    {
        return [
            'tahun_keluaran' => 'required|numeric',
            'warna' => 'required|string',
            'harga' => 'required|numeric',
            'mesin' => 'required|string',
            'tipe_suspensi' => 'required|string',
            'tipe_transmisi' => 'required|string',
        ];
    }

    public function validation($request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if($validator->fails()){
            return response()->json(
                $validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $request->all();
    }
}
