<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use Auth, Redirect;

class MasterDesaController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.MasterDesa.masterDesa');
	}

  public function create(Request $request){
    $data['desa'] 	    = Desa::find($request->id);
    $data['kecamatan'] 	= Kecamatan::get();
    $data['provinsi'] 	= Provinsi::get();
    $data['kabupaten'] 	= Kabupaten::get();

    if (isset($request->id) && $request->id == 0) {
      $data['title']	= 'Tambah';
    }else{
      $data['title'] 	= 'Ubah';
    }
    $content = view('MasterData.MasterDesa.add_desa', $data)->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function store(Request $request){
     // return $request->all();
    $desa = isset($request->id_desa) ? Desa::find($request->id_desa) : new Desa();

    if (isset($request->id_desa)) {
      $desa->id_desa  = $request->id_desa;      
    }else{
      $cekIdTerakhir = Desa::where('id_kecamatan',$request->kecamatan_id)->orderBy('id_desa', 'desc')->first();
      if (!empty($cekIdTerakhir)) {
        $desa->id_desa = $cekIdTerakhir->id_desa+1;      
      }else{
        $desa->id_desa = $request->kecamatan_id.'0001';
      }      
    }    

    $desa->nama_desa = strip_tags(strtoupper($request->nama_desa));
    $desa->id_kecamatan = $request->kecamatan_id;

    $desa->save();
    if ($desa) {
     if (isset($request->id_desa) && $request->id_desa == 0) {
        return Redirect::route('master_desa')
                    ->with('title', 'Berhasil !')
                    ->with('message', 'Data berhasil ditambahkan')
                    ->with('type', 'success');
      }else{
        return Redirect::route('master_desa')
                    ->with('title', 'Berhasil !')
                    ->with('message', 'Data berhasil diubah')
                    ->with('type', 'success');
      }  
    }else{
      return Redirect::route('master_desa')
                    ->with('title', 'Gagal !')
                    ->with('message', 'Data gagal ditambahkan')
                    ->with('type', 'error');
    }    
  }

	public function edit(Request $request){

	}

	public function delete(Request $request){

	}

	public function detail(Request $request){

	}

	public function datagrid(Request $request){
	  $data = Desa::getJson($request);
      return $data;
	}
}
