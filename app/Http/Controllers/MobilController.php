<?php

namespace App\Http\Controllers;

use App\Services\MobilService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MobilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(MobilService $mobilService) 
    {
        $mobils = $mobilService->findAll();
        if($mobils){
            $mobilCollection = [];
            foreach($mobils as $mobil){
                $mobilCollection[] = $mobilService->recompileData($mobil);
            }
            return response()->json(
                [
                    'data' => $mobilCollection
                ], Response::HTTP_OK);  
        }
        return response()->json(
            [
                'message' => 'Mobil Not Found',
                'data' => '',
            ], Response::HTTP_NOT_FOUND);    
    }

    public function store(Request $request, MobilService $mobilService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $mobil = $mobilService->create($request);
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

    public function show($id,MobilService $mobilService)
    {
        if($mobil = $mobilService->findById($id)){
            $mobil = $mobilService->recompileData($mobil);
            return response()->json(
                [
                    'data' => $mobil
                ], Response::HTTP_OK);  
        }
        else{
            return response()->json(
                [
                    'message' => 'Mobil Not Found',
                    'data' => '',
                ], Response::HTTP_NOT_FOUND);  
        }
    }

    public function update($id,Request $request, MobilService $mobilService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $mobil = $mobilService->updateById($id,$request);
            if(!$mobil){
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
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }    
    }

    public function destroy($id,MobilService $mobilService)
    {
        $mobil = $mobilService->deleteById($id);
        if(!$mobil){
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

    

    public function validation($request)
    {
        $rules = new MobilService;
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
