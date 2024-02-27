<style type="text/css">
	*{
		/* font-family: Calibri, sans-serif; */
		line-height: 1.2;
	}
	body {
		margin: 0px; padding: 19px; border: 4px double black; outline: solid 1px black;
	}
	@page { margin: 26px; }
</style>
<?php
	if ($jenis_file == 'salinan') {
		echo '<div style="font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif; width:70px; height:20px;position: fixed;right: 0;">SALINAN</div>';
	}

    // require_once("phpqrcode/qrlib.php");
    $file_name = $jenis_file == 'salinan' ? $url_surat_salinan : $url_surat_asli;
    // QRcode::png($file_name,"qrcode/image.png","L",4,4);
    // $qrcode  = asset('qrcode/image.png');
    // $content = str_replace("[[qrcode]]",  QrCode::size(300)->generate($file_name.'a'),  $content);
    echo $content;

?>
