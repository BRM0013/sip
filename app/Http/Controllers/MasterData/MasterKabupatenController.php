<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use Auth, Redirect;

class MasterKabupatenController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.MasterKabupaten.masterKabupaten');
	}

  public function create(Request $request){
    // return $request->all();
    $data['kabupaten'] 	= Kabupaten::find($request->id);
    $data['provinsi'] 	= Provinsi::get();

    if (isset($request->id) && $request->id == 0) {
      $data['title']	= 'Tambah';
    }else{
      $data['title'] 	= 'Ubah';
    }
    $content = view('MasterData.MasterKabupaten.add_kabupaten', $data)->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function store(Request $request){
    // return $request->all();
    $data_format = isset($request->id_kabupaten) ? Kabupaten::find($request->id_kabupaten) : new Kabupaten();
    
    if (isset($request->id_kabupaten)) {
      $data_format->id_kabupaten  = $request->id_kabupaten;      
    }else{
      $cekIdTerakhir = Kabupaten::where('id_provinsi',$request->provinsi_id)->orderBy('id_kabupaten', 'desc')->first();
      $data_format->id_kabupaten  = $cekIdTerakhir->id_kabupaten+1;
    }    
    
    $data_format->nama_kabupaten  = strip_tags(strtoupper($request->nama_kabupaten));
    $data_format->id_provinsi  = strip_tags(strtoupper($request->provinsi_id));    
    $saved = $data_format->save();    
    if ($saved) {
      if (isset($request->id_kabupaten) && $request->id_kabupaten == 0) {
        return Redirect::route('master_kabupaten')
                    ->with('title', 'Berhasil !')
                    ->with('message', 'Data berhasil ditambahkan')
                    ->with('type', 'success');
      }else{
        return Redirect::route('master_kabupaten')
                    ->with('title', 'Berhasil !')
                    ->with('message', 'Data berhasil diubah')
                    ->with('type', 'success');
      }     
    }else{
      return Redirect::route('master_kabupaten')
                    ->with('title', 'Gagal !')
                    ->with('message', 'Data gagal ditambahkan')
                    ->with('type', 'error');
    }   
  }

  public function delete(Request $request){
    // return $request->all();
    $delete = Kabupaten::where('id_kabupaten', $request->id)->delete();
    if($delete){
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

	public function datagrid(Request $request){
	  $data = Kabupaten::getJson($request);
      return $data;
	}
}
