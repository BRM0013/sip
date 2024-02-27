<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailableTolak;

use App\Models\Surat;
use App\Models\SuratKeterangan;
use App\Models\Users;
use App\Models\LogRevisiSurat;
use App\Models\SyaratPengajuan;
use App\Models\MasterData\JenisSurat;
use App\Models\MasterData\JenisPersyaratan;
use App\Models\MasterData\TemplateSurat;
use App\Models\MasterData\JenisSarana;
use App\Models\SuratDiluar;

use App\Http\Libraries\Formatters;

use File, Auth, Redirect, Validator, DB, PDF;

class DocumentController extends Controller{
	public function __construct() {
		$this->middleware('auth');
	}

	function main(Request $request){
		$surat = SuratKeterangan::find($request->id);
		$users = Users::find(Auth::getUser()->id);

		$surat->keterangan                 = strtoupper($request->keterangan);
		if ($request->status_verifikasi == "Ditolak") {
			$surat->status_aktif       = 'Ditolak';
		}

		$surat->disetujui_admin          = $request->status_verifikasi;
		$surat->tanggal_disetujui_admin  = date('Y-m-d H:i:s');
		$surat->tanggal_terbit   = date('Y-m-d H:i:s');

		$surat->status_aktif          = 'Aktif';
		return  $this->cetak_doc($surat);
	}

