<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Provinsi;
use Auth, Redirect;

class MasterProvinsiController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.MasterProvinsi.masterProvinsi');
	}

  public function create(Request $request){
		$data['provinsi'] 	= Provinsi::find($request->id);
    $data['id_terakhir'] = Provinsi::select('id_provinsi')->orderBy('id_provinsi', 'desc')->first();

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}
		$content = view('MasterData.MasterProvinsi.add_provinsi', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		// $provinsi = isset($request->id_provinsi) ? Provinsi::find($request->id_provinsi) : new Provinsi();
    $cek = Provinsi::where('id_provinsi',$request->id_provinsi)->first();
    if (!empty($cek)) {
      $provinsi = Provinsi::find($request->id_provinsi);
    }else {
      $provinsi = New Provinsi;
    }
    $provinsi->id_provinsi = $request->id_provinsi;
    $provinsi->nama_provinsi = strip_tags(strtoupper($request->nama_provinsi));
    $provinsi->save();
		if ($provinsi) {
			return Redirect::route('master_provinsi')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('master_provinsi')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function delete(Request $request){
	    // return $request->all();
	    $delete = Provinsi::where('id_provinsi', $request->id)->delete();
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
	  $data = Provinsi::getJson($request);
      return $data;
	}
}
