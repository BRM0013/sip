<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Users;
use App\Http\Libraries\Whatsapp;
use Illuminate\Support\Facades\Hash;
use Redirect;

class ManualGuideController extends Controller{
    public function index(){
        return view('auth.manual_guide');
    }

    public function cek_sip()
    {
        return view('auth.cek_sip');
    }

    function data(Request $request){
        // return $request->all();
        $jenis_input = strip_tags($request->jenis_input);
        $request_input = strip_tags($request->request_input);

        if($jenis_input=='nama_pemohon'){            
            $columns = 'users.name';
            $opr = 'like';
            $value = '%'.$request_input.'%';
        }else{
            $columns = 'surat.nama_tempat_praktik';
            $opr = 'like';
            $value = '%'.$request_input.'%';
        }    
        
        $surat = Surat::select('id_surat','status_aktif')
                        ->join('users','surat.id_user','users.id')
                        ->where($columns,$opr,$value)
                        // ->where('status_aktif','Sudah Diambil')
                        ->orderBy('tanggal_disetujui_kabid','ASC')
                        ->where('surat.status_simpan','!=','draf')
                        ->get();        
        return ['surat'=>$surat];        
    }

    function baris(Request $request){
        // return $request->all();

        $id_surat = $request->id;
        $index = $request->index;
        $status_aktif = $request->status_aktif;

        $surat = Surat::selectRaw("*,jns.nomor_surat as no_js_surat,surat.nomor_surat,surat.status_aktif as status_aktif")
        ->leftjoin('users as u','u.id','surat.id_user')
        ->leftjoin('jenis_surat as jns','jns.id_jenis_surat','surat.id_jenis_surat')
        ->leftjoin('jenis_sarana as js','js.id_jenis_sarana','surat.id_jenis_sarana')
        ->leftjoin('pendidikan_terakhir as pt','pt.id_pendidikan_terakhir','u.id_pendidikan_terakhir')
        ->leftjoin('desa as d','d.id_desa','u.id_desa')
        ->leftjoin('kecamatan as kec','kec.id_kecamatan','u.id_kecamatan')
        ->leftjoin('kabupaten as kab','kab.id_kabupaten','u.id_kabupaten')
        ->leftjoin('provinsi as prov','prov.id_provinsi','u.id_provinsi')
        ->where('id_surat',$id_surat)        
        ->first();
        // return $surat;
        $data = [
            'surat'=>$surat,
            'index'=>$index,
        ];
        $content = view('auth.baris_sip',$data)->render();

        return $content;
    }

    public function getKTP(Request $request)
    {
        $no_ktp = str_replace(' ', '', $request->no_ktp);
        $user = Users::select('nomor_ktp')->where('nomor_ktp', $no_ktp)->where('id', '!=', $request->id)->first();      
          
          if (!empty($user)) {
            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Ditemukan !!', 'data' => $user];
          }else{
            $return = ['status' => 'empty', 'code' => '404', 'message' => 'Data Tidak Ditemukan !!', 'data' => ''];
          }
          return response()->json($return);
    }

    public function getWA(Request $request)
    {
        $no_wa = str_replace(' ', '', $request->no_wa);
        $user = Users::select('nomor_telpon')->where('nomor_telpon', $no_wa)->where('id', '!=', $request->id)->first();      
          
          if (!empty($user)) {
            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Ditemukan !!', 'data' => $user];
          }else{
            $return = ['status' => 'empty', 'code' => '404', 'message' => 'Data Tidak Ditemukan !!', 'data' => ''];
          }
          return response()->json($return);
    }

    public function reset_password(Request $request)
    {
        return view('auth.reset_password');
    }

    public function cek_resetwa(Request $request)
    {                        
        $no_wa = str_replace(' ', '', $request->no_wa);
        $user = Users::where('nomor_telpon', $no_wa)
        ->where('email',$request->email)        
        ->first();      
          
          if (!empty($user)) {
            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Ditemukan !!', 'data' => $user];
          }else{
            $return = ['status' => 'error', 'code' => '404', 'message' => 'Data Tidak Ditemukan !!', 'data' => ''];
          }
          return response()->json($return);        
    }

    public function store_resetpassword(Request $request)
    {
        $getUser = Users::where('nomor_telpon',$request->no_wa)->first();
        if (!empty($getUser)) {
            $data['id'] = $getUser->id;
            $data['no_wa'] = $getUser->nomor_telpon;

            $result =  Whatsapp::verifikasi_resetWA($data['no_wa'],$data['id']);
            if ($result) {
                return Redirect::route('home')
                                  ->with('title', 'Berhasil !')
                                  ->with('message', 'Reset Berhasil Diperbaharui')
                                  ->with('type', 'success');;                
            }
        }   
    }

    public function verifikasi_reset(Request $request){
        $id = $request->id;
        $users = Users::find($id);            
        // return $users;
        return view('auth.form_reset',compact('users'));                
    }

    public function store_reset_password(Request $request)
    {
        $getUser = Users::find($request->id);
        if (!empty($getUser)) {
            $getUser->password = Hash::make($request->password);
            $getUser->save();
            
            if ($getUser) {
                return Redirect::route('home')
                              ->with('title', 'Berhasil !')
                              ->with('message', 'Reset Berhasil Diperbaharui')
                              ->with('type', 'success');
            }
        }        
    }
}
