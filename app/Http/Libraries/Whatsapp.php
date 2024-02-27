<?php
namespace App\Http\Libraries;

use App\Models\Users;
use File, Auth, Redirect, Validator, DB, PDF;

class Whatsapp
{
	// protected $url = "http://sip.natusi.co.id";
// 	protected $url = "http://192.168.2.131/develop/sidoarjosip/public/";
	protected $url = "http://dinkes.sidoarjokab.go.id/sip/public";
	// protected $url = "https://sip.natusi.co.id/public/";
	public static function kirim($no_wa, $id, $tipe, $data_email)
	{
		$url = "http://dinkes.sidoarjokab.go.id/sip/public";
		$user = Users::where('id',$id)->first();
		date_default_timezone_set('Asia/Jakarta');

		$pesan = "";
		if ($tipe == 'Tolak') {                             
			$pesan .= "Permohonan Surat Izin Praktik";
			$pesan .= "\n\nBapak / Ibu. *".$user->name."* Yth";
			$pesan .= "\nMohon maaf kepada bapak atau ibu, kami tidak dapat memberikan izin praktik ".$data_email['sip'].", silahkan lakukan perbaikan sesuai dengan keterangan berikut : ";
			$pesan .= "\n".$data_email['keterangan']."";
			$pesan .= "\n\nSemoga informasi ini dapat bermanfaat bagi anda. Untuk informasi lebih lanjut, silakan menghubungi kami melalui fasilitas [Sentra Pesan]";
			$pesan .= "\n\nDengan senang hati kami akan melayani anda.";
			$pesan .= "\n\nTerima kasih.";
			$pesan .= "\n\n\nHormat Kami,";
			$pesan .= "\n\nDinas Kesehatan Kabupaten Sidoarjo";
			// $pesan .= "\n\n\nSurat ini dihasilkan oleh komputer dan tidak perlu dijawab kembali.";
		} else if ($tipe == 'Tolak Pencabutan') {
			$pesan .= "Permohonan Pencabutan Surat Izin Praktik";
			$pesan .= "\n\nBapak / Ibu. *".$user->name."* Yth";
			$pesan .= "\nMohon maaf kepada bapak atau ibu, kami tidak dapat memberikan Surat Pencabutan Izin Praktik, silahkan lakukan perbaikan sesuai dengan keterangan berikut : ";
			$pesan .= "\n".$data_email['keterangan']."";
			$pesan .= "\n\nSemoga informasi ini dapat bermanfaat bagi anda. Untuk informasi lebih lanjut, silakan menghubungi kami melalui fasilitas [Sentra Pesan]";
			$pesan .= "\n\nDengan senang hati kami akan melayani anda.";
			$pesan .= "\n\nTerima kasih.";
			$pesan .= "\n\n\nHormat Kami,";
			$pesan .= "\n\nDinas Kesehatan Kabupaten Sidoarjo";
			// $pesan .= "\n\n\nSurat ini dihasilkan oleh komputer dan tidak perlu dijawab kembali.";

		} else if ($tipe == 'Pencabutan') {
			$pesan .= "Haloo, Mohon maaf menggangu Waktunya saya merupakan pesan dari sistem Dinas Kesehatan Sidoarjo ingin menyampaikan";
			$pesan .= "Surat Pencabutan Surat Izin Praktik bapak / ibu \n\nAtas Nama. ".$data_email['surat_pencabutan']['name']." yang diajukan melalui email Sudah selesai, silahkan mengumpulkan berkas asli yang diupload ke Bidang SDK Dinas Kesehatan Kab. Sidoarjo, untuk mendapatkan surat pencabutan SIP";			
			$pesan .= "\n\n\nHormat Kami,";
			$pesan .= "\n\nDinas Kesehatan Kabupaten Sidoarjo";
			// $pesan .= "\n\n\nSurat ini dihasilkan oleh komputer dan tidak perlu dijawab kembali.";
		} else {
			$pesan .= 'Diberitahukan kepada Bapak / Ibu untuk melakukan validasi ulang mengenai berkas persyaratan SIP yang sudah pernah Di upload. Dimohon untuk membuka aplikasi Login menggunakan Akun anda di link http://dinkes.sidoarjokab.go.id/sip/public/';
		}

		$nomer = Whatsapp::generate_number($no_wa);

		if($nomer!=''){
        	// SEND MESSAGE WA

			$curl = curl_init();
			$apikey = "60jFs9J9lt0RWt2v1zcFPbFXOpVhbobH4RSeuxYYWNBsiq4kL9BsdshgKGwsdJYe";
			$data = [
				'phone' => $nomer,
				'message' => $pesan,
			];

			curl_setopt($curl, CURLOPT_HTTPHEADER,
				array(
					"Authorization: $apikey",
				)
			);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($curl);
			curl_close($curl);
			// return $result;
	        // echo "<pre>";
	        // print_r($result);
		}

	}

