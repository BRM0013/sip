<?php
header("Content-type: application/pdf");
// header("Content-Disposition: inline; filename=".$_GET['filepath']);
readfile($_GET['filepath']);
echo $_GET['filepath'];
?>
<!-- <iframe src="<?php echo $_GET['filepath']; ?>" width="" height=""></iframe> -->
