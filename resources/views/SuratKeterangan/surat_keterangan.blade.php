@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <h1>Surat Keterangan Praktik Diluar Wilayah Sidoarjo</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Master Data</a></li>
      <li class="active"> Surat Keterangan</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-info" id="btn-add" style="border-top:none">
      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
        <i class="fa fa-file iconLabel m-r-15"></i> Data Permohonan Surat Keterangan Praktik Diluar Wilayah Sidoarjo</h4>
        <div class="box-body">
         @if(in_array(Auth::getUser()->id_level_user, [1,8]))
         <div class="col-md-12">
          @if(in_array(Auth::getUser()->id_level_user, [1,8]))
          <div class="col-md-2" style="margin-bottom: 10px;">
            <button class="btn btn-info" id="jadwalTanggal">Jadwalkan Tanggal</button>
          </div>
          <div class="col-md-2" style="margin-bottom: 10px;">
            <button class="btn btn-success" id="sudahAmbil">Sudah Ambil</button>
          </div>
          <div class="col-md-2" style="margin-bottom: 10px;">
            <button class="btn btn-danger" id="batalkan">Batalkan</button>
          </div>
          <div class="col-sm-8" style="margin-top: 10px;">          
            @endif
          </div>
          <hr style="border: 1px solid grey;width: 100%;">
          @endif

          <div class="col-md-12">
           {{-- DATA GRID --}}
           <div class="col-md-12 form-inline main-layer panelSearch p-10">
            <?php if (Auth::getUser()->id_level_user == 2): ?>
              @if(App\Models\Users::is_complite(Auth::getUser()))
              <div class="form-group pull-left">
                <a class="btn btn-warning" href="{{ route('add_surat_keterangan') }}">
                  <i class="fa fa-plus-square iconLabel" style="margin-right: 15px"></i> Tambah
                </a>
              </div>
              @else
              <a class="btn btn-warning" onclick="add_error()">
                <i class="fa fa-plus-square iconLabel" style="margin-right: 15px"></i> Tambah
              </a>
              @endif 
            <?php endif ?>
            <?php if (Auth::getUser()->id_level_user != 2): ?>

              <?php $value = ['Semua', 'Menunggu', 'Aktif', 'Ditolak','Dijadwalkan Tanggal','Sudah Diambil']; ?>              
              <?php $filter = ['Semua', 'Menunggu Pengecekan', 'Proses Tanda Tangan Basah', 'Ditolak','Dijadwalkan Tanggal','Sudah Diambil']; ?>
              <select id="filter" class="form-control" onchange="get_load(this.value)">
                <?php for ($i = 0; $i < count($filter); $i++): ?>
                  <option value="{{ $value[$i] }}">{{ $filter[$i] }}</option>
                <?php endfor ?>
              </select>
            <?php endif ?>
            <div class="form-group pull-right">
              <select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option"></select>
            </div>
            <div class="form-group pull-right">
              <input type="text" style="text-transform:uppercase" class="input-sm form-control" placeholder="Search" id="search">
            </div>
          </div>
          <div class='clearfix'></div>
          <div class="col-md-12 p-0">
            <div class="table-responsive">
              <table class="table table-striped b-t b-light" id="datagrid"></table>
            </div>
            <footer class="panel-footer">
              <div class="row">
                <!-- Page Option -->
                <div class="col-sm-2 hidden-xs">
                  <select class="input-sm form-control input-s-sm inline v-middle option-page" id="option"></select>
                </div>
                <!-- Page Info -->
                <div class="col-sm-5 text-center">
                  <small class="text-muted inline m-t-sm m-b-sm" id="info"></small>
                </div>
                <!-- Paging -->
                <div class="col-sm-5 text-right text-center-xs">
                  <ul class="pagination pagination-sm m-t-none m-b-none" id="paging"></ul>
                </div>
              </div>
            </footer>
          </div>
          <div class='clearfix'></div>
          {{--  <table class="table table-bordered"></table> --}}
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
</section>
<div class="clearfix"></div>
</div>

<div class="modal-dialog"></div>

