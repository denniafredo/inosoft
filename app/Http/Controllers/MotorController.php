<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Services\MotorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MotorController extends Controller
{
    public function index(MotorService $motorService) : JsonResponse 
    {
        $motor = $motorService->findAll();
        
        return response()->json(
            [
                'data' => $motor
            ], Response::HTTP_OK);    
    }

    public function store(Request $request, MotorService $motorService) : JsonResponse
    {
        try{
            $motor = $motorService->create($this->validation($request));
            return response()->json(
                [
                    'data' => $motor
                ], Response::HTTP_CREATED);
        }
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }       
    }

    public function show($id,MotorService $motorService) : JsonResponse
    {
        $motor = $motorService->findById($id);
        
        return response()->json(
            [
                'data' => $motor
            ], Response::HTTP_OK);  
    }

    public function update($id,Request $request, MotorService $motorService):JsonResponse
    {
        $motor = $motorService->updateById($id,$this->validation($request));
        if($motor->errorCode){
            return response()->json(
                [
                    'message' => "Motor Not Found",
                    'data' => []
                ], Response::HTTP_NOT_FOUND); 
        }
        return response()->json(
            [
                'data' => $motor
            ], Response::HTTP_OK);   
    }

    public function destroy($id,MotorService $motorService) : JsonResponse
    {
        $motor = $motorService->deleteById($id);
        if($motor->errorCode){
            return response()->json(
                [
                    'message' => "Motor Not Found",
                    'data' => []
                ], Response::HTTP_NOT_FOUND); 
        }
        return response()->json(
            [
                'data' => $motor
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