	public static function verifikasiWA($no_wa, $id_user)
	{
		$url = "http://dinkes.sidoarjokab.go.id/sip/public";
		// $url = "http://192.168.2.131/develop/sidoarjosip/public/";
		// $url = "http://sip.natusi.co.id";
		$user = Users::where('id',$id_user)->first();
		date_default_timezone_set('Asia/Jakarta');                            
		$pesan = "";
		$pesan .= "Bapak / Ibu. *".$user->name."* Yth";
		$pesan .= "\nTerima kasih telah mendaftarkan diri Anda pada layanan Dinas Kesehatan Kab Sidoarjo. Jangan lupa follow instagram @sdkdinkes_sidoarjo untuk mendapatkan informasi terbaru.";
		$pesan .= "\nSilakan menekan link di bawah ini untuk mengonfirmasi bahwa email Anda aktif:";
		$pesan .= "\n".$url."/verifikasi/".$user->id;
		$pesan .= "\n\n\nJika link tidak dapat diakses, pastikan https diubah menjadi http";

		// $pesan .= "\n\nMohon maaf, Sehubungan dengan Hari Raya Idul Fitri 1444 H, Maka Pelayanan Surat Izin Praktik Dinas Kesehatan Kabupaten Sidoarjo LIBUR pada 19 April 2023 sd 25 April 2023, dan akan aktif kembali pada 26 April 2023";	

		$nomer = Whatsapp::generate_number($no_wa);

		if($nomer!=''){
        	// SEND MESSAGE WA

		// 	$key='db3a6d79869ac7ba000724164dd4b25e789f083f2303b9c8'; //this is demo key please change with your own key
		// 	$url='http://116.203.92.59/api/send_message';
		// 	$data = array(
		// 		"phone_no"=> $nomer,
		// 		"key"		=>$key,
		// 		"message"	=>$pesan
		// 	);
		// 	$data_string = json_encode($data);

		// 	$ch = curl_init($url);
		// 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($ch, CURLOPT_VERBOSE, 0);
		// 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		// 	curl_setopt($ch, CURLOPT_TIMEOUT, 360);
		// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		// 		'Content-Type: application/json',
		// 		'Content-Length: ' . strlen($data_string))
		// );
		// 	$res=curl_exec($ch);
		// 	curl_close($ch);

			$curl = curl_init();
			$apikey = "60jFs9J9lt0RWt2v1zcFPbFXOpVhbobH4RSeuxYYWNBsiq4kL9BsdshgKGwsdJYe";
			$data = [
				'phone' => $nomer,
				'message' => $pesan,
			];

			curl_setopt($curl, CURLOPT_HTTPHEADER,
				array(
					"Authorization: $apikey",
				)
			);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($curl);
			curl_close($curl);
			// return $result;
	        // echo "<pre>";
	        // print_r($result);
		}

	}

