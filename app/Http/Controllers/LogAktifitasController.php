<?php

namespace App\Http\Controllers;

use App\Models\LogAktifitas;

use Illuminate\Http\Request;

class LogAktifitasController extends Controller{
    public function index(){
    	$data['mn_active'] = "log_aktifitas";
        return view('LogAktifitas.log_aktifitas', $data);
    }

    public function datagrid(Request $request){
      $data = LogAktifitas::getJson($request);
      return $data;
    }
}
