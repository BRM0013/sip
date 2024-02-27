<!DOCTYPE html>
<html>
<head>
    <title>Cetak Document</title>
</head>
<body>
    <?php
    $nama_pakai = "Surat_Keterangan_".date('Y-m-d');
    ?>
    <a href="javascript:void(0)" onclick="Export2Doc('exportContent','{{$nama_pakai}}')">Download</a>
    <?php

    require_once("phpqrcode/qrlib.php");
    $file_name = Request::url();
    QRcode::png($file_name,"qrcode/image.png","L",3,3);
    $qrcode  = asset('qrcode/image.png');
    $content = str_replace("[[qrcode]]", $qrcode,  $content);
    echo '<div id="exportContent" style="margin: 0px; padding: 19px; border: 4px double black; outline: solid 3px black;">';
    echo $content;
    echo '</div>';
    ?>
</body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function Export2Doc(element, filename){
            var preHtml, postHtml, html, link, blob, url, css;
             css = (
             '<style>' +
             '@page WordSection1{size: 8.47in 12.99in;mso-page-orientation: portrait;margin: 0px; padding: 30px; border: 4px double black; outline: solid 3px black;} ' +
             'div.WordSection1 {page: WordSection1;}' +
             'table{border-collapse:collapse; margin-left: 60px; margin-right: 50px;}td{border:0px;}p{margin-left: 50px; margin-right: 50px;}span{margin-right: 100px;}' +
             'table#nomor_surat{border-collapse:collapse; margin-left: 0px;}td{margin-right:10px;}' +
             'table#nomor_surat2{border-collapse:collapse; margin-left: 0px;}td{margin-right:10px;}' +
             'table#kode_qr td{margin-right: 100px;}' +
             'table#logo_dinkes{border-collapse:collapse; margin-left: 5px; margin-top:60px; margin-right:100px;}' +
             'table#identitas{border-collapse:collapse;}' +
             'table#test_foto{border-collapse:collapse;} td p{margin-left:0px;}' +
             '</style>'
           );

            html = window.exportContent.innerHTML;

            blob = new Blob(['\ufeff', css + html], {
             type: 'application/msword'
            });

            url = URL.createObjectURL(blob);
            filename = filename?filename+'.doc':'document.doc';

            link = document.createElement('A');

            link.href = url;
            link.download = 'Document';
            document.body.appendChild(link);
            if (navigator.msSaveOrOpenBlob ) navigator.msSaveOrOpenBlob( blob, filename); // IE10-11
            else link.click();  // other browsers
            document.body.removeChild(link);
        }
    </script>
</html>
