<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use File, Auth, Redirect, Validator, DB, PDF;

class ManualGuideController extends Controller{
    public function index(){
        return view('MasterData.ManualGuide.upload_manual_guide');
    }

    public function store(Request $request){
      if(!empty($request->manual_kasi)){
          $ext = $request->file('manual_kasi')->getClientOriginalExtension();
          $filename = 'manual_guide_kasi.pdf';
          $temp_foto = 'upload/manual_guide';
          $proses = $request->file('manual_kasi')->move($temp_foto, $filename);
      }
      
      if(!empty($request->manual_kabid)){
          $ext = $request->file('manual_kabid')->getClientOriginalExtension();
          $filename = 'manual_guide_kabid.pdf';
          $temp_foto = 'upload/manual_guide';
          $proses = $request->file('manual_kabid')->move($temp_foto, $filename);
      }
      
      if(!empty($request->manual_kadinkes)){
          $ext = $request->file('manual_kadinkes')->getClientOriginalExtension();
          $filename = 'manual_guide_kadinkes.pdf';
          $temp_foto = 'upload/manual_guide';
          $proses = $request->file('manual_kadinkes')->move($temp_foto, $filename);
      }
      
      if(!empty($request->manual_pemohon)){
          $ext = $request->file('manual_pemohon')->getClientOriginalExtension();
          $filename = 'manual_guide_pemohon.pdf';
          $temp_foto = 'upload/manual_guide';
          $proses = $request->file('manual_pemohon')->move($temp_foto, $filename);
      }
      
      if(!empty($request->manual_admin)){
          $ext = $request->file('manual_admin')->getClientOriginalExtension();
          $filename = 'manual_guide_admin.pdf';
          $temp_foto = 'upload/manual_guide';
          $proses = $request->file('manual_admin')->move($temp_foto, $filename);
      }
      
      return Redirect::route('upload_manual_guide')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
    }
}
