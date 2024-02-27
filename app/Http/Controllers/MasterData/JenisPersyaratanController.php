<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\JenisPersyaratan;

use File, Auth, Redirect, Validator, DB, PDF;

class JenisPersyaratanController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.JenisPersyaratan.jenis_persyaratan');
	}

	public function create(Request $request){
		$data['jpersyaratan'] = JenisPersyaratan::find($request->id);

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}

		$content = view('MasterData.JenisPersyaratan.add_jenis_persyaratan', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		$JenisPersyaratan = isset($request->id_jenis_persyaratan) ?
							JenisPersyaratan::find($request->id_jenis_persyaratan) : new JenisPersyaratan();

		$JenisPersyaratan->nama_variable 			= str_replace(')', '', str_replace('(', '', str_replace(' ', '_', strtolower($request->nama_persyaratan))));
		$JenisPersyaratan->nama_jenis_persyaratan 	= strip_tags($request->nama_persyaratan);
		$JenisPersyaratan->jenis_input				= strip_tags($request->jenis_input);
		$JenisPersyaratan->keterangan_persyaratan 	= strip_tags($request->keterangan_persyaratan);

		$saved = $JenisPersyaratan->save();
		if ($saved) {
			return Redirect::route('jenis_persyaratan')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('jenis_persyaratan')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function edit(Request $request){

	}

	public function delete(Request $request){
		$res = JenisPersyaratan::where('id_jenis_persyaratan', $request->id)->delete();
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

	public function detail(Request $request){

	}

	public function datagrid(Request $request){
	  $data = JenisPersyaratan::getJson($request);
      return $data;
	}
}
