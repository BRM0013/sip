<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\JenisSurat;
use App\Models\MasterData\JenisPersyaratan;
use App\Models\MasterData\JenisPraktik;
use App\Models\MasterData\TemplateSurat;

use File, Auth, Redirect, Validator, DB, PDF;

class JenisSuratController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.JenisSurat.jenis_surat');
	}

	public function create(Request $request){

		// return $request->all();
		
		$data['jsurat'] = JenisSurat::find($request->id);
		$data['jenis_persyaratan'] = JenisPersyaratan::all();
		$data['list_jenis_praktik'] = JenisPraktik::all();

		$ts_izin_praktik = '0';
		if (isset($data['jsurat'])) {
			$ts_izin_praktik = $data['jsurat']->jenis_praktik;
		}
		$data['list_ts_praktik'] = DB::select("SELECT * FROM `jenis_praktik` WHERE `id_jenis_praktik` IN ($ts_izin_praktik)");
		$data['template_surat']  = isset($data['jsurat']) ? TemplateSurat::where('id_jenis_surat', $data['jsurat']->id_jenis_surat)->get() : [];

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'Tambah Jenis Surat';
		}else{
			$data['title'] 	= 'Ubah Jenis Surat';
		}

		return view('MasterData.JenisSurat.add_jenis_surat', $data);
	}

	public function store(Request $request){
		$JenisSurat = isset($request->id_jenis_surat) ? JenisSurat::find($request->id_jenis_surat) : new JenisSurat();
		
    	$countUrutan = JenisSurat::select("urutan")->orderBy('urutan','desc')->first();

		$JenisSurat->nama_surat 					= strip_tags($request->nama_surat);
		$JenisSurat->nomor_surat 					= strip_tags($request->nomor_surat);
		$JenisSurat->maksimal_pengajuan 			= isset($request->maksimal_pengajuan) ? $request->maksimal_pengajuan : '0';
		$JenisSurat->format_str 					= isset($request->format_str) ? $request->format_str : '-';
		$JenisSurat->jenis_praktik 					= isset($request->jenis_praktik) ? join(',', $request->jenis_praktik) : '8';
		$JenisSurat->jenis_waktu_praktik 			= isset($request->jenis_waktu_praktik) ? $request->jenis_waktu_praktik : 'Tanpa Waktu Praktik';
	    $JenisSurat->syarat_pengajuan 				= isset($request->syarat_pengajuan) ? join(',', $request->syarat_pengajuan) : '';
		$JenisSurat->syarat_pencabutan 				= isset($request->syarat_pencabutan) ? join(',', $request->syarat_pencabutan) : '';
		$JenisSurat->syarat_perpanjangan 			= isset($request->syarat_perpanjangan) ? join(',', $request->syarat_perpanjangan) : '';
		$JenisSurat->syarat_pindah_tempat 			= isset($request->syarat_pindah_tempat) ? join(',', $request->syarat_pindah_tempat) : '';

		if ($JenisSurat->urutan == '') {
			$JenisSurat->urutan 						= $countUrutan->urutan + 1;			
		}

		if (!empty($request->paraf_kasi)) {
	        $ext_foto                               = $request->paraf_kasi->getClientOriginalExtension();
	        $filename                               = "paraf-kasi-uploaded-at-".strtolower(str_replace(" ", "-", $request->nama_surat)).'-'.date('Ymd-His').'.'.$ext_foto;
	        $temp_foto                              = 'upload/file_master';
	        $proses                                 = $request->paraf_kasi->move($temp_foto, $filename);
	        $JenisSurat->paraf_kasi 				= $filename;
	    }

	    if (!empty($request->paraf_kabid)) {
	        $ext_foto                               = $request->paraf_kabid->getClientOriginalExtension();
	        $filename                               = "paraf-kabid-uploaded-at-".strtolower(str_replace(" ", "-", $request->nama_surat)).'-'.date('Ymd-His').'.'.$ext_foto;
	        $temp_foto                              = 'upload/file_master';
	        $proses                                 = $request->paraf_kabid->move($temp_foto, $filename);
	        $JenisSurat->paraf_kabid 				= $filename;
	    }

	    if (!empty($request->ttd_kadinkes)) {
	        $ext_foto                               = $request->ttd_kadinkes->getClientOriginalExtension();
	        $filename                               = "ttd-kadinkes-uploaded-at-".strtolower(str_replace(" ", "-", $request->nama_surat)).'-'.date('Ymd-His').'.'.$ext_foto;
	        $temp_foto                              = 'upload/file_master';
	        $proses                                 = $request->ttd_kadinkes->move($temp_foto, $filename);
	        $JenisSurat->ttd_kadinkes 				= $filename;
	    }

	    if (!empty($request->stempel_dinkes)) {
	        $ext_foto                               = $request->stempel_dinkes->getClientOriginalExtension();
	        $filename                               = "stempel-dinkes-uploaded-at-".strtolower(str_replace(" ", "-", $request->nama_surat)).'-'.date('Ymd-His').'.'.$ext_foto;
	        $temp_foto                              = 'upload/file_master';
	        $proses                                 = $request->stempel_dinkes->move($temp_foto, $filename);
	        $JenisSurat->stempel_dinkes 			= $filename;
	    }

		$saved = $JenisSurat->save();

		$ts_izin_praktik = $JenisSurat->jenis_praktik;
		if ($ts_izin_praktik != 0) {
			$JenisPraktik = DB::select("SELECT * FROM `jenis_praktik` WHERE `id_jenis_praktik` IN ($ts_izin_praktik)");

			foreach ($JenisPraktik as $row) {
				$TemplateSurat = !empty(TemplateSurat::where('id_jenis_surat', $JenisSurat->id_jenis_surat)->where('id_jenis_praktik', $row->id_jenis_praktik)->where('jenis', 'PENGAJUAN')->first()) ? TemplateSurat::where('id_jenis_surat', $JenisSurat->id_jenis_surat)->where('id_jenis_praktik', $row->id_jenis_praktik)->where('jenis', 'PENGAJUAN')->first() : new TemplateSurat();

				$TemplateSurat->id_jenis_surat 		= $JenisSurat->id_jenis_surat;
				$TemplateSurat->id_jenis_praktik 	= $row->id_jenis_praktik;
				$TemplateSurat->jenis 				= 'PENGAJUAN';
				$TemplateSurat->template_surat 		= $request[$row->nama_variable];

				$TemplateSurat->save();
			}

			foreach ($JenisPraktik as $row) {
				$TemplateSurat = !empty(TemplateSurat::where('id_jenis_surat', $JenisSurat->id_jenis_surat)->where('id_jenis_praktik', $row->id_jenis_praktik)->where('jenis', 'PENCABUTAN')->first()) ? TemplateSurat::where('id_jenis_surat', $JenisSurat->id_jenis_surat)->where('id_jenis_praktik', $row->id_jenis_praktik)->where('jenis', 'PENCABUTAN')->first() : new TemplateSurat();

				$TemplateSurat->id_jenis_surat 		= $JenisSurat->id_jenis_surat;
				$TemplateSurat->id_jenis_praktik 	= $row->id_jenis_praktik;
				$TemplateSurat->jenis 				= 'PENCABUTAN';
				$TemplateSurat->template_surat 		= $request['pencabutan_'.$row->nama_variable];

				$TemplateSurat->save();
			}
		}

		if ($request->submit == 'Terapkan') {
			return Redirect::to('/home/master/jenis_surat/edit/'.$JenisSurat->id_jenis_surat)
											->with('title', 'Berhasil !')
											->with('message', 'Data berhasil ditambahkan')
											->with('type', 'success');
		}else{
			if ($saved) {
				return Redirect::route('jenis_surat')
											->with('title', 'Berhasil !')
											->with('message', 'Data berhasil ditambahkan')
											->with('type', 'success');
			}else{
				return Redirect::route('jenis_surat')
											->with('title', 'Gagal !')
											->with('message', 'Data gagal ditambahkan')
											->with('type', 'error');
			}
		}
	}

	public function edit(Request $request){

	}

	public function delete(Request $request){
		$res = JenisSurat::where('id_jenis_surat', $request->id)->delete();
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
	  $data = JenisSurat::getJson($request);
      return $data;
	}
}
