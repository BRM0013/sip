<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailableTolak;

use App\Models\Surat;
use App\Models\Users;
use App\Models\LogRevisiSurat;
use App\Models\SyaratPengajuan;
use App\Models\MasterData\JenisSurat;
use App\Models\MasterData\JenisPersyaratan;
use App\Models\MasterData\TemplateSurat;
use App\Models\MasterData\JenisSarana;
use App\Models\SuratDiluar;

use App\Http\Libraries\Formatters;
use App\Http\Libraries\Whatsapp;

use File, Auth, Redirect, Validator, DB, PDF;

class SuratAllController extends Controller{
	public function __construct() {
		$this->middleware('auth');
	}

	public function index(Request $request){

		$data['jenis_surat'] = JenisSurat::get();
		$data['list_jenis_praktik'] = DB::select("SELECT * FROM `jenis_praktik`");
		return view('SuratAll.surat', $data);
	}

	function datagrid(Request $request){		
		// return $request;
		$data = Surat::getJsonAll($request);
		return $data;
	}

	public function jadwalkanTanggal(Request $request)
	{
		if (count((array)$request->id) > 0) {

			$error = 0;

			foreach ((array)$request->id as $key => $val) {
				$cek_surat = Surat::where('id_surat',$val)->first();

				if (!empty($cek_surat)) {
					if ($cek_surat->status_aktif != 'Aktif') {
						$error +=1;
					}					
				}
			}

			if ($error > 0) {
				return ['status'=>'error','code'=>250,'content'=>'','message'=>'Status Data yang dipilih harus Proses Tanda Tangan Basah'];
			} else {
				$data['id'] = (array)$request->id;
				$content = view('SuratAll.jadwalkan_tanggal', $data)->render();

				return ['status'=>'success','code'=>200,'content'=>$content];
			}
		} else {
			return ['status'=>'error','code'=>250,'content'=>'','message'=>'Tidak ada data yang Dipilih'];				
		}

	}

	public function simpanJadwalkanTanggal(Request $request)
	{
		$rules = array(
			'tanggal' => 'required',
			'jadwal_keterangan' => 'required'
		);

		$messages = array(
			'required'  => 'Kolom Harus Diisi',
		);
		$validator 	= Validator::make($request->all(), $rules, $messages);
		if (!$validator->fails()) {
			$tanggal = date('Y-m-d', strtotime($request->tanggal));
			foreach ((array)$request->id_surat as $key => $val) {
				$surat = Surat::find($val);
				if (!empty($surat)) {
					$surat->status_aktif = 'Dijadwalkan Tanggal';
					$surat->jadwalkan_tanggal = $tanggal;
					$surat->jadwal_keterangan = $request->jadwal_keterangan;
					$surat->save();

					$c_surat = Surat::find($val);
					$user = Users::find($c_surat->id_user);
					Whatsapp::jadwalkanTanggal($user->nomor_telpon, $user->id, $tanggal, $request->jadwal_keterangan);
				}
			}

			return ['status'=>'success','code'=>200,'data'=>'','message'=>'Berhasil Menjadwalkan Tanggal'];
		} else {
			return $validator->messages();
		}
	}

	public function sudahAmbil(Request $request)
	{

		if (count((array)$request->id) > 0) {

			$error = 0;

			foreach ((array)$request->id as $key => $val) {
				$cek_surat = Surat::where('id_surat',$val)->first();

				if (!empty($cek_surat)) {
					if ($cek_surat->status_aktif == 'Aktif' || $cek_surat->status_aktif == 'Dijadwalkan Tanggal') {

					} else {
						$error +=1;
					}
				}
			}


			if ($error > 0) {
				return ['status'=>'error','code'=>250,'content'=>'','message'=>'Status Data yang dipilih harus Aktif atau (Dijadwalkan Tanggal)'];
			} else {
				$id = (array)$request->id;

				foreach ($id as $key => $val) {
					$surat = Surat::find($val);
					if (!empty($surat)) {
						$surat->status_aktif = 'Sudah Diambil';
						$surat->save();
					}
				}

				return ['status'=>'success','code'=>200,'content'=>'','message'=>'Berhasil Mengubah Status Menjadi Sudah Diambil'];
			}
		} else {
			return ['status'=>'error','code'=>250,'content'=>'','message'=>'Tidak ada data yang Dipilih'];				
		}

	}

	public function batalkan(Request $request)
	{
		if (count((array)$request->id) > 0) {
			// $cek_surat = Surat::where('id_surat',$val)->first();

			if ($request->status == 'Dijadwalkan Tanggal') {
				$data['status'] = ['Proses Tanda Tangan Basah'];
				$data['status_val'] = ['Aktif'];
			} elseif ($request->status == 'Sudah Diambil') {
				$data['status'] = ['Proses Tanda Tangan Basah','Dijadwalkan Tanggal'];
				$data['status_val'] = ['Aktif','Dijadwalkan Tanggal'];
			} else {
				return ['status'=>'error','code'=>250,'content'=>'','message'=>'Untuk saat ini status yang bisa dibatalkan hanya status (Dijadwalkan Tanggal & Sudah Diambil)'];				
			}

			$data['id'] = (array)$request->id;
			$content = view('SuratAll.batalkan', $data)->render();

			return ['status'=>'success','code'=>200,'content'=>$content];
		} else {
			return ['status'=>'error','code'=>250,'content'=>'','message'=>'Tidak ada data yang Dipilih'];				
		}

	}

	public function simpanBatalkan(Request $request)
	{
		// return $request->all();
		$rules = array(
			'status' => 'required',
		);

		$messages = array(
			'required'  => 'Kolom Harus Diisi',
		);
		$validator 	= Validator::make($request->all(), $rules, $messages);
		if (!$validator->fails()) {
			foreach ((array)$request->id_surat as $key => $val) {
				$surat = Surat::find($val);
				if (!empty($surat)) {
					$surat->status_aktif = $request->status;
					$surat->save();
				}

				// $c_surat = Surat::find($val);
				// $user = Users::find($c_surat->id_user);
				// Whatsapp::jadwalkanTanggal($user->nomor_telpon, $user->id, $tanggal, $request->jadwal_keterangan);
			}

			return ['status'=>'success','code'=>200,'data'=>'','message'=>'Berhasil Mengembalikan status Surat'];
		} else {
			return $validator->messages();
		}
	}
}
