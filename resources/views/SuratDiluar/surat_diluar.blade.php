@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <h1>SIP Diluar</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> SIP Diluar</li>
    </ol>
  </section>
  <section class="content">
      <div class="box box-info" id="btn-add" style="border-top:none">
        <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
          <i class="fa fa-file iconLabel m-r-15"></i> SIP Diluar
        </h4>
      <div class="box-body">
        <div class="col-md-12">
         {{-- DATA GRID --}}
            <div class="col-md-12 form-inline main-layer panelSearch p-10">
              <div class="form-group pull-left">
                <a class="btn btn-warning" href="javascript:void(0)" onclick="formInput('0')">
                  <i class="fa fa-plus-square iconLabel" style="margin-right: 15px"></i> Tambah
                </a>
                <a class="btn btn-danger" href="{{ route('surat') }}">
                  <i class="fa fa-chevron-left iconLabel" style="margin-right: 15px"></i>Kembali
                </a>
              </div>
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
<!-- /*`id_surat_diluar`, `id_user`, `tanggal`, `nama_tempat`, `alamat_tempat`, `nomor_str`, `tanggal_berlaku_str`, `status_aktif` FROM `surat_diluar`*/ -->
@endsection
@section('js')
  <script type="text/javascript">
    var datagrid;
    function get_load(url) {
      $("#datagrid").html('');
        datagrid = $("#datagrid").datagrid({
        url                     : url,
        primaryField            : 'id_surat_diluar',
        rowNumber               : true,
        rowCheck                : false,
        searchInputElement      : '#search',
        searchFieldElement      : '#search-option',
        pagingElement           : '#paging',
        optionPagingElement     : '#option',
        pageInfoElement         : '#info',
        columns                 : [
          {field: 'nama_tempat', title: 'Nama Tempat Praktik', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'alamat_tempat', title: 'Alamat Tempat Praktik', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'nomor_str', title: 'Nomor STR', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'tanggal_berlaku_str', title: 'Tanggal Berlaku STR', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'sip_ke', title: 'SIP Ke', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'status_aktif', title: 'Status', editable: false, sortable: true, width: 200, align: 'left', search: true},          
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
      get_load('{{ route('datagrid_surat_diluar') }}');
    });

    function menu(rowData, rowIndex){
      var html = '';
      html += '<div class="btn-group">' +
                '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></a>' +
                '<ul class="dropdown-menu pull-right">';
      html +=     '<li><a href="javascript:void(0);" onclick="formInput(\''+rowData.id_surat_diluar+'\')"><i class="fa fa-pencil"></i> Edit</a></li>';
      html +=     '<li><a href="javascript:void(0);" onclick="deletes(\''+rowData.id_surat_diluar+'\')"><i class="fa fa-trash-o"></i> Delete</a></li>' +
                '</ul>' +
              '</div>';
      return html;
    }

    function formInput(id){
      $.post("{!! route('add_surat_diluar') !!}", {id:id}).done(function(data){
        if(data.status == 'success'){
          $('.modal-dialog').html(data.content);
        }
      });
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
         $.post("{{route('delete_surat_diluar')}}", {id:id}).done(function(data){
           if(data.status == 'success'){
             datagrid.reload();
             swal("Success!", data.message, "success");
           }else{
             swal("Whooops!", data.message, "error");
           }
         });
       });
    }
  </script>
@endsection
