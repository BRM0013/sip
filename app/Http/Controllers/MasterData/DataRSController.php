<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Libraries\CompressFile;

use App\Models\Users;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\MasterData\LevelUsers;
use App\Models\MasterData\Fasyankes;
use App\Models\MasterData\PendidikanTerakhir;
use App\Models\MasterData\JenisSurat;

use File, Auth, Redirect, Validator, DB;

class DataRSController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        return view('MasterData.DataRS.main');
    }

    public function datagrid(Request $request){
      $data = Users::getJsonRS($request);
      return $data;
    }

    public function create(Request $request){      
    $data['list_provinsi']            = Provinsi::all();
    $data['list_level_user']          = LevelUsers::all();
    $data['list_pendidikan_terakhir'] = PendidikanTerakhir::all();
    $data['list_kabupaten']           = Kabupaten::all();
    $data['list_fasyankes']           = Fasyankes::all();
    $data['users']                    = isset($setting) ? Users::find(Auth::getUser()->id) : Users::find($request->id);
    $data['setting']                  = isset($setting) ? 'setting' : '';

    if (isset($setting)) {
      $data['mn_active'] = "pengaturan_datars";
      $data['title']                  = 'Pengaturan';
    }else if(isset($request->id)){
      $data['title']                  = 'Ubah Data RS';
    }else{
      $data['title']                  = 'Tambah RS';
    }

    return view('MasterData.DataRS.add', $data);
  }

  public function update_setting(Request $request,$setting){      
    $data['list_provinsi']            = Provinsi::all();
    $data['list_level_user']          = LevelUsers::all();
    $data['list_pendidikan_terakhir'] = PendidikanTerakhir::all();
    $data['list_kabupaten']           = Kabupaten::all();
    $data['users']                    = isset($setting) ? Users::find(Auth::getUser()->id) : Users::find($request->id);
    $data['setting']                  = isset($setting) ? 'setting' : '';
    $data['list_fasyankes']           = Fasyankes::all();

    if (isset($setting)) {
      $data['mn_active'] = "pengaturan_datars";
      $data['title']                  = 'Pengaturan';
    }else if(isset($request->id)){
      $data['title']                  = 'Ubah Data RS';
    }else{
      $data['title']                  = 'Tambah RS';
    }

    return view('MasterData.DataRS.add', $data);
  }

  public function store(Request $request){
    $users = isset($request->id) ? Users::find($request->id) : new Users();
    $users->id                     = $request->id;
    if(isset($request->level_user)) {$users->id_level_user           = $request->level_user;}
    $users->id_level_user          = 7;    
    $users->name                   = strtoupper($request->name);    
    $users->email                  = $request->email;
    $users->alamat_domisili        = strip_tags(strtoupper($request->alamat_domisili));
    $users->nomor_telpon           = $request->nomor_telpon;
    $users->status_verifikasi      = 'Terverifikasi';
    $users->fasyankes_id           = $request->fasyankes_id;

    if (!empty($request->password)) { 
        $users->password                = Hash::make($request->password);
    }
    $saved = $users->save();

      if ($saved) {      
        return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/master/data_rs/setting' : 'home/master/data_rs')      
                      ->with('title', 'Berhasil !')
                      ->with('message', 'Data berhasil ditambahkan')
                      ->with('type', 'success');
      }else{
        return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/master/data_rs/setting' : 'home/master/data_rs')            
                      ->with('title', 'Gagal !')
                      ->with('message', 'Data gagal ditambahkan')
                      ->with('type', 'error');
      }
    }

  public function delete(Request $request){
    $res = Users::where('id', $request->id)->delete();
    if($res){
      $return = [
        'status'=>'success',
        'message'=>'Data berhasil dihapus!'
      ];
    }else{
      $return = [
        'status'=>'error',
        'message'=>'Data gagal dihapus!'
      ];
    }
    return $return;
  }

}
