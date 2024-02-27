<?php

namespace App\Http\Controllers;

use App\Models\LogAktifitas;
use App\Models\Surat;
use App\Models\MasterData\JenisSurat;

use Illuminate\Http\Request;

use DB;

class LaporanController extends Controller{
	public function index(){
		$data['mn_active'] = "laporan-admin";
		$jenis = DB::table('jenis_surat')->orderBy('urutan','ASC')->get();
		$data['jenis'] = $jenis;
		return view('Laporan.main', $data);
	}

	function data(Request $request){
		// return $request->all();
		$akhir = $request->akhir;
		$mulai = $request->mulai;
		$profesi = $request->profesi;
		$status = $request->status;

		$mulai_format = date('d-m-Y',strtotime($request->mulai));
		$akhir_format = date('d-m-Y',strtotime($request->akhir));

		if($profesi=='All'){
			$where = "tanggal_disetujui_kabid >= '$mulai 00:00:00' AND tanggal_disetujui_kabid <= '$akhir 23:59:59'";
		}else{
			$where = "tanggal_disetujui_kabid >= '$mulai 00:00:00' AND tanggal_disetujui_kabid <= '$akhir 23:59:59' AND id_jenis_surat='$profesi'";
		}

		if ($status=='All') {
			$whereStatus = "tanggal_disetujui_kabid >= '$mulai 00:00:00' AND tanggal_disetujui_kabid <= '$akhir 23:59:59'";
		}else{
			$whereStatus = "status_aktif='$status'";
		}

		$surat = Surat::select('id_surat','status_aktif')->whereRaw($where)->whereRaw($whereStatus)->orderBy('tanggal_disetujui_kabid','ASC')->get();

		if ($request->profesi=="All") {
			$jp = "SEMUA PROFESI";
		}else{
			$jp = JenisSurat::select('nama_surat')->where('id_jenis_surat',$request->profesi)->first();
		}
		
		return ['surat'=>$surat,'lokasi_bulan'=>$mulai_format.' '.'sampai'.' '.$akhir_format,'profesi'=>$jp];
		// return $surat;
	}

	function baris(Request $request){
		$id_surat = $request->id;
		$index = $request->index;
		$status_aktif = $request->status_aktif;

		$surat = Surat::selectRaw("*,jns.nomor_surat as no_js_surat,surat.nomor_surat")
		->leftjoin('users as u','u.id','surat.id_user')
		->leftjoin('jenis_surat as jns','jns.id_jenis_surat','surat.id_jenis_surat')
		->leftjoin('jenis_sarana as js','js.id_jenis_sarana','surat.id_jenis_sarana')
		->leftjoin('pendidikan_terakhir as pt','pt.id_pendidikan_terakhir','u.id_pendidikan_terakhir')
		->leftjoin('desa as d','d.id_desa','u.id_desa')
		->leftjoin('kecamatan as kec','kec.id_kecamatan','u.id_kecamatan')
		->leftjoin('kabupaten as kab','kab.id_kabupaten','u.id_kabupaten')
		->leftjoin('provinsi as prov','prov.id_provinsi','u.id_provinsi')
		->where('id_surat',$id_surat)->first();
		// return $surat;
		$data = [
			'surat'=>$surat,
			'index'=>$index,
		];

		// return $data;
		$content = view('Laporan.baris',$data)->render();
		return $content;
	}
}