	public static function jadwalkanTanggal($no_wa, $id_user, $tanggal, $jadwal_keterangan)
	{
		$user = Users::where('id',$id_user)->first();
		date_default_timezone_set('Asia/Jakarta');                            
		
		$pesan = 'PENGAMBILAN DILAKUKAN PADA SENIN - KAMIS : 08.30 - 12.00 WIB dan 13.00 - 14.30 WIB, JUMAT : 08.30 - 11.30 WIB';
		$pesan .= "\n\nPengambilan Surat Izin Praktik bapak / ibu . ".$user->name."";		
		$pesan .= "\nTANGGAL/TEMPAT : ".$tanggal. ' '."\nMal Pelayanan Publik Kabupaten Sidoarjo (Jl. Lingkar Timur) https://goo.gl/maps/kyw8LTcTBBmcifca8";
		$pesan .= "\nNo. Telepon MPP : 031. 99715540";
		$pesan .= "\nKeterangan : ".$jadwal_keterangan;
		$pesan .= "\n\nPersyaratan : ";
		$pesan .= "\n1. Datang sesuai jadwal yang ditentukan dengan menunjukkan bukti menerima notifikasi WhatsApp";
		$pesan .= "\n2. Membawa Semua Berkas Persyaratan yang di upload melalui SIP Online (Bukan Hasil Scan / Mencetak Sendiri) dengan rincian :";
		$pesan .= "\na. STR Legalisir/ e-STR / STR SALINAN (khusus Dokter)";
		$pesan .= "\nb. Surat kecukupan satuan kredit profesi dari Organisasi Profesi (khusus perpanjangan)";
		$pesan .= "\nc. Surat permohonan kepada Kepala Dinas Kesehatan Kabupaten Sidoarjo";
		$pesan .= "\nd. Surat Keterangan dari Pimpinan Fasilitas Kesehatan (Berstempel Basah)";
		$pesan .= "\ne. Asli Surat Keterangan Sehat (kecuali Dokter, Apoteker, TTK)";
		$pesan .= "\nf. Semua Berkas Persyaratan pendukung lainnya yang diupload melalui SIP Online";
		$pesan .= "\ng. Membawa SIP Lama Asli (Khusus Perpanjangan)";
		$pesan .= "\nh. Fotocopy Surat Persetujuan Ijin Operasional Fasilitas Pelayanan Kesehatan / Sertifikat Standart Fasilitas Pelayanan Kesehatan.";
		$pesan .= "\ni. Menunjukkan KTP Asli sesuai yang di upload.";
		$pesan .= "\nj. Surat Kuasa Bermaterai & fotocopy KTP yang mengambil (khusus penyerahan yang diwakilkan)";
		$pesan .= "\nk. Berkas Dimasukkan Dalam 1 map yang tertulis Nama Lengkap dan profesi";
		
		$pesan .= "\n\nSelanjutnya mohon untuk mengisi Survei Kepuasan Masyarakat (SKM) Pelayanan Perizinan Surat Ijin Praktik melalui link  https://bit.ly/IKMJAYANTI (tinggal klik link tersebut kemudian dinilai) dengan memilih petugas pemberi layanan An. Jayanti Mayasari dan layanan yang diterima : Bidang Sumber Daya Kesehatan (SDM-Kesehatan)";	
		$pesan .= "\nJangan lupa follow instagram @sdkdinkes_sidoarjo untuk mendapatkan informasi terbaru.";	
		$pesan .= "\n\nPesan ini dikirim melalui sistem tidak perlu dibalas terimakasih Tetap Sehat dan Semangat bagi kita semua";

		$nomer = Whatsapp::generate_number($no_wa);

		if($nomer!=''){
        	// SEND MESSAGE WA

		// 	$key='db3a6d79869ac7ba000724164dd4b25e789f083f2303b9c8'; //this is demo key please change with your own key
		// 	$url='http://116.203.92.59/api/send_message';
		// 	$data = array(
		// 		"phone_no"=> $nomer,
		// 		"key"		=>$key,
		// 		"message"	=>$pesan
		// 	);
		// 	$data_string = json_encode($data);

		// 	$ch = curl_init($url);
		// 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($ch, CURLOPT_VERBOSE, 0);
		// 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		// 	curl_setopt($ch, CURLOPT_TIMEOUT, 360);
		// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		// 		'Content-Type: application/json',
		// 		'Content-Length: ' . strlen($data_string))
		// );
		// 	$res=curl_exec($ch);
		// 	curl_close($ch);

			$curl = curl_init();
			$apikey = "60jFs9J9lt0RWt2v1zcFPbFXOpVhbobH4RSeuxYYWNBsiq4kL9BsdshgKGwsdJYe";
			$data = [
			'phone' => $nomer,
			'message' => $pesan,
			];

			curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
			"Authorization: $apikey",
			)
			);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($curl);
			curl_close($curl);