	function cetak_doc($SuratKeterangan){
		$users = Users::find($SuratKeterangan->id_user);
		$JenisSurat = JenisSurat::find($SuratKeterangan->id_jenis_surat);
		$data['content'] = TemplateSurat::where('id_jenis_surat', $SuratKeterangan->id_jenis_surat)->where('jenis', 'PENGAJUAN')->first()->template_surat;

        //data users
		$nama = '';
		if(!empty($users->gelar_depan)){
			$nama = $users->gelar_depan.'. ';
		}

		$nama = $nama.''.$users->name;

		if(!empty($users->gelar_belakang)){
			$nama = $nama.', '.$users->gelar_belakang;
		}

		$data['nama'] = $nama;

		$data['content'] = str_replace('[[nama-lengkap]]', $users->name, $data['content']);
		$data['content'] = str_replace('[[nama-lengkap-dan-gelar]]', $nama, $data['content']);
		$data['content'] = str_replace('[[gelar-depan]]', $users->gelar_depan, $data['content']);
		$data['content'] = str_replace('[[gelar-belakang]]', $users->gelar_belakang, $data['content']);
		$data['content'] = str_replace('[[email]]', $users->email, $data['content']);
		$data['content'] = str_replace('[[pendidikan-terakhir]]', $users->PendidikanTerakhir->pendidikan_terakhir, $data['content']);
		$data['content'] = str_replace('[[alamat-jalan-rt-rw]]', substr($users->alamat_jalan_rt_rw, 0, 3), $data['content']);
		$data['content'] = str_replace('[[alamat-jalan-rt-rw1]]', substr($users->alamat_jalan_rt_rw, 4, 3), $data['content']);
		$data['content'] = str_replace('[[alamat-domisili]]', $users->alamat_domisili, $data['content']);
		$data['content'] = str_replace('[[nomor-telpon]]', $users->nomor_telpon, $data['content']);
		$data['content'] = str_replace('[[nomor-ktp]]', $users->nomor_ktp, $data['content']);
		$data['content'] = str_replace('[[photo]]', url('/').'/img/foto3x6.png', $data['content']);
		$data['content'] = str_replace('[[tahun-lulus]]', $users->tahun_lulus, $data['content']);
		$data['content'] = str_replace('[[jenis-kelamin]]', $users->jenis_kelamin, $data['content']);
		$data['content'] = str_replace('[[desa]]', strtoupper($users->desa->nama_desa), $data['content']);
		$data['content'] = str_replace('[[dusun]]', strtoupper($users->dusun), $data['content']);
		$data['content'] = str_replace('[[kecamatan]]', strtoupper($users->kecamatan->nama_kecamatan), $data['content']);
		$data['content'] = str_replace('[[kabupaten]]', strtoupper(str_replace('KABUPATEN', '', $users->kabupaten->nama_kabupaten)), $data['content']);
		$data['content'] = str_replace('[[provinsi]]', strtoupper($users->provinsi->nama_provinsi), $data['content']);
		$data['content'] = str_replace('[[jabatan]]', $users->Jabatan->jabatan, $data['content']);
		$data['content'] = str_replace('[[tempat-lahir]]', strtoupper(str_replace('KABUPATEN', '', $users->tempat_lahir)), $data['content']);
		$data['content'] = str_replace('[[tanggal-lahir]]', Formatters::tgl_indo($users->tanggal_lahir), $data['content']);
		$data['content'] = str_replace('[[status-perkawinan]]', $users->status_perkawinan, $data['content']);
		$data['content'] = str_replace('[[status-kepegawaian]]', $users->status_kepegawaian, $data['content']);
		$data['content'] = str_replace('[[agama]]', $users->agama, $data['content']);
		$data['content'] = str_replace('[[profesi]]', $users->profesi, $data['content']);

    	//Surat
		$data['content'] = str_replace('[[tanggal-pengajuan]]', $SuratKeterangan->tanggal_pengajuan, $data['content']);
		$data['content'] = str_replace('[[keperluan]]', $SuratKeterangan->keperluan, $data['content']);
		$data['content'] = str_replace('[[nomor-surat]]', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $data['content']);
		$data['content'] = str_replace('[[tanggal-terbit]]', Formatters::tgl_indo($SuratKeterangan->tanggal_terbit), $data['content']);
		$romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
		$data['content'] = str_replace('[[tahun-terbit]]', date('Y', strtotime($SuratKeterangan->tanggal_terbit)), $data['content']);
		$data['content'] = str_replace('[[bulan-terbit]]', $romawi[date('m', strtotime($SuratKeterangan->tanggal_terbit))*1], $data['content']);

		if ($SuratKeterangan->status_aktif == 'Menunggu') {
			$data['content'] = str_replace('[[watermark]]', url('/').'/img/konsep.png', $data['content']);
		}elseif ($SuratKeterangan->status_aktif == 'Kedaluarsa') {
			$data['content'] = str_replace('[[watermark]]', url('/').'/img/Kedaluwarsa.png', $data['content']);
		}elseif ($SuratKeterangan->status_aktif == 'Ditolak') {
			$data['content'] = str_replace('[[watermark]]', url('/').'/img/tolak.png', $data['content']);
		}else{
			$data['content'] = str_replace('[[watermark]]', url('/').'/img/blank_watermark.png', $data['content']);
		}

        //ttd dan qrcode
		$data['content'] = str_replace('[[paraf-kasi]]', url('/').'/img/blank_kasi.png', $data['content']);
		$data['content'] = str_replace('[[paraf-kabid]]', url('/').'/img/blank_kabid.png', $data['content']);
		$data['content'] = str_replace('[[paraf-kadinkes]]', url('/').'/img/blank_ttd.png', $data['content']);
		$data['content'] = str_replace('[[stempel-dinkes]]', url('/').'/img/blank_stempel.png', $data['content']);
    	// qrcode [[qrcode]]
		$data['content'] = str_replace('[[logo-dinkes]]', url('/').'/img/kop_surat.png', $data['content']);

  		//list surat izin
		$list_surat = Surat::where('id_user', $users->id)->where('status_aktif', 'Aktif')->get();

		$html = "Tidak memiliki Tempat Praktik di wilayah Kabupaten Sidoarjo.";
		if($list_surat->count()!=0){
			$sip_ke_terbilang = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM'];
			$i=1;
			$html = "Telah memiliki Surat Ijin Praktik (SIP) di ".count($list_surat)." (".$sip_ke_terbilang[count($list_surat)].") sarana kesehatan di wilayah Dinas Kesehatan Kabupaten Sidoarjo. Sarana praktik tersebut adalah : <table style='width: 100%; margin-bottom: -7px'>";
			foreach($list_surat as $ls){
				$html .= "<tr>".
				"<td rowspan='3' style='vertical-align: top'>$i.</td>".
				"<td style='width:33%'>Nama Praktik</td>".
				"<td>:</td>".
				"<td style='width:64%'>$ls->nama_tempat_praktik</td>".
				"</tr>".
				"<tr>".
				"<td style='vertical-align: top'>Alamat</td>".
				"<td style='vertical-align: top'>:</td>".
				"<td style='vertical-align: top'>$ls->alamat_tempat_praktik</td>".
				"</tr>".
				"<tr>".
				"<td>No SIP</td>".
				"<td>:</td>".
				"<td>$ls->nomor_str</td>".
				"</tr>";
				$i++;
			}
			$html .= "</table>";
		}

		$data['content'] = str_replace('[[list-tempat-praktik]]', $html, $data['content']);
		$data['content'] = str_replace('[[jumlah-tempat-praktik]]', count($list_surat), $data['content']);

		$data['content'] = str_replace('[[list-tempat-praktik]]', $html, $data['content']);
		$data['content'] = str_replace('[[jumlah-tempat-praktik]]', count($list_surat), $data['content']);

		$file_name_asli = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-keterangan-asli.pdf');

	    if ($SuratKeterangan->file_surat_keterangan_asli != '') {
	      $file_name_asli = $SuratKeterangan->file_surat_keterangan_asli;
	    }

	    //qrcode surat asli
	    $data['url_surat_asli'] = url('/').'/upload/file_sip_asli/'.$file_name_asli;
	    $data['jenis_file'] = 'asli';

    	//Viewne gabung k2 surat

		return view('Surat.print_doc', $data);

    	// $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
    	// $pdf->save('upload/file_sip_salinan/'.$file_name_salinan);

    	// $pdf = view('Surat.print_doc', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
    	// $pdf->save('upload/file_doc/'.$file_name_doc);

    	// $SuratKeterangan->file_surat_keterangan_doc = $file_name_doc;
    	// $SuratKeterangan->save();
	}
}
