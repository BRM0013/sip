<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Models\Users;
use App\Models\Surat;
use App\Models\MasterData\JenisSurat;
use App\Models\SyaratPengajuan;
use App\Models\MasterData\JenisPersyaratan;
use App\Http\Controller\SuratController;
use App\Models\MasterData\TemplateSurat;
use App\Http\Libraries\Whatsapp;

use App\Http\Libraries\Formatters;
use Intervention\Image\ImageManagerStatic as Image;

use File, Auth, Redirect, Validator, DB, PDF;

class SendMailController extends Controller{
	
  public function __construct() {
		$this->middleware('auth');
	}

	public function form_validasi(Request $request)
  {    
    $data['users'] = Users::find($request->id);
    $data['surat'] = Surat::join('users','surat.id_user', 'users.id')
                                       ->where('surat.id_user',$request->id)
                                       ->get();
    $noS = 0;
    foreach ($data['surat'] as $key) {
      $data['surat'][$noS]['row'] = SyaratPengajuan::join('surat','syarat_pengajuan.id_surat','surat.id_surat')
                                            ->join('jenis_persyaratan','syarat_pengajuan.id_jenis_persyaratan','jenis_persyaratan.id_jenis_persyaratan')
                                            ->where('syarat_pengajuan.id_surat', $key->id_surat)->get();
      $noS++;
    }
    $data['list_surat'] = $data['surat'];
    return view('Surat.upload_berkas', $data);    
  } 

  public function save_uploadberkas(Request $request)
  {
    // return $request->all();
    // return $id_surat = $request->id_surat;
    $users = Users::find($request->id_user);
    
    if (!empty($request->file('photo'))) {
      $ext = $request->file('photo')->getClientOriginalExtension();
      if ($ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
          return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/users/setting' : 'home/users')
              ->with('title', 'Gagal !')
              ->with('message', 'Maaf! file Upload tidak sesuai')
              ->with('type', 'error');
      }else{
        $avatar = $request->file('photo');         
        $filename = "stempel-dinkes-uploaded-at-".strtolower(str_replace(" ", "-", $request->name)).'-'.date('Ymd-His').'.'.$avatar->getClientOriginalExtension();
        Image::make($avatar)->resize(130, 180)->save( public_path('/upload/users/' . $filename));
        $users->photo = $filename;
        $users->save();        
      }
    }
    // return $request->id_surat;
    // $surat = Surat::find($request->id_surat)
    if ($request->id_surat != '') {
        for ($i=0; $i < count($request->id_surat) ; $i++) {
          // return $request->id_surat[$i];

          $syarat_pengajuan = SyaratPengajuan::join('surat','syarat_pengajuan.id_surat','surat.id_surat')
                                  ->join('jenis_persyaratan','syarat_pengajuan.id_jenis_persyaratan','jenis_persyaratan.id_jenis_persyaratan')
                                  ->where('syarat_pengajuan.id_surat',$request->id_surat[$i])
                                  ->get();
                                  // return $syarat_pengajuan

          foreach ($syarat_pengajuan as $row) {
            
            if (!empty($request->file($row->nama_variable))) {
              $ukuran = filesize($request->nama_variable[$i]);
              
              if ($ukuran > 2000000) {
                return ['status'=>'error','message'=>'Maaf ukuran lebih dari 2 MB'];
              }else{
                $ext = $request->file($row->nama_variable)[$i]->getClientOriginalExtension();
                 if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
                  return Redirect::route('home')
                    ->with('title', 'Gagal !')
                    ->with('message', 'Maaf! file Upload tidak sesuai')
                    ->with('type', 'error');
                  }
                $filename   = str_replace(["/"],["_"],$row->nama_variable.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').$i.'.'.$ext);               
                $temp_foto  = 'upload/file_berkas';
                $proses     = $request->file($row->nama_variable)[$i]->move($temp_foto, $filename);               
              }
              $row->nama_file_persyaratan = $filename;
              $row->save();
              
              $surat = Surat::find($request->id_surat[$i]);
              $surat = $surat;
              $surat->upload_ulang = 'sudah';
              $surat->save();          
              $this->cetak_pdf($surat);
            }else{
              $surat = Surat::find($request->id_surat[$i]);
              $surat = $surat;
              $surat->upload_ulang = 'sudah';
              $surat->save();          
              $this->cetak_pdf($surat);
            }

          }
        }

      }
      // return '';
      $users->verifikasi_ulang = 'sudah';      
      $saved = $users->save();
      if ($saved) {
        return Redirect::route('home')
                      ->with('title', 'Berhasil !')
                      ->with('message', 'Data Berhasil Divalidasi')
                      ->with('type', 'success');
      }else{
        return Redirect::route('home')
                      ->with('title', 'Gagal !')
                      ->with('message', 'Data gagal Divalidasi')
                      ->with('type', 'error');
      }
  } 

