<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\JenisPraktik;

use File, Auth, Redirect, Validator, DB, PDF;

class JenisPraktikController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		$data['mn_active'] = "master";
		$data['submn_active'] = "jenis_praktik";
		return view('MasterData.JenisPraktik.jenis_praktik', $data);
	}

	public function create(Request $request){
		$data['jpraktik'] 	= JenisPraktik::find($request->id);

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}

		$content = view('MasterData.JenisPraktik.add_jenis_praktik', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		$JenisPraktik = isset($request->id_jenis_praktik) ? JenisPraktik::find($request->id_jenis_praktik) : new JenisPraktik();
		
		$JenisPraktik->nama_jenis_praktik 	= strip_tags(strtotime($request->nama_jenis_praktik));
		$JenisPraktik->nama_variable 		= str_replace(')', '', str_replace('(', '', str_replace(' ', '_', strtolower($request->nama_jenis_praktik))));

		$saved = $JenisPraktik->save();
		if ($saved) {
			return Redirect::route('jenis_praktik')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('jenis_praktik')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function edit(Request $request){

	}

	public function delete(Request $request){
		$res = JenisPraktik::where('id_jenis_praktik', $request->id)->delete();
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
	  $data = JenisPraktik::getJson($request);
      return $data;
	}
}
