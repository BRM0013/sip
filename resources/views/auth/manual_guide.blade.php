@extends('layouts.app')
@section('content')
<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="tab">
                <!-- <button class="tablinks" onclick="openCity(event, 'Admin')">Admin</button> -->
                <button class="tablinks" onclick="openCity(event, 'Pemohon')">Buku Panduan</button>
                <!--<button class="tablinks" onclick="openCity(event, 'Kasi')">Kasi</button>-->
                <!-- <button class="tablinks" onclick="openCity(event, 'Kabid')">Kabid</button> -->
                <!-- <button class="tablinks" onclick="openCity(event, 'Kadinkes')">Kadinkes</button> -->
            </div>

           <!--  <div id="Admin" class="tabcontent">
                <h3>Manual Guide Admin</h3>
                <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/')}}/upload/manual_guide/Buku_Panduan_Kadinkes(Admin).pdf" width="100%" height="550px"></iframe>
            </div> -->

            <div id="Pemohon" class="tabcontent">
                <h3>Buku Panduan Pemohon </h3>
                <iframe src="{{ url('/')}}/upload/manual_guide/Buku_Panduan_Kadinkes(Client).pdf" width="100%" height="550px"></iframe>

                <!-- <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/')}}/upload/manual_guide/Buku_Panduan_Kadinkes(Client).pdf" width="100%" height="550px"></iframe> -->
            </div>

          <!--   <div id="Kasi" class="tabcontent">
                <h3>Manual Guide Kasi</h3>
                <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/')}}/upload/manual_guide/Buku_Panduan_Kadinkes(Kasi).pdf" width="100%" height="550px"></iframe>
            </div> -->

            <!-- <div id="Kabid" class="tabcontent">
                <h3>Manual Guide Kabid</h3>
                <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/')}}/upload/manual_guide/Buku_Panduan_Kadinkes(Kabid).pdf" width="100%" height="550px"></iframe>
            </div> -->

           <!--  <div id="Kadinkes" class="tabcontent">
                <h3>Manual Guide Kadinkes</h3>
                <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/')}}/upload/manual_guide/Buku_Panduan_Kadinkes(Kadinkes).pdf" width="100%" height="550px"></iframe>
            </div> -->
        </div>
    </div>
</div>

<script>
document.getElementsByClassName("tabcontent")[0].style.display = "block";
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>

</body>
@endsection
