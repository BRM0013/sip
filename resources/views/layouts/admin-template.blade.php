<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dinas Kesehatan Sidoarjo | Dashboard</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/Ionicons/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
   <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('/') }}/dist/css/AdminLTE.min.css">

  <!-- link custom -->
  <link rel="stylesheet" type="text/css" href="{!! url('css/custom.css') !!}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
       <link rel="stylesheet" href="{{ url('/') }}/dist/css/skins/_all-skins.min.css">
       {{-- SWEETALERT --}}
       <link rel="stylesheet" href="{{ url('/') }}/build/sweetalert/sweetalert.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HC3T8E589Y"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HC3T8E589Y');
</script>
  <style type="text/css">
    .treeview-menu>li>a{
      display: inline-grid !important;
    }

    .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side{
      background-color: #2B3940;
    }

    .skin-blue .sidebar a {
      color: #fff;
    }

    .skin-blue .sidebar-menu>li>a{
      border: 0.5px solid;
      border-color: #c2cfd6;
    }

    .skin-blue .sidebar-menu .treeview-menu>li>a{
      color: #fff;
    }

    .skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a{
      background-color: #519cc8 !important;
    }

    .treeview-menu>li:hover, .treeview-menu>li.active{
      background-color: #519cc8 !important;
    }

    /*.open>.dropdown-menu {
      display: block !important;
      z-index: 999 !important;
      position: relative !important;
    }*/

    .notifikasi-surat {
      position: fixed;
      top: 50px;
      right: 10px;
      z-index: 999999;
    }


  </style>

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition skin-blue sidebar-mini">
  @php
    $surat_menunggu = App\Models\Surat::join('users as u','u.id','surat.id_user')->where('status_aktif', 'Menunggu')->where('status_simpan', 'simpan')->get();
  @endphp

  @if(count($surat_menunggu) > 0 && (Auth::user()->id_level_user == 1 || Auth::user()->id_level_user == 8))
  <div class="alert alert-warning notifikasi-surat" role="alert">
    <a href="{{route('surat-all')}}?filter=Menunggu" style="text-decoration: none;"><span style="margin-right: 20px;">Ada {{count($surat_menunggu)}} Surat yang masih Menunggu Pengecekan</span></a>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>S</b>IP</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SIP </b>Dinkes SDA</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ url('/') }}/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">{{Auth::getUser()->name}}</span>
            </a> -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="@if(Auth::getUser()->photo!='') {{ url('/') }}/upload/users/{{ Auth::getUser()->photo }} @else {{ url('/') }}/upload/no_image.jpg @endif" class="user-image" alt="User Image">
              <span class="hidden-xs">{{Auth::getUser()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <!-- <img src="{{ url('/') }}/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                <img src="@if(Auth::getUser()->photo!='') {{ url('/') }}/upload/users/{{ Auth::getUser()->photo }} @else {{ url('/') }}/upload/no_image.jpg @endif" class="img-circle" alt="User Image">

                <p>
                  {{Auth::getUser()->name}}
                  <small>{{Auth::getUser()->email}}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </nav>
  </header>

    <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <!-- <img src="{{ url('/') }}/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
          <img src="@if(Auth::getUser()->photo!='') {{ url('/') }}/upload/users/{{ Auth::getUser()->photo }} @else {{ url('/') }}/upload/no_image.jpg @endif" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p style="font-size: 12px;">{{Auth::getUser()->name}}</p>
          <P style="font-size: 11px;">{{Auth::getUser()->email}}</P>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
         @if(Auth::getUser()->id_level_user != 5)
            <li <?php if(!empty($mn_active)) {if ($mn_active == 'dashboard') {echo "class='active'";}} ?>>
              <a href="{{ url('/') }}/home">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>

            @if(Auth::getUser()->id_level_user != 2 && Auth::getUser()->id_level_user != 6 && Auth::getUser()->id_level_user != 7)
            <li>
              <a href="{{ route('surat-all') }}">
                <i class="fa fa-university"></i> <span>Semua Surat Izin Praktik</span>
              </a>
            </li>
            @endif

            <?php if (Auth::getUser()->id_level_user == 2): ?>
              <li>
                <a href="{{ route('surat') }}">
                  <i class="fa fa-university"></i> <span>Surat Izin Praktik</span>
                </a>
              </li>
            <?php elseif (Auth::getUser()->id_level_user == 6 || Auth::getUser()->id_level_user == 7): ?>
              <li>
                <a href="{{ route('surat') }}">
                  <i class="fa fa-university"></i> <span>Daftar Surat Izin Praktik</span>
                </a>
              </li>
            <?php else: ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-university"></i>
                  <span>Surat Izin Praktik</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php $no = 1; ?>
                  <?php foreach (App\Models\MasterData\JenisSurat::where('id_jenis_surat', '!=', '28')->orderBy('urutan','ASC')->get() as $row): ?>
                    <li style="text-indent: 10px;"><a href="{{ route('surat') }}/list/{{ $row->id_jenis_surat }}"><?php echo $no++; ?>. {{$row->nama_surat}}</a></li>
                  <?php endforeach ?>
                </ul>
              </li>
            <?php endif ?>

            <?php if (Auth::getUser()->id_level_user != 6 && Auth::getUser()->id_level_user != 7): ?>
            <li <?php if(!empty($mn_active)) {if ($mn_active == 'keterangan') {echo "class='active'";}} ?>>
              <a href="{{ route('surat_keterangan') }}">
                <i class="fa fa-file-text-o"></i> <span>Suket Praktik Diluar Sidoarjo</span>
              </a>
            </li>

            <li <?php if(!empty($mn_active)) {if ($mn_active == 'format_surat') {echo "class='active'";}} ?>>
              <a href="{{ route('format_surat') }}">
                <i class="fa fa-file"></i> <span>Contoh Format Surat</span>
              </a>
            </li>
            <?php endif ?>

            <?php if (Auth::getUser()->id_level_user == 1): ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-cube"></i>
                  <span>Data Master</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li style="text-indent: 10px;"><a href="{{ route('ttdkadinkes') }}">Master Ttd Kadinkes</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('data_op') }}" >Akun Organisasi Profesi</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('data_rs') }}" >Akun Rumah Sakit</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('fasyankes') }}" >Daftar Fasyankes</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('jabatan') }}" >Jabatan</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('jenis_praktik') }}" >Jenis Praktik</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('level_pengguna') }}">Level Pengguna</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('pendidikan_terakhir') }}">Pendidikan Terakhir</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('jenis_surat') }}">Jenis Surat</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('jenis_sarana') }}">Jenis Sarana</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('jenis_persyaratan') }}">Jenis Persyaratan</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('upload_manual_guide') }}">Manual Guide</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('master_provinsi') }}">Master Provinsi</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('master_kabupaten') }}">Master Kabupaten</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('master_kecamatan') }}">Master Kecamatan</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('master_desa') }}">Master Desa</a></li>
                  <li style="text-indent: 10px;"><a href="{{ route('chatbot') }}">ChatBot</a></li>
                </ul>
              </li>
              <li <?php if(!empty($mn_active)) {if ($mn_active == 'log_aktifitas') {echo "class='active'";}} ?>>
                <a href="{{ route('log_aktifitas') }}">
                  <i class="fa fa-history"></i> <span>Log Aktifitas</span>
                </a>
              </li>
              <li <?php if(!empty($mn_active)) {if ($mn_active == 'users') {echo "class='active'";}} ?>>
                <a href="{{ route('users') }}">
                  <i class="fa fa-user"></i> <span>Pengguna</span>
                </a>
              </li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-database"></i>
                  <span>Backup Database</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li style="text-indent: 10px;"><a target="_blank" href="{{ url('/') }}/dbexport.php" >Backup Database</a></li>
                  <li style="text-indent: 10px;"><a target="_blank" href="{{ url('/') }}/backup/mydata.sql" >Download Database</a></li>
                </ul>
              </li>
              <li <?php if(!empty($mn_active)) {if ($mn_active == 'laporan-admin') {echo "class='active'";}} ?>>
                <a href="{{ route('laporan-admin') }}">
                  <i class="fa fa-file"></i> <span>Laporan SIP</span>
                </a>
              </li>
            <?php endif ?>

            <?php if (Auth::getUser()->id_level_user == 6): ?>
            <li <?php if(!empty($mn_active)) {if ($mn_active == 'pengaturan_dataop') {echo "class='active'";}} ?>>
              <a href="{{ route('data_op') }}/setting">
                <i class="fa fa-gear"></i> <span>Pengaturan</span>
              </a>
            </li>

            <?php else: ?>
            <li <?php if(!empty($mn_active)) {if ($mn_active == 'pengaturan') {echo "class='active'";}} ?>>
              <a href="{{ route('users') }}/setting">
                <i class="fa fa-gear"></i> <span>Data Diri</span>
              </a>
            </li>
            <?php endif ?>
         @endif
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <?php if (Auth::getUser()->status_verifikasi == 'Belum Terverfikasi'): ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>Verifikasi Email</h1>
      </section>

      <section class="content">
        <div class="alert" style="border-color: #ffbeb5; background-color: #ffbeb5;">
          <strong>Selamat</strong> Anda telah terdaftar di SIP Dinas Kesehatan Sidoarjo, silahkan verifikasi <strong>Nomor WA</strong> Anda terlebih dahulu untuk dapat menggunakan sistem kami.
        </div>
      </section>
    </div>

  <?php else: ?>

      @yield('content')


  <?php endif ?>


  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2020 <a href="#">Dinas Kesehatan Sidoarjo</a>.</strong> Develop By. CV NATUSI
  </footer>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <!-- <div class="control-sidebar-bg"></div> -->

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('/') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="{{ url('/') }}/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('/') }}/dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="{{ url('/') }}/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="{{ url('/') }}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="{{ url('/') }}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- Select2 -->
<script src="{{ url('/') }}/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- CKeditor -->
<script src="{!! asset('plugins/ckeditor1/ckeditor.js') !!}"></script>
<script src="{!! asset('plugins/ckeditor1/adapters/jquery.js') !!}"></script>
<!-- DataTables -->
<script src="{{ url('/') }}/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{ url('/') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- bootstrap datepicker -->
<script src="{{ url('/') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- ChartJS -->
<script src="{{ url('/') }}/bower_components/chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{ url('/') }}/dist/js/pages/dashboard2.js"></script> -->
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{ url('/') }}/dist/js/demo.js"></script> -->
{{-- DATAGRID --}}
<script src="{{ url('/') }}/build/js/datagrid.js"></script>
{{-- SWEETALERT --}}
<script src="{{ url('/') }}/build/sweetalert/sweetalert.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>


