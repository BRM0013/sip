<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\Jabatan;

use File, Auth, Redirect, Validator, DB, PDF;

class JabatanController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		$data['mn_active'] = "master";
		$data['submn_active'] = "jabatan";
		return view('MasterData.Jabatan.jabatan', $data);
	}

	public function create(Request $request){
		$data['jjabatan'] 	= Jabatan::find($request->id);

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}

		$content = view('MasterData.Jabatan.add_jabatan', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		$jabatan = isset($request->id_jabatan) ? Jabatan::find($request->id_jabatan) : new Jabatan();

		$jabatan->jabatan 	= strip_tags(strtoupper($request->jabatan));

		$saved = $jabatan->save();
		if ($saved) {
			return Redirect::route('jabatan')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('jabatan')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function edit(Request $request){

	}

	public function delete(Request $request){
		$res = Jabatan::where('id_jabatan', $request->id)->delete();
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
	  $data = Jabatan::getJson($request);
      return $data;
	}
}