@endsection
@section('js')
<script type="text/javascript">
  var datagrid;
  function get_load(filter) {
    $("#datagrid").html('');
    datagrid = $("#datagrid").datagrid({
      url                     : '{{route("datagrid_surat_keterangan")}}?filter='+filter,
      primaryField            : 'id_surat_keterangan',
      rowNumber               : true,
      rowCheck                : false,
      searchInputElement      : '#search',
      searchFieldElement      : '#search-option',
      pagingElement           : '#paging',
      optionPagingElement     : '#option',
      pageInfoElement         : '#info',
      columns                 : [
      {field: 'checks', title: '<input type="checkbox" onclick="checkAll(this)">', editable: false, sortable: false, width: 30, align: 'center', search: false,
      rowStyler: function(rowData, rowIndex) {
        return checks(rowData, rowIndex);
      }
    },
    {field: 'name', title: 'Nama Pemohon', editable: false, sortable: true, width: 200, align: 'left', search: true},
    {field: 'tanggal_pengajuan', title: 'Tanggal Pengajuan', editable: false, sortable: true, width: 200, align: 'left', search: true},
    {field: 'email', title: 'Email', editable: false, sortable: true, width: 200, align: 'left', search: true},
    {field: 'keperluan', title: 'Keperluan', editable: false, sortable: true, width: 200, align: 'left', search: true},
      // {field: 'status_aktif', title: 'Status', editable: false, sortable: true, width: 200, align: 'left', search: true},
      {field: 'statusAktif', title: 'Status', sortable: false, width: 200, align: 'left', search: true,
      rowStyler: function(rowData, rowIndex) {
        return statusAktif(rowData, rowIndex);
      }
    },
    {field: 'menu', title: 'Menu', sortable: false, width: 50, align: 'center', search: false,
    rowStyler: function(rowData, rowIndex) {
      return menu(rowData, rowIndex);
    }
  }
  ]
});
    datagrid.run();
  }

  $(document).ready(function() {
    get_load('Semua');
  });

  function checks(rowData, rowIndex) {
    var id = rowData.id_surat_keterangan;
    var tag = '';
    tag += '<input type="checkbox" value="'+id+'" name="id_data[]" id="id_data'+id+'"/>';
    tag += '<label for="id_data'+id+'"></label>';
    return tag;
  }

  function menu(rowData,rowIndex){
    var html = '';
    html += '<div class="btn-group">' +
    '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></a>' +
    '<ul class="dropdown-menu pull-right">';

    html += '<li><a href="javascript:void(0)" onclick="detail(\''+rowData.id_surat_keterangan+'\')"><i class="fa fa-eye"></i> Rincian</a></li>';

    // if (rowData.status_aktif == 'Aktif' && {{ Auth::getUser()->id_level_user }} != 2) {
    //   html += '<li><a href="javascript:void(0);" onclick="upload_file(\''+rowData.id_surat_keterangan+'\')"><i class="fa fa-upload"></i> Upload File E-Buddy</a></li>';
    // }

    // if (rowData.status_aktif == 'Aktif' && {{ Auth::getUser()->id_level_user }} != 2) {
    //   html += '<li><a target="_blank" href="{{url("/")}}/home/surat_keterangan/document/'+rowData.id_surat_keterangan+'"><i class="fa fa-download"></i> Suket .Doc</a></li>';
    // }

    if ((rowData.disetujui_admin == 'Menunggu' || rowData.status_aktif == 'Tolak') || {{ Auth::getUser()->id_level_user }} != 2) {
      html += '<li><a href="{{ url("/") }}/home/surat_keterangan/edit/'+rowData.id_surat_keterangan+'"><i class="fa fa-edit"></i> Edit Suket</a></li>';
    }

    if (rowData.file_surat_keterangan_asli != null && {{ Auth::getUser()->id_level_user }} != 2) {
      html += '<li><a target="_blank" href="{{ url("/") }}/upload/file_sip_asli/'+rowData.file_surat_keterangan_asli+'"><i class="fa fa-file-pdf-o"></i> Berkas Asli</a></li>';
    }

    @if(Auth::getUser()->id_level_user != 2)
    if (rowData.file_surat_keterangan_salinan != null) {
      html += '<li><a target="_blank" href="{{ url("/") }}/upload/file_sip_salinan/'+rowData.file_surat_keterangan_salinan+'"><i class="fa fa-file-excel-o"></i> Berkas Salinan</a></li>';
    }
    @endif

    if ((rowData.disetujui_admin == 'Menunggu' || rowData.status_aktif == 'Ditolak') || {{ Auth::getUser()->id_level_user }} != 2) {
      html +=     '<li><a href="javascript:void(0);" onclick="deletes(\''+rowData.id_surat_keterangan+'\')"><i class="fa fa-trash-o"></i> Delete</a></li>';
    }      

    html +=  '</ul>' +
    '</div>';
    return html;
  }

  function add_error() {
    swal({
     title:"Data Tidak Lengkap",
     text:"Silahkan lengkap i data pengguna anda terlebih dahulu melaluli pengaturan pengguna.",
     type:"warning"
   },

   function(isConfirm){
    if (isConfirm) {
      window.location.href = "{{url('/')}}/home/users/setting";
    }
  });
  }

  function statusAktif(rowData, rowIndex) {
    var status = rowData.status_aktif;
    if (status == 'Menunggu') {
      return 'Menunggu Pengecekan';
    }else if(status == 'Aktif') {
      return 'Proses Tanda Tangan Basah ';
    }else{
      return status;
    } 
  }

  function deletes(id) {
    swal({
     title:"Hapus data",
     text:"Apakah anda yakin ?",
     type:"warning",
     showCancelButton: true,
     confirmButtonColor: "#DD6B55",
     confirmButtonText: "Saya yakin!",
     cancelButtonText: "Batal!",
     closeOnConfirm: false
   },
   function(){
     $.post("{{ route('delete_surat_keterangan') }}", {id:id}).done(function(data){
       if(data.status == 'success'){
         datagrid.reload();
         swal("Success!", data.message, "success");
       }else{
         swal("Whooops!", data.message, "error");
       }
     });
   });
  }

  function detail(id) {
    $.post("{{ route('detail_surat_keterangan') }}", {id:id}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }


  function upload_file(id) {
    $.post("{{ route('upload_detail_surat_keterangan') }}", {id:id}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }

  $('#jadwalTanggal').click(() => {
    var id = [];
    $(':checkbox:checked').each(function(i){
      id[i] = $(this).val();
    });

    $.post("{{route('jadwalkanTanggalSuket')}}", {id:id}).done(function(data){
      if (data.status == 'success') {
        $('.modal-dialog').html(data.content);
      } else {
        swal('Whooops',data.message,'warning');
      }
    })
  })

  $('#sudahAmbil').click(() => {
    var id = [];
    $(':checkbox:checked').each(function(i){
      id[i] = $(this).val();
    });

    swal({
      title:"Ubah Status",
      text:"Apakah anda yakin ingin merubah Status Menjadi Sudah Diambil ?",
      type:"warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Saya yakin!",
      cancelButtonText: "Batal!",
      closeOnConfirm: false
    },
    function(){

      $.post("{{route('sudahAmbilSuket')}}", {id:id}).done(function(data){
        if (data.status == 'success') {
          swal('Berhasil',data.message,'success');
          datagrid.reload();
        } else {
          swal('Whooops',data.message,'warning');
        }
      })
    })
  })

  $('#batalkan').click(() => {
    var status = $('#filter').val(); 
    if (status && status != 'Semua') {

      var id = [];
      $(':checkbox:checked').each(function(i){
        if ($(this).val() != 'on') { 
          id[i] = $(this).val();
        }
      });

      if (id.length > 0) {

        $.post("{{route('batalkan_keterangan')}}", {id:id,status:status}).done(function(data){
          if (data.status == 'success') {
            $('.modal-dialog').html(data.content);
          } else {
            swal('Whooops',data.message,'warning');
          }
        })
      } else {
        swal('Whooops','Pilih salah satu Pemohon','warning');
      }
    } else {
      swal('Whooops','Pilih salah satu status di Filter Status kecuali Semua','warning');
    }
  })

  function checkAll(t) {
    $('input:checkbox').not(t).prop('checked', t.checked);
  }
</script>
@endsection
