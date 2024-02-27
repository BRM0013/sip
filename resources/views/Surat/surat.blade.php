@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <h1>{{ $jenis_surat->nama_surat }}</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Master Data</a></li>
      <li class="active"> {{ $jenis_surat->nama_surat }}</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-info" id="btn-add" style="border-top:none">
      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
        <i class="fa fa-file iconLabel m-r-15"></i> Data Permohonan Surat</h4>
        <div class="box-body">
          @if(Auth::getUser()->id_level_user == 1 || Auth::getUser()->id_level_user == 8)
          <div class="col-md-12">
            @if(Auth::getUser()->id_level_user == 1 || Auth::getUser()->id_level_user == 8)
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

              @if (Auth::getUser()->id_level_user == 2 && $maksimal_pengajuan < $jenis_surat->maksimal_pengajuan)
                @if(App\Models\Users::is_complite(Auth::getUser()))                    
                <div class="form-group pull-left">
                  <a class="btn btn-warning" href="{{ route('add_surat') }}">
                    <i class="fa fa-plus-square iconLabel" style="margin-right: 15px"></i> Tambah
                  </a>
                </div>
                @else
                <a class="btn btn-warning" onclick="add_error()">
                  <i class="fa fa-plus-square iconLabel" style="margin-right: 15px"></i> Tambah
                </a>
                @endif
              @else
                @if(Auth::getUser()->id_level_user == 2)
                <div class="alert alert-warning" role="alert">
                  Maaf Pengajuan SIP Anda sudah mencapai batas Maksimal. jika ingin membuat SIP baru silahkan lakukan Pencabutan SIP anda sebelumnya.
                </div>
                @endif
              @endif                    

              <!-- <div class="form-group pull-left" style="margin-left: 10px;">
                <a class="btn btn-success" href="{{ route('surat_diluar') }}">
                  Daftar SIP Diluar
                </a>
              </div> -->

              <?php if (Auth::getUser()->id_level_user != 2 && Auth::getUser()->id_level_user != 6 ): ?>
              <div class="col-sm-3">
                <?php $filter = [
                  'Semua', 
                  'Proses Tanda Tanggan Basah', 
                  'Menunggu Pengecekan',
                  'Tolak',
                  'Revisi',
                  'Dicabut',
                  'Kedaluarsa', 
                  'Menunggu Pencabutan',
                  'Dijadwalkan Tanggal',
                  'Sudah Diambil',
                  'Tanggal Terbit'
                ]; ?>
                <?php $value = [
                  'Semua', 
                  'Aktif', 
                  'Menunggu',
                  'Tolak',
                  'Revisi',
                  'Dicabut',
                  'Kedaluarsa', 
                  'Menunggu Pencabutan',
                  'Dijadwalkan Tanggal',
                  'Sudah Diambil',
                  'Tanggal Terbit'
                ]; ?>
                <label>Filter Status</label>
                <br>
                  <?php
                    if (Auth::getUser()->id_level_user == 8) {
                      $idx_selected = 2;
                    } else if(Auth::getUser()->id_level_user == 9) {
                      $idx_selected = 7;
                    } else {
                      $idx_selected = '';
                    }
                  ?>
                  <select id="filter" class="form-control" onchange="get_load(this.value)">
                    <?php for ($i = 0; $i < count($filter); $i++): ?>
                      <option value="{{ $value[$i] }}" {{ $i == $idx_selected ? 'selected' : '' }}>{{ $filter[$i] }}</option>
                    <?php endfor ?>

                  </select>
              </div>

              <?php if (count($list_jenis_praktik) > 1): ?>
                <div class="col-sm-3">
                  <label>Filter Jenis Praktik</label>
                  <br>
                  <select class="form-control"  id="jenis_praktik" name="jenis_praktik" onchange="get_load(this.value)">
                    <option value="Semua">Semua</option>
                    <?php foreach ($list_jenis_praktik as $row): ?>
                      <option value="{{ $row->id_jenis_praktik }}">{{ $row->nama_jenis_praktik }}</option>
                    <?php endforeach ?>
                  </select>
                </div>
                <?php else: ?>
                  <input type='hidden' id='jenis_praktik' value="Semua">
                <?php endif ?>
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
  <!-- Modal Pilih Pencabutan -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pilih Jenis Pencabutan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">          
          <button type="button" class="btn btn-warning surat-pencabutan" onclick="pindah(this)">Pindah Tempat Praktik</button>
          <button type="button" class="btn btn-danger surat-pencabutan" onclick="berhenti(this)">Berhenti</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tanggal Terbit -->
  <div class="modal fade" id="exampleModalTerbit" tabindex="-1" role="dialog" aria-labelledby="exampleModalTerbitLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <div class="form-group" style="margin: 0px;">
              <div class="row">
                <div class="col-sm-12">
                  <label>Tanggal Terbit SIP<span class="colorRed">*</span></label>
                  <input type="text" style="text-transform:uppercase" name="tanggal_terbit" value="" class="form-control input-sm" id="datepicker" placeholder="Tanggal Terbit" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>                  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
