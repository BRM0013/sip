<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\SyaratPengajuan;


use File, Auth, Redirect, Validator, DB;

class GetController extends Controller{
    function getKabupaten(Request $request){
      $id_provinsi = $request->id;
      $kabupaten = Kabupaten::where('id_provinsi',$id_provinsi)->get();
      
      if(count($kabupaten)!=0){
        $return = [
          'status'=>'success',
          'message'=>'Data ditemukan',
          'data'=>$kabupaten,
        ];
      }else{
        $return = [
          'status'=>'error',
          'message'=>'Data tidak ditemukan',
          'data'=>[],
        ];
      }
      return $return;
    }

    function getKecamatan(Request $request){
      $id_kabupaten = $request->id;
      $kecamatan = Kecamatan::where('id_kabupaten',$id_kabupaten)->get();

      if(count($kecamatan)!=0){
        $return = [
          'status'=>'success',
          'message'=>'Data ditemukan',
          'data'=>$kecamatan,
        ];
      }else{
        $return = [
          'status'=>'error',
          'message'=>'Data tidak ditemukan',
          'data'=>[],
        ];
      }
      return $return;
    }

    function getDesa(Request $request){
      $id_kecamatan = $request->id;
      $desa = Desa::where('id_kecamatan',$id_kecamatan)->get();

      if(count($desa)!=0){
        $return = [
          'status'=>'success',
          'message'=>'Data ditemukan',
          'data'=>$desa,
        ];
      }else{
        $return = [
          'status'=>'error',
          'message'=>'Data tidak ditemukan',
          'data'=>[],
        ];
      }
      return $return;
    }

    function getBerkasPengajuan(Request $request){
      // return $request->all();
    if (isset($request->id_surat)) {
        $data['syarat_pengajuan'] = SyaratPengajuan::where('id_surat', $request->id_surat)->where('id_jenis_persyaratan', $request->id)->first();
      }else {
        $data['syarat_pengajuan'] = SyaratPengajuan::where('id_surat_keterangan', $request->id_surat_keterangan)->where('id_jenis_persyaratan', $request->id)->first();
      }
      $content = view('modal', $data)->render();
      return ['status'=>'success','content'=>$content];
    }

    public function getSearchKabupaten(Request $request){
      $cari = $request->cari;
      $kabupaten = Kabupaten::where('nama_kabupaten','like','%'.$cari.'%')->limit(10)->get();
      return ['status'=>'success','data'=>$kabupaten];
    }
}
