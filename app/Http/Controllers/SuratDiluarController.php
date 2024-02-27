<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuratDiluar;
use App\Models\Surat;
use App\Models\Users;
use File, Auth, Redirect, Validator, DB, PDF;

/*`id_surat_diluar`, `id_user`, `tanggal`, `nama_tempat`, `alamat_tempat`, `nomor_str`, `tanggal_berlaku_str`, `status_aktif` FROM `surat_diluar`*/

class SuratDiluarController extends Controller{
	public function index(Request $request){
		return view('SuratDiluar.surat_diluar');
	}

	public function create(Request $request){
		$data['surat_diluar'] 	= SuratDiluar::find($request->id);
		$content = view('SuratDiluar.add_surat_diluar', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function daftar_sip_diluar(Request $request){
		$data['surat_diluar'] 	= SuratDiluar::where('id_user', $request->id)->get();
		$data['surat_didalam'] = Surat::where('id_user', $request->id)->get();
		$data['users'] = Users::find($request->id);
		$content = view('SuratDiluar.daftar_surat_diluar', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		$SuratDiluar = isset($request->id_surat_diluar) ? SuratDiluar::find($request->id_surat_diluar) : new SuratDiluar();

		$SuratDiluar->id_user 				= Auth::getUser()->id;
		$SuratDiluar->sip_ke 				= $request->sip_ke;
		$SuratDiluar->nama_tempat 			= strip_tags(strtoupper($request->nama_tempat));
		$SuratDiluar->alamat_tempat 		= strip_tags(strtoupper($request->alamat_tempat));
		$SuratDiluar->nomor_str 			= strip_tags($request->nomor_str);
		$SuratDiluar->tanggal_berlaku_str 	= strtoupper($request->tanggal_berlaku_str);
		$SuratDiluar->status_aktif 			= $request->status_aktif; 

		$saved = $SuratDiluar->save();
		if ($saved) {
			return Redirect::route('surat_diluar')
										->with('title', 'Berhasil !')
										->with('message', 'Data berhasil ditambahkan')
										->with('type', 'success');
		}else{
			return Redirect::route('surat_diluar')
										->with('title', 'Gagal !')
										->with('message', 'Data gagal ditambahkan')
										->with('type', 'error');
		}
	}

	public function edit(Request $request){
		# code...
	}

	public function delete(Request $request){
		$res = SuratDiluar::where('id_surat_diluar', $request->id)->delete();
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

	public function datagrid(Request $request){
      $data = SuratDiluar::getJson($request);
      return $data;
    }
}
