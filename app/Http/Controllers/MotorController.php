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
    public function index(MotorService $motorService) 
    {
        $motors = $motorService->findAll();
        if($motors){
            $motorCollection = [];
            foreach($motors as $motor){
                $motorCollection[] = $motorService->recompileData($motor);
            }
            return response()->json(
                [
                    'data' => $motorCollection
                ], Response::HTTP_OK);   
        }
        return response()->json(
            [
                'message' => 'Motor Not Found',
                'data' => ''
            ], Response::HTTP_NOT_FOUND);  
    }

    public function store(Request $request, MotorService $motorService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $motor = $motorService->create($request);
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

    public function show($id,MotorService $motorService)
    {
        if($motor = $motorService->findById($id)){
            $motor = $motorService->recompileData($motor);
            return response()->json(
                [
                    'data' => $motor
                ], Response::HTTP_OK);  
        }
        else{
            return response()->json(
                [
                    'message' => 'Motor Not Found',
                    'data' => '',
                ], Response::HTTP_NOT_FOUND);  
        }
    }

    public function update($id,Request $request, MotorService $motorService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $motor = $motorService->updateById($id,$request);
            if(!$motor){
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
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }    
    }

    public function destroy($id,MotorService $motorService)
    {
        $motor = $motorService->deleteById($id);
        if(!$motor){
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

    

    public function validation($request)
    {
        $rules = new MotorService;
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