<?php if (!empty(Auth::getUser())): ?>
  <!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5d01de1d267b2e578532248f/default';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
</script>
<!--End of Tawk.to Script-->
<?php endif ?>


<script type="text/javascript">
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>

<script type="text/javascript">
  @if (!empty(Session::get('message')))
    swal({
      title: "{{Session::get('title')}}",
      text: "{{Session::get('message')}}",
      type: "{{Session::get('type')}}",
      timer: 2000,
      showConfirmButton: false
    });
  @endif


  // baca pemberitahuan
  function baca_pemberitahuan(id){
    $.post("{!! route('pemberitahuan_dibaca') !!}", {id:id}).done(function(data){
      $('#jumlah_pemberitahuan').remove();
      $('#header_pemberitahuan').html('Tidak ada pemberitahuan baru!')
    });
  }

  function detail_pencabutan(id, id_pemberitahuan) {
    baca_pemberitahuan(id_pemberitahuan);
    $.post("{{ route('detail_pencabutan_surat') }}", {id:id}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }

  function detail_surat(id, id_pemberitahuan) {
    baca_pemberitahuan(id_pemberitahuan);
    $.post("{{ route('detail_surat') }}", {id:id}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }

  function detail_surat_keterangan(id, id_pemberitahuan) {
    baca_pemberitahuan(id_pemberitahuan);
    $.post("{{ route('detail_surat_keterangan') }}", {id:id}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }
</script>

@yield('js')
</body>
</html>
