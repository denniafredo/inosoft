<?php

namespace App\Http\Controllers;

use App\Services\TransaksiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function store(Request $request, TransaksiService $transaksiService)
    {
        if($this->validation($request)->status() != Response::HTTP_OK){
            return $this->validation($request);
        }
        try{
            $transaksi = $transaksiService->create($request);
            if(!$transaksi){
                return response()->json(
                [
                    'message' => 'Error : Kendaraan Sudah Terjual!'
                ], Response::HTTP_CONFLICT);
            }
            return response()->json(
                [
                    'data' => $transaksi
                ], Response::HTTP_CREATED);
        }
        catch(\Exception $e){
            return response()->json(
                [
                    'message' => 'Error : '. $e->getMessage()
                ], Response::HTTP_CONFLICT);
        }       
    }
    public function report(TransaksiService $transaksiService)
    {
        $report = $transaksiService->getReport();
        return response()->json(
            [
                'data' => $report
            ], Response::HTTP_OK);
    }
    public function validation($request)
    {
        $rules = new TransaksiService;
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
