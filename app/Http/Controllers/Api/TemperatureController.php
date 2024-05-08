<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Temperature;

class TemperatureController extends Controller
{
    public function store(Request $request){
        $data = $request -> temperature;
        $temperature = Temperature::create([
          "temperature" => $data
        ]);
        return response()->json(
            ["status"=>201,"message"=>"Temperature berhasil disimpan"],201
        );
    }
    public function index()
    {
      $data = DB::table('temperature')->orderBy('id','desc')->get();
      return response()->json(
      [
        "status"=>200,
        "data"=>$data
      ],200);
    }

}
