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

class DocumentPencabutanController extends Controller{
	public function __construct() {
		$this->middleware('auth');
	}

	function mainPencabutan(Request $request){
		$surat = Surat::find($request->id_surat);
	  $users = Users::find(Auth::getUser()->id);
	  return $this->cetak_docPencabutan($surat);
	}

	function cetak_docPencabutan($surat)
	{
	  $users = Users::find($surat->id_user);
    $JenisSurat = JenisSurat::find($surat->id_jenis_surat);

    if ($surat->status_aktif == 'Dicabut') {
      $data['content'] = TemplateSurat::where('id_jenis_surat', $surat->id_jenis_surat)->where('id_jenis_praktik', $surat->id_jenis_praktik)->where('jenis', 'PENCABUTAN')->first()->template_surat;
    }

    $nama = '';
    if(!empty($users->gelar_depan)){
      $nama = $users->gelar_depan.'. ';
    }

    $nama = $nama.''.$users->name;

    if(!empty($users->gelar_belakang)){
      $nama = $nama.', '.$users->gelar_belakang;
    }
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
    
    $data['content'] = str_replace('[[photo]]', url('/').'/upload/users/'.$users->photo, $data['content']);

    // $data['content'] = str_replace('[[photo]]', url('/').'/img/foto3x6.png', $data['content']);
    $data['content'] = str_replace('[[tahun-lulus]]', $users->tahun_lulus, $data['content']);
    $data['content'] = str_replace('[[jenis-kelamin]]', $users->jenis_kelamin, $data['content']);
    $data['content'] = str_replace('[[jenis-kelamin1]]', ($users->jenis_kelamin=='Perempuan') ? '2' : '1', $data['content']);

    $data['content'] = str_replace('[[desa]]', ucwords(strtoupper($users->desa->nama_desa)), $data['content']);
    $data['content'] = str_replace('[[dusun]]', ucwords(strtoupper($users->dusun)), $data['content']);
    $data['content'] = str_replace('[[kecamatan]]', ucwords(strtoupper($users->kecamatan->nama_kecamatan)), $data['content']);
    $data['content'] = str_replace('[[kabupaten]]', ucwords(strtoupper(str_replace('KABUPATEN', '', $users->kabupaten->nama_kabupaten))), $data['content']);
    $data['content'] = str_replace('[[provinsi]]', ucwords(strtoupper($users->provinsi->nama_provinsi)), $data['content']);
    $data['content'] = str_replace('[[jabatan]]', $users->Jabatan->jabatan, $data['content']);
    $data['content'] = str_replace('[[tempat-lahir]]', ucwords(strtoupper(str_replace('KABUPATEN', '', $users->tempat_lahir))), $data['content']);
    $data['content'] = str_replace('[[tanggal-lahir]]', Formatters::tgl_indo($users->tanggal_lahir), $data['content']);
    $data['content'] = str_replace('[[tanggal-lahir1]]', date('Ymd',strtotime($users->tanggal_lahir)), $data['content']);
    $data['content'] = str_replace('[[status-perkawinan]]', $users->status_perkawinan, $data['content']);
    $data['content'] = str_replace('[[status-kepegawaian]]', $users->status_kepegawaian, $data['content']);
    $data['content'] = str_replace('[[agama]]', $users->agama, $data['content']);
    $data['content'] = str_replace('[[profesi]]', $users->profesi, $data['content']);

    //surat
    $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $data['content'] = str_replace('[[tahun-terbit]]', date('Y', strtotime($surat->tanggal_terbit)), $data['content']);
    $data['content'] = str_replace('[[bulan-terbit]]', $romawi[date('m', strtotime($surat->tanggal_terbit))*1], $data['content']);
    $data['content'] = str_replace('[[sebagai-jabatan]]', $surat->sebagai_jabatan, $data['content']);
    $data['content'] = str_replace('[[tanggal-berlaku-str]]', Formatters::tgl_indo($surat->tanggal_berlaku_str), $data['content']);
    $data['content'] = str_replace('[[nomor-surat]]', $surat->nomor_surat, $data['content']);

    if($surat->tanggal_dicabut!='' || $surat->tanggal_dicabut!=null){
      $tgl_cabut = Formatters::tgl_indo($surat->tanggal_dicabut);
      $cbt = explode(" ",$tgl_cabut);
    }else{
      $cbt = ['','',''];
    }

    $data['content'] = str_replace('[[tanggal-permohonan-pencabutan]]', Formatters::tgl_indo($surat->tanggal_permohonan_pencabutan), $data['content']);
    $data['content'] = str_replace('[[tanggal-pengajuan]]', $surat->tanggal_pengajuan, $data['content']);
    $data['content'] = str_replace('[[jenis-praktik]]', $surat->jenis_praktik->nama_jenis_praktik, $data['content']);
    $data['content'] = str_replace('[[nama-tempat-praktik]]', $surat->nama_tempat_praktik, $data['content']);
    $data['content'] = str_replace('[[alamat-tempat-praktik]]', $surat->alamat_tempat_praktik, $data['content']);
    $data['content'] = str_replace('[[nomor-str]]', $surat->nomor_str, $data['content']);
    $data['content'] = str_replace('[[nomor-op]]', $surat->nomor_op, $data['content']);
    $data['content'] = str_replace('[[jenis-pekerjaan]]', $surat->jenis_pekerjaan, $data['content']);
    $data['content'] = str_replace('[[tanggal-terbit]]', Formatters::tgl_indo($surat->tanggal_terbit), $data['content']);
    $data['content'] = str_replace('[[tanggal-kedaluarsa]]', Formatters::tgl_indo($surat->tanggal_kedaluarsa), $data['content']);
    $data['content'] = str_replace('[[sip-ke]]', $surat->sip_ke, $data['content']);

    $sip_ke_terbilang = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM'];
    $data['content'] = str_replace('[[sip-ke-terbilang]]', $sip_ke_terbilang[$surat->sip_ke], $data['content']);

    $sip_ke_terbilang_kecil = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam'];
    $data['content'] = str_replace('[[sip-ke-terbilang-kecil]]', $sip_ke_terbilang_kecil[$surat->sip_ke], $data['content']);

    $data['content'] = str_replace('[[status-aktif]]', $surat->status_aktif, $data['content']);
    $data['content'] = str_replace('[[tanggal-dicabut]]', '&nbsp;&nbsp;&nbsp;&nbsp; '.$cbt[1].' '.$cbt[2], $data['content']);
    $data['content'] = str_replace('[[waktu-praktik]]', $surat->waktu_praktik, $data['content']);

    $data['content'] = str_replace('[[berlaku-sampai]]', 'berlaku sampai dengan tanggal '.Formatters::tgl_indo($surat->tanggal_kedaluarsa), $data['content']);

    // $data['content'] = str_replace('[[berlaku-sampai]]', $surat->status_aktif == 'Aktif' ? 'berlaku sampai dengan tanggal '.Formatters::tgl_indo($surat->tanggal_kedaluarsa) : 'belum berlaku.', $data['content']);
    
    $data['content'] = str_replace('[[tanggal-berlaku]]', Formatters::tgl_indo($surat->tanggal_kedaluarsa), $data['content']);

    //list surat izin
    $list_surat = Surat::where('id_user', $users->id)->where('status_aktif', 'Aktif')->get();
    $html = "<table>";
      for ($i=0; $i < count($list_surat); $i++) {
        $html =  $html."<tr>
          <td>$i. </td>
          <td>
            $list_surat[$i]->nama_tempat_praktik
            <br>
            $list_surat[$i]->alamat_tempat_praktik
            <br>
            $list_surat[$i]->nomor_str
          </td>
        </tr>";
      }
      $html = $html."</table>";
      $data['content'] = str_replace('[[list-tempat-praktik]]', $html, $data['content']);
      $data['content'] = str_replace('[[jumlah-tempat-praktik]]', count($list_surat), $data['content']);


      //ttd dan qrcode
      $data['content'] = str_replace('[[paraf-kasi]]', url('/').'/img/blank_kasi.png', $data['content']);
      $data['content'] = str_replace('[[paraf-kabid]]', url('/').'/img/blank_kabid.png', $data['content']);
      $data['content'] = str_replace('[[paraf-kadinkes]]', url('/').'/img/blank_ttd.png', $data['content']);
      $data['content'] = str_replace('[[stempel-dinkes]]', url('/').'/img/blank_stempel.png', $data['content']);

    $data['content'] = str_replace('[[logo-dinkes]]', url('/').'/img/kop_surat.png', $data['content']);
      // qrcode [[qrcode]]
      if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
        $file_name_asli = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-pencabutan_asli.pdf');
        $file_name_salinan = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-pencabutan_salinan.pdf');

        if ($surat->file_surat_pencabutan_sip_asli != '') {
          $file_name_asli = $surat->file_surat_pencabutan_sip_asli;
        }

        if ($surat->file_surat_pencabutan_sip_salinan != '') {
          $file_name_salinan = $surat->file_surat_pencabutan_sip_salinan;
        }

        $JenisSarana = JenisSarana::find($surat->id_jenis_sarana);

        switch ($JenisSarana->id_jenis_sarana) {
          case '19':
          $jenis_sarana = '';
          break;
          case '11':
          $jenis_sarana = 'Apoteker Penanggung Jawab '.ucwords($surat->nama_tempat_praktik);
          break;
          case '10':
          $jenis_sarana = 'Dokter Penanggung Jawab '.ucwords($surat->nama_tempat_praktik);
          break;
          case '9':
          $jenis_sarana = 'Direktur '.ucwords($surat->nama_tempat_praktik);
          break;

          default:
          $jenis_sarana = 'Kepala '.ucwords($surat->nama_tempat_praktik);
          break;
        }
        $data['content'] = str_replace('[[tembusan]]', $jenis_sarana, $data['content']);

      }else{
        $file_name_asli = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-asli.pdf');
        $file_name_salinan = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-salinan.pdf');

        if ($surat->file_surat_sip_asli != '') {
          $file_name_asli = $surat->file_surat_sip_asli;
        }

        if ($surat->file_surat_sip_salinan != '') {
          $file_name_salinan = $surat->file_surat_sip_salinan;
        }
      }

      //qrcode surat asli
      $data['url_surat_asli'] = url('/').'/upload/file_sip_asli/'.$file_name_asli;
      $data['jenis_file'] = 'asli';
      $data['content'] = str_replace(url('/').'/upload/users/'.$users->photo, url('/').'/upload/users/'.$users->photo, $data['content']);

      return view('Surat.print_docPencabutan', $data);

      // $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
      // $pdf->save('upload/file_sip_asli/'.$file_name_asli);      

      //ttd dan qrcode
      // $data['content'] = str_replace(url('/').'/img/foto3x6.png', url('/').'/upload/users/'.$users->photo, $data['content']);    

      // if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
      //   $surat->file_surat_pencabutan_sip_asli 			= $file_name_asli;
      // }

      // $surat->save();
    }	
}
