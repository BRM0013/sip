<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\FormatSurat;
use App\Models\MasterData\JenisPersyaratan;
use App\Models\MasterData\JenisSurat;
use App\Models\MasterData\JenisSarana;

use File, Auth, Redirect, Validator, DB, PDF;

class FormatSuratController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		$data['mn_active'] = "master";
		$data['submn_active'] = "format_surat";
		return view('MasterData.FormatSurat.main', $data);
	}

	public function datagrid(Request $request){
	  $data = FormatSurat::getJson($request);
      return $data;
	}

	public function create(Request $request){
		// return $request->all();
		$data['jenis_surat'] 	= JenisSurat::all();
        $data['jenis_persyaratan'] 	= JenisPersyaratan::all();
		// $data['data_format'] 	= FormatSurat::find($request->id);
		$data['data_format'] 	= FormatSurat::join('jenis_surat as jns','jns.id_jenis_surat','format_surat.jenis_surat_id')
											  ->where('id_format_surat',$request->id)->first();

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah';
		}else{
			$data['title'] 	= 'Ubah';
		}

		$content = view('MasterData.FormatSurat.add', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		// return $request->all();
		$data_format =  new FormatSurat();

		$data_format->nama 	= strip_tags(strtoupper($request->nama));
		$data_format->keterangan 	= strip_tags(strtoupper($request->keterangan));
		$data_format->jenis_surat_id 	= strip_tags(strtoupper($request->jenis_surat_id));
        $data_format->id_jenis_persyaratan 	= strip_tags(strtoupper($request->id_jenis_persyaratan));

		if(!empty($request->file('nama_file'))){
	      $ukuran = filesize($request->nama_file);

	      if ($ukuran > 2000000) {
	        return ['status'=>'error','message'=>'Maaf Ukuran lebih dari 2MB!'];
	      }else{
	        $filename               = $request->file('nama_file')->getClientOriginalName();
	        $temp_foto 		        = 'upload/format_surat';
	        $proses                 = $request->file('nama_file')->move($temp_foto, $filename);
	        $data_format->nama_file = $filename;
	      }
	    }
		$saved = $data_format->save();
		if ($saved) {
			if (isset($request->id_format_surat) && $request->id_format_surat == 0) {
				return Redirect::route('format_surat')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
			}else{
				return Redirect::route('format_surat')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil diubah')
										->with('type', 'success');
			}
		}else{
			return Redirect::route('format_surat')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function delete(Request $request){
		// return $request->all();
		$delete = FormatSurat::where('id_format_surat', $request->id)->delete();
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
}