	        // echo "<pre>";
	        // print_r($result);
		}

	}

	public static function rupiah($money){
		return number_format($money,0,",",".");
	}

	public static function generate_number($number){
		$noWa = $number;
		if($noWa!=''){
			if($noWa[0]=='0'){
				$no = '';
				for ($i=1; $i < strlen($noWa); $i++) {
					$no .= $noWa[$i];
				}
				$noWa = '62'.$no;
			}else if($noWa[0]=='6'){
				$noWa = $number;
			}else if($noWa[0]=='+'){
				$no = '';
				for ($i=1; $i < strlen($noWa); $i++) {
					$no .= $noWa[$i];
				}
				$noWa = $no;
			}else{
				$noWa = '62'.$noWa;
			}
		}else{
			$noWa = '';
		}
		return $noWa;
	}

	public static function verifikasi_resetWA($no_wa, $id_user)
	{
		$url = "http://dinkes.sidoarjokab.go.id/sip/public";
		// $url = "http://192.168.2.101/develop/sidoarjosip/public";
		
		// $url = "http://sip.natusi.co.id/public";
		$user = Users::where('id',$id_user)->first();
		date_default_timezone_set('Asia/Jakarta');                            
		$pesan = "";
		// $pesan .= "Bapak / Ibu. *".$user->name."* Yth";
		$pesan .= "\nVerifikasi Reset Password.";
		$pesan .= "\nSilakan menekan link di bawah ini untuk Login Akun anda dan Lakukan perubahan Password:";
		$pesan .= "\n".$url."/verifikasi_reset/".$user->id;

		$nomer = Whatsapp::generate_number($no_wa);

		if($nomer!=''){
			$curl = curl_init();
			$apikey = "60jFs9J9lt0RWt2v1zcFPbFXOpVhbobH4RSeuxYYWNBsiq4kL9BsdshgKGwsdJYe";
			$data = [
				'phone' => $nomer,
				'message' => $pesan,
			];

			curl_setopt($curl, CURLOPT_HTTPHEADER,
				array(
					"Authorization: $apikey",
				)
			);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	        $result = curl_exec($curl);
	        $err = curl_error($curl);
	        curl_close($curl);

	        // echo "<pre>";
	        // print_r($err);

	        if (!$err) {			
				  $data = [
				  	'title' 	=>'Berhasil',
					'message' 	=>'Link Reset Password Berhasil Dikirim ke WA',
					'type' 		=>'success'
				  ];

				 return $data;
			}
		}
	}

	public static function kirimBerkasTte($id_user, $id_surat, $file_berkas)
	{		
		$url = "http://dinkes.sidoarjokab.go.id/sip/public";
		// $url = "http://192.168.2.101/develop/sidoarjosip/public";
		// $url = "http://sip.natusi.co.id";

		$user = Users::where('id',$id_user)->first();
		date_default_timezone_set('Asia/Jakarta');                            
		
		$pesan = 'TERIMA KASIH SUDAH MENGGUNAKAN LAYANAN SIP';
		// $pesan .= "\n\nPengambilan Surat Izin Praktik HardCopy bapak / ibu . ".$user->name."";		
		// $pesan .= "\nTEMPAT : "."\nMal Pelayanan Publik Kabupaten Sidoarjo (Jl. Lingkar Timur) https://goo.gl/maps/kyw8LTcTBBmcifca8";
		// $pesan .= "\nNo. Telepon MPP : 031. 99715540";
		// $pesan .= "\n\nTerima kasih sudah menggunakan layanan SIP ";	
		$pesan .= "\n\nBerikut File yang dapat Diunduh";		
		$pesan .= "\n".$url."/upload/file_sip_asli/".$file_berkas;

		$nomer = Whatsapp::generate_number($user->nomor_telpon);

		if($nomer!=''){
			$curl = curl_init();
			$apikey = "60jFs9J9lt0RWt2v1zcFPbFXOpVhbobH4RSeuxYYWNBsiq4kL9BsdshgKGwsdJYe";
			$data = [
			'phone' => $nomer,
			'message' => $pesan,
			];

			curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
			"Authorization: $apikey",
			)
			);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($curl);
			curl_close($curl);

	        // echo "<pre>";
	        // print_r($result);
		}

	}
}
