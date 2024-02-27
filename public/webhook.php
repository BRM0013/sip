<?php
header("Content-Type: text/plain");
$data = json_decode(file_get_contents('php://input'), true);
date_default_timezone_set("Asia/Jakarta");
/**
 * for auto reply or bot
 */

$id = $data['id'];
$pushName = strtolower($data['pushName']);
$message = $data['message'];
$phone = $data['phone'];

$conn = new mysqli("192.168.2.212", "natusi", "4rjun4", "sip");
if ($conn->connect_error) { // Check connection
	die("Connection failed: " . $conn->connect_error);
}

$pesans = $message;
$pesan = '';
$cek_chatbot = '';
$result_cek_chatbot = mysqli_query($conn, "SELECT * FROM chatbot WHERE angka='".$pesans."'");
$result_chatbot1 = mysqli_query($conn, "SELECT * FROM chatbot WHERE level=1");

while($row = mysqli_fetch_array($result_cek_chatbot)){
	$cek_chatbot = $row;
}

	// $string = 'bertanya menanyakan ping halo assalamualaikum';
	// if(mysqli_num_rows($result_cek_chatbot) <= 0 && stripos($string,$pesans)!==false){
	if(mysqli_num_rows($result_cek_chatbot) <= 0 && checkStr($pesans)){
		$pesan .= "DAFTAR PERTANYAAN UNTUK WHATSAPP BOT \n";
		$pesan .= "Pilihan Judul Pertanyaan :\n";
		while($row = mysqli_fetch_array($result_chatbot1)){
			$pesan .= $row['angka'].". ".$row['pertanyaan']."\n\n";
		}
		$pesan .= "Copy Angka diawal lalu pastekan dan kirim (ex: 1, lalu kirim pesan).\n";
	}else{
		if(is_numeric($pesans) && $pesans<=mysqli_num_rows($result_chatbot1)){
			$question = mysqli_query($conn, "SELECT * FROM chatbot WHERE parent_id='".$cek_chatbot['id_chatbot']."'");
			if ($question->num_rows > 0) {
				$pesan .= "DAFTAR PERTANYAAN DARI PERTANYAAN (".$cek_chatbot['pertanyaan'].") \n";
				$pesan .= "Pilihan Judul Pertanyaan :\n";
				while($row = mysqli_fetch_array($question)){
					$pesan .= $row['angka'].". ".$row['pertanyaan']."\n\n";
				}
				$pesan .= "Copy Angka diawal lalu pastekan dan kirim (ex: 1, lalu kirim pesan).\n";
			} else {
				$answer = mysqli_query($conn, "SELECT * FROM chatbot WHERE id_chatbot='".$cek_chatbot['id_chatbot']."'");
				$pesan .= "JAWABAN DARI PERTANYAAN (".$cek_chatbot['pertanyaan'].")\n";
				while ($row = $answer->fetch_assoc()) {
					$pesan .= replaceString($row['jawaban'])."\n\n";
				}
				$pesan .= "TERIMAKASIH \n";
			}
		}
	}
	// echo $pesan = mysqli_num_rows($result_chatbot1);
	echo $pesan;
	die();

if(stripos($pushName, 'surat ijin praktek tenaga kesehatan sidoarjo')===false){
	echo $pesan;
}
function replaceString($str){
  return str_replace(['<p>','</p>','<u>','</u>','<ul>','</ul>','<li>','</li>','<br>'],'',$str);
}
//echo 'juga';

function checkStr($str){
	$string = ['halo','tanya','salamu'];
	foreach ($string as $key => $val) {
		if(stripos($str,$val)!==false){
			return true;
		}
	}
	return false;
}