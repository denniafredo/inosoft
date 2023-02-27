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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MotorService $motorService) : JsonResponse 
    {
        $motor = $motorService->findAll();
        
        return response()->json(
            [
                'data' => $motor
            ], Response::HTTP_OK);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Motor  $motor
     * @return \Illuminate\Http\Response
     */
    public function show($id,MotorService $motorService) : JsonResponse
    {
        $motor = $motorService->findById($id);
        
        return response()->json(
            [
                'data' => $motor
            ], Response::HTTP_OK);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Motor  $motor
     * @return \Illuminate\Http\Response
     */
    public function edit(Motor $motor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Motor  $motor
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Motor  $motor
     * @return \Illuminate\Http\Response
     */
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
