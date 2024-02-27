<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Auth, Redirect;

class MasterKecamatanController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.MasterKecamatan.masterKecamatan');
	}

  public function create(Request $request){
    $data['kecamatan'] 	= Kecamatan::find($request->id);
    $data['provinsi'] 	= Provinsi::get();
    $data['kabupaten'] 	= Kabupaten::get();

    if (isset($request->id) && $request->id == 0) {
      $data['title']	= 'Tambah';
    }else{
      $data['title'] 	= 'Ubah';
    }
    $content = view('MasterData.MasterKecamatan.add_kecamatan', $data)->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function store(Request $request){
    // return $request->all();
    $kecamatan = isset($request->id_kecamatan) ? Kecamatan::find($request->id_kecamatan) : new Kecamatan();

    if (isset($request->kecamatan)) {
      $kecamatan->id_kecamatan  = $request->id_kecamatan;      
    }else{
      $cekIdTerakhir = Kecamatan::where('id_kabupaten',$request->kabupaten_id)->orderBy('id_kecamatan', 'desc')->first();
      if (!empty($cekIdTerakhir)) {
        $kecamatan->id_kecamatan = $cekIdTerakhir->id_kecamatan+1;      
      }else{
        $kecamatan->id_kecamatan = $request->kabupaten_id.'001';
      }      
    }    

    $kecamatan->nama_kecamatan = strip_tags(strtoupper($request->nama_kecamatan));
    $kecamatan->id_kabupaten = $request->kabupaten_id;

    $kecamatan->save();
    if ($kecamatan) {
     if (isset($request->id_kecamatan) && $request->id_kecamatan == 0) {
        return Redirect::route('master_kecamatan')
                    ->with('title', 'Berhasil !')
                    ->with('message', 'Data berhasil ditambahkan')
                    ->with('type', 'success');
      }else{
        return Redirect::route('master_kecamatan')
                    ->with('title', 'Berhasil !')
                    ->with('message', 'Data berhasil diubah')
                    ->with('type', 'success');
      }  
    }else{
      return Redirect::route('master_kecamatan')
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
	  $data = Kecamatan::getJson($request);
      return $data;
	}
}
