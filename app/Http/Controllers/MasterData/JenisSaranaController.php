<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\JenisSarana;

use File, Auth, Redirect, Validator, DB, PDF;

class JenisSaranaController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.JenisSarana.jenis_sarana');
	}

	public function create(Request $request){
		$data['jsarana'] = JenisSarana::find($request->id);

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}

		$content = view('MasterData.JenisSarana.add_jenis_sarana', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		$JenisSarana = isset($request->id_jenis_sarana) ? 
					   JenisSarana::find($request->id_jenis_sarana) : new JenisSarana();

		$JenisSarana->nama_sarana 	= strip_tags($request->nama_sarana);

		$saved = $JenisSarana->save();
		if ($saved) {
			return Redirect::route('jenis_sarana')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('jenis_sarana')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function edit(Request $request){

	}

	public function delete(Request $request){
		$res = JenisSarana::where('id_jenis_sarana', $request->id)->delete();
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
	  $data = JenisSarana::getJson($request);
      return $data;
	}
}