<script type="text/javascript">
  var datagrid;
  function get_load(filter) {

    if(filter == 'Tanggal Terbit'){
      $('#exampleModalTerbit').modal('show');          
    }

    $("#datagrid").html('');
    datagrid = $("#datagrid").datagrid({
      url                     : '{{ route("datagrid_surat") }}?id={{ $id_surat }}&filter='+$('#filter').val()+'&praktik='+$('#jenis_praktik').val()+'&tanggal_terbit='+$('input[name=tanggal_terbit]').val(),
      primaryField            : 'id_surat',
      rowNumber               : true,
      rowCheck                : false,
      searchInputElement      : '#search',
      searchFieldElement      : '#search-option',
      pagingElement           : '#paging',
      optionPagingElement     : '#option',
      pageInfoElement         : '#info',
      columns                 : [
      <?php if (Auth::getUser()->id_level_user == 1): ?>    
        {field: 'checks', title: '<input type="checkbox" onclick="checkAll(this)">', editable: false, sortable: false, width: 30, align: 'center', search: false,
        rowStyler: function(rowData, rowIndex) {
          return checks(rowData, rowIndex);
        }
      },
    <?php endif ?>
    {field: 'SetNama', title: 'Nama Pemohon', editable: false, sortable: true, width: 300, align: 'left', search: true,
    rowStyler: function(rowData, rowIndex) {
     return SetNama(rowData, rowIndex);
   }
 },
 {field: 'tanggal_pengajuan', title: 'Tanggal Pengajuan', editable: false, sortable: true, width: 200, align: 'left', search: true},
 {field: 'email', title: 'Email', editable: false, sortable: true, width: 200, align: 'left', search: true},
 {field: 'nomor_str', title: 'Nomor STR', editable: false, sortable: true, width: 200, align: 'left', search: true},
          // {field: 'status_aktif', title: 'Status', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'statusAktif', title: 'Status', sortable: false, width: 200, align: 'left', search: true,
          rowStyler: function(rowData, rowIndex) {
            return statusAktif(rowData, rowIndex);
          }
        },

        <?php if (Auth::getUser()->id_level_user != 6): ?>
          {field: 'menu', title: 'Menu', sortable: false, width: 50, align: 'center', search: false,
          rowStyler: function(rowData, rowIndex) {
            return menu(rowData, rowIndex);
          }
        }
      <?php endif ?>

      ]
    });
    datagrid.run();
  }

  $(document).ready(function() {
    @if(isset($_GET['filter']))
      $('#filter').val('Menunggu')
      get_load("Menunggu");
    @elseif(Auth::getUser()->id_level_user == 8)
      get_load('Menunggu');
    @else
      get_load('Semua');
    @endif

    $('input[name=tanggal_terbit]').change(function() {
      get_load('Tanggal Terbit');
    });
  });

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format:'yyyy-mm-dd',
    });

  function checks(rowData, rowIndex) {
    var id = rowData.id_surat;
    var tag = '';
    tag += '<input type="checkbox" value="'+id+'" name="id_data[]" id="id_data'+id+'"/>';
    tag += '<label for="id_data'+id+'"></label>';
    return tag;
  }

 function statusAktif(rowData, rowIndex) {
    var statusSimpan = rowData.status_simpan;
    if (statusSimpan == 'draf') {
      return 'Simpan Belum Lengkap';
    }else{
      var status = rowData.status_aktif;
      if (status == 'Menunggu') {
        return 'Menunggu Pengecekan';
      } else if (status == 'Aktif') {

        if (status == 'Aktif' && rowData.disetujui_admin == 'Disetujui') {
          return 'Proses Tanda Tangan Basah';
        } else {
          return 'Menunggu Pengecekan';
        }

        // if ((status == 'Aktif' && rowData.disetujui_admin == 'Disetujui' && rowData.disetujui_kabid == 'Disetujui') || (rowData.disetujui_kadinkes == 'Disetujui' && rowData.disetujui_kadinkes == null)) {
        //   return 'Proses Tanda Tangan Basah ';
        // } else {
        //   return 'Menunggu Pengecekan';
        // }
      } else if (status == 'Dijadwalkan Tanggal') {
        return status+' '+rowData.jadwalkan_tanggal;
      } else{
        return status;
      }    
    }
  }
  

  function SetNama(rowData, rowIndex) {
    var gelar_d = rowData.gelar_depan;
    if (gelar_d == null) {
     var gd = '';
   }else{
     var gd = gelar_d;
   }

   var nama = rowData.name;

   var gelar_b = rowData.gelar_belakang;
   if (gelar_b == null) {
     var gb = '';
   }else{
     var gb = gelar_b;
   }

   var gabung = gd+ ' ' +nama+ ' ' +gb;
   return gabung;

 }

 function menu(rowData,rowIndex){
  var html = '';
  html += '<div class="btn-group">' +
  '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></a>' +
  '<ul class="dropdown-menu pull-right">';

  html += '<li><a href="javascript:void(0)" onclick="detail(\''+rowData.id_surat+'\')"><i class="fa fa-eye"></i> Rincian</a></li>';

  if (rowData.status_aktif == 'Menunggu Pencabutan' || rowData.status_aktif == 'Dicabut') {
    html += '<li><a href="javascript:void(0)" onclick="detail_pencabutan(\''+rowData.id_surat+'\')"><i class="fa fa-tv"></i>Rincian Pencabutan</a></li>';
  }

      if (rowData.status_aktif == 'Dicabut' && {{ Auth::getUser()->id_level_user }} != 2) {
        html += '<li><a href="javascript:void(0);" onclick="upload_file(\''+rowData.id_surat+'\')"><i class="fa fa-upload"></i> Upload File Pencabutan</a></li>';
      }

      // if (rowData.status_aktif == 'Dicabut' && {{ Auth::getUser()->id_level_user }} != 2) {
      //   html += '<li><a target="_blank" href="{{url("/")}}/home/surat/list/'+rowData.id_jenis_surat+'/document/'+rowData.id_surat+'"><i class="fa fa-download"></i> Pencabutan .Doc</a></li>';
      // }

      if (rowData.file_surat_sip_asli != null && {{ Auth::getUser()->id_level_user }} != 2) {
        html += '<li><a target="_blank" href="{{ url("/") }}/upload/file_sip_asli/'+rowData.file_surat_sip_asli+'"><i class="fa fa-file-pdf-o"></i> Berkas Asli</a></li>';

        // html += '<li><a target="_blank" href="{{url("/")}}/viewpdf.php?filepath={{ url("/") }}/upload/file_sip_asli/'+rowData.file_surat_sip_asli+'"><i class="fa fa-file-pdf-o"></i> Berkas Asli</a></li>';
      }

      if (rowData.file_surat_sip_salinan != null && {{ Auth::getUser()->id_level_user }} != 2) {
        html += '<li><a target="_blank" href="{{ url("/") }}/upload/file_sip_salinan/'+rowData.file_surat_sip_salinan+'"><i class="fa fa-file-excel-o"></i> Berkas Salinan</a></li>';

        // html += '<li><a target="_blank" href="{{url("/")}}/viewpdf.php?filepath={{ url("/") }}/upload/file_sip_salinan/'+rowData.file_surat_sip_salinan+'"><i class="fa fa-file-excel-o"></i> Berkas Salinan</a></li>';
      }

      if (rowData.file_surat_pencabutan_sip_asli != null && {{ Auth::getUser()->id_level_user }} != 2) {
        html += '<li><a target="_blank" href="{{url("/")}}/viewpdf.php?filepath={{ url("/") }}/upload/file_sip_asli/'+rowData.file_surat_pencabutan_sip_asli+'"><i class="fa fa-file-text-o"></i> Berkas Pencabutan Asli</a></li>';
        
        html += '<li><a target="_blank" href="{{ url("/") }}/upload/file_sip_salinan/'+rowData.file_surat_pencabutan_sip_salinan+'"><i class="fa fa-file-powerpoint-o"></i> Berkas Pencabutan Salinan</a></li>';
      }      

      html += '<li><a href="javascript:void(0)" onclick="daftar_sip_diluar(\''+rowData.id_user+'\')"><i class="fa fa-list-alt"></i> Daftar SIP</a></li>';

      if ((rowData.disetujui_admin == 'Menunggu' || rowData.status_aktif == 'Tolak') || {{ Auth::getUser()->id_level_user }} != 2) {
        var judul_nya = '';
        if(rowData.status_perpanjangan=='Perpanjangan'){
          judul_nya = 'Perpanjangan';
        }else{
          judul_nya = 'Permohonan';
        }
        html += '<li><a href="{{ url("/") }}/home/surat/edit/'+rowData.id_surat+' "><i class="fa fa-pencil"></i> Edit '+judul_nya+'</a></li>';
      }

      if ((rowData.status_aktif == 'Menunggu Pencabutan' && rowData.pencabutan_disetujui_admin == 'Menunggu') || (rowData.status_aktif == 'Menunggu Pencabutan' && {{ Auth::getUser()->id_level_user }} != 2)) {
        html += '<li><a href="{{ url("/") }}/home/surat/edit/'+rowData.id_surat+'/pencabutan"><i class="fa fa-edit"></i>Edit Pencabutan</a></li>';
      }else if(rowData.status_aktif == 'Aktif' || rowData.status_aktif == 'Dijadwalkan Tanggal' || rowData.status_aktif == 'Sudah Diambil'){
        // html += '<li><a href="{{ url("/") }}/home/surat/edit/'+rowData.id_surat+'/pencabutan"><i class="fa fa-eraser"></i> Pencabutan</a></li>';
        html += '<li><a href="javascript:void(0);" onclick="pilihPencabutan(\''+rowData.id_surat+'\')"><i class="fa fa-check-circle"></i> Pencabutan</a></li>';      
      }

      if (rowData.status_tenggang == true) {
        html += '<li><a href="{{ url("/") }}/home/surat/edit/'+rowData.id_surat+'/perpanjangan "><i class="fa fa-hourglass-start"></i> Perpanjangan</a></li>';
      }

      if ((rowData.status_aktif == 'Aktif' && {{ Auth::getUser()->id_level_user }} == 2) || (rowData.status_aktif == 'Revisi' && {{ Auth::getUser()->id_level_user }} == 1)) {
        html += '<li><a href="javascript:void(0);" onclick="formRevisi(\''+rowData.id_surat+'\')"><i class="fa fa-check-circle"></i> Revisi</a></li>';
      }

      if ((rowData.disetujui_admin == 'Menunggu' || rowData.status_aktif == 'Tolak') || {{ Auth::getUser()->id_level_user }} != 2) {
        html +=     '<li><a href="javascript:void(0);" onclick="deletes(\''+rowData.id_surat+'\')"><i class="fa fa-trash-o"></i> Delete</a></li>';
      }

      html +=  '</ul>' +
      '</div>';
      return html;
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
       $.post("{{ route('delete_surat') }}", {id:id}).done(function(data){
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
      $.post("{{ route('detail_surat') }}", {id:id}).done(function(data){
        if(data.status == 'success'){
          $('.modal-dialog').html(data.content);
        }
      });
    }

    function formRevisi(id){
      $.post("{{ route('revisi_surat') }}", {id:id}).done(function(data){
        if(data.status == 'success'){
          $('.modal-dialog').html(data.content);
        }
      });
    }

    function detail_pencabutan(id) {
      $.post("{{ route('detail_pencabutan_surat') }}", {id:id}).done(function(data){
        if(data.status == 'success'){
          $('.modal-dialog').html(data.content);
        }
      });
    }

    function add_error() {
      swal({
       title:"Data Tidak Lengkap",
       text:"Silahkan lengkapi data pengguna anda terlebih dahulu melaluli pengaturan pengguna.",
       type:"warning"
     },

     function(isConfirm){
      if (isConfirm) {
        window.location.href = "{{url('/')}}/home/users/setting";
      }
    });
    }

    function daftar_sip_diluar(id){
      $.post("{!! route('daftar_surat_diluar') !!}", {id:id}).done(function(data){
        if(data.status == 'success'){
          $('.modal-dialog').html(data.content);
        }
      });
    }

    function upload_file(id) {
      $.post("{{ route('upload_detail_file_pencabutan') }}", {id:id}).done(function(data){
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

      $.post("{{route('jadwalkanTanggal')}}", {id:id}).done(function(data){
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

        $.post("{{route('sudahAmbil')}}", {id:id}).done(function(data){
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

          $.post("{{route('batalkan')}}", {id:id,status:status}).done(function(data){
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

  function pilihPencabutan(id){    
    $('#exampleModal').modal('show')
    $('.surat-pencabutan').attr('data-id',id)
  }
  function berhenti(e){
    var id_surat = $(e).attr('data-id')
    var url = '{{  url("/") }}';
    var urs = `${url}/home/surat/edit/${id_surat}/pencabutan`;
    window.location.href = urs;
  }

  function pindah(e){
    var id_surat = $(e).attr('data-id')
    var url = '{{  url("/") }}';
    var urs = `${url}/home/surat/edit/${id_surat}/pencabutan_pindah`; // route yg ada parameternya bisa ditulis gini mbak
    $.get(urs).done(function(data){
      if(data.status == 'warning'){
        swal('Whooops!',data.message,'warning'); // mau munculkan swal kalau datanya blm lengkap
      }else{
        window.location.href = urs;
      }
    });    
  }  
  </script>
  @endsection