  private function cetak_pdf($surat){
    set_time_limit(3000);
    $users = Users::find($surat->id_user);
    $JenisSurat = JenisSurat::find($surat->id_jenis_surat);

    if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
      $data['content'] = TemplateSurat::where('id_jenis_surat', $surat->id_jenis_surat)->where('id_jenis_praktik', $surat->id_jenis_praktik)->where('jenis', 'PENCABUTAN')->first()->template_surat;
    }else{
      $data['content'] = TemplateSurat::where('id_jenis_surat', $surat->id_jenis_surat)->where('id_jenis_praktik', $surat->id_jenis_praktik)->where('jenis', 'PENGAJUAN')->first()->template_surat;
    }

    //data users
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

    if ($surat->status_aktif == 'Menunggu') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/konsep.png', $data['content']);
    }elseif ($surat->status_aktif == 'Kedaluarsa') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/Kedaluwarsa.png', $data['content']);
    }elseif ($surat->status_aktif == 'Dicabut') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/dicabut.png', $data['content']);
    }elseif ($surat->status_aktif == 'Tolak') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/tolak.png', $data['content']);
    }else{
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/blank_watermark.png', $data['content']);
    }


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
      // qrcode [[qrcode]]
      $data['content'] = str_replace('[[logo-dinkes]]', url('/').'/img/KOP SIP besar.png', $data['content']);

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

      $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
      $pdf->save('upload/file_sip_asli/'.$file_name_asli);

      //qrcode surat asli
      $data['url_surat_salinan'] = url('/').'/upload/file_sip_salinan/'. $file_name_salinan;
      $data['jenis_file'] = 'salinan';

      //ttd dan qrcode
      $data['content'] = str_replace(url('/').'/upload/users/'.$users->photo, url('/').'/upload/users/'.$users->photo, $data['content']);
      // $data['content'] = str_replace(url('/').'/img/foto3x6.png', url('/').'/upload/users/'.$users->photo, $data['content']);

      if ($surat->disetujui_kasi == 'Disetujui') {
        // $data['content'] = str_replace(url('/').'/img/blank_kasi.png', url('/').'/upload/file_master/'.$JenisSurat->paraf_kasi, $data['content']);
      }

      if ($surat->disetujui_kabid == 'Disetujui') {
        // $data['content'] = str_replace(url('/').'/img/blank_kabid.png', url('/').'/upload/file_master/'.$JenisSurat->paraf_kabid, $data['content']);
      }

      if (($surat->status_aktif == 'Aktif' && $surat->disetujui_kadinkes == 'Disetujui') || ($surat->status_aktif == 'Dicabut' && $surat->pencabutan_disetujui_kadinkes == 'Disetujui')) {
        // $data['content'] = str_replace(url('/').'/img/blank_ttd.png', url('/').'/upload/file_master/'.$JenisSurat->ttd_kadinkes, $data['content']);
        // $data['content'] = str_replace(url('/').'/img/blank_stempel.png', url('/').'/upload/file_master/'.$JenisSurat->stempel_dinkes, $data['content']);
      }

      $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
      $pdf->save('upload/file_sip_salinan/'.$file_name_salinan);

      if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
        $surat->file_surat_pencabutan_sip_asli      = $file_name_asli;
        $surat->file_surat_pencabutan_sip_salinan     = $file_name_salinan;
      }else{
        $surat->file_surat_sip_asli     = $file_name_asli;
        $surat->file_surat_sip_salinan    = $file_name_salinan;
      }

      $surat->save();

      return "berhasil";
    }

    public function sendWA(Request $request)
    {      
      // $users = Users::where('id', array(1275, 1957);
      $users = Users::where('id',1275)->first();
        $data_users = DB::table('users')
                             ->select('id','id_surat','nomor_telpon','name')
                             ->join('surat','surat.id_user','users.id')
                             ->where('surat.status_aktif','Aktif')
                             ->where('users.status_verifikasi','Terverifikasi')
                             // ->where('users.id',1275)                          
                             ->get();
        // return $data_users;

        $dataID = [];
        $temptId = 0;
        foreach ($data_users as $key) {
          $syarat = SyaratPengajuan::join('users','syarat_pengajuan.id_user','users.id')
                                   ->join('surat','syarat_pengajuan.id_surat','surat.id_surat')
                                   ->join('jenis_persyaratan','syarat_pengajuan.id_jenis_persyaratan','jenis_persyaratan.id_jenis_persyaratan')
                                   ->where('syarat_pengajuan.id_surat', $key->id_surat)
                                   ->get();
            // return $syarat; 
            $stSyarat = 'Tidak';
            foreach ($syarat as $keySyarat) {
              if ($keySyarat->nama_file_persyaratan != '') {
                if(!file_exists(public_path('/upload/file_berkas/'.$keySyarat->nama_file_persyaratan))) {
                  $stSyarat = 'Ya';
                }
              }
            }
            if ($stSyarat == 'Ya') {
              $dataID[$temptId] = [
                'id' => $key->id,
                'nama' => $key->name,
                'phone' => $key->nomor_telpon,
              ];
              $temptId++;
            }
        }
        // return count($dataID);
        return $dataID;
        for ($i=0; $i < count($dataID) ; $i++) { 
          // return $dataID[$i]['phone'];
          Whatsapp::kirim($dataID[$i]->phone);
        }
         // Whatsapp::kirim($users->nomor_telpon);
    }
}
