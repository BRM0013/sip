<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\PendidikanTerakhir;

use File, Auth, Redirect, Validator, DB, PDF;

class PendidikanTerakhirController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.PendidikanTerakhir.pendidikan_terakhir');
	}

	public function create(Request $request){
		$data['jpendidikan_terakhir'] = PendidikanTerakhir::find($request->id);

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}

		$content = view('MasterData.PendidikanTerakhir.add_pendidikan_terakhir', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		$PendidikanTerakhir = 
							isset($request->id_pendidikan_terakhir) ? 
							PendidikanTerakhir::find($request->id_pendidikan_terakhir) : new PendidikanTerakhir();

		$PendidikanTerakhir->pendidikan_terakhir 	= strip_tags(strtoupper($request->pendidikan_terakhir));
		$PendidikanTerakhir->jenjang 				= strip_tags(strtoupper($request->jenjang));

		$saved = $PendidikanTerakhir->save();
		if ($saved) {
			return Redirect::route('pendidikan_terakhir')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('pendidikan_terakhir')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function edit(Request $request){

	}

	public function delete(Request $request){
		$res = PendidikanTerakhir::where('id_pendidikan_terakhir', $request->id)->delete();
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
	  $data = PendidikanTerakhir::getJson($request);
      return $data;
	}
}
