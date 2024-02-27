@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Master Data</a></li>
    </ol>
  </section>
  <section class="content">
      <div class="box box-info" id="btn-add" style="border-top:none">
        <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
        <i class="fa fa-file iconLabel m-r-15"></i> Data Surat Izin Praktik Di Rumah Sakit</h4>
      <div class="box-body">

        <div class="col-md-12">
         {{-- DATA GRID --}}
            <div class="col-md-12 form-inline main-layer panelSearch p-10">

              <div class="form-group pull-right">
                <select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option"></select>
              </div>
              <div class="form-group pull-right">
                <input type="text" style="text-transform:uppercase" class="input-sm form-control" placeholder="Search" id="search">
              </div>
            </div>
            <div class='clearfix'></div>
            <div class="col-md-12 p-0">
              <div>
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
    function get_load(url) {
      $("#datagrid").html('');
        datagrid = $("#datagrid").datagrid({
        url                     : url,
        primaryField            : 'id_surat',
        rowNumber               : true,
        rowCheck                : false,
        searchInputElement      : '#search',
        searchFieldElement      : '#search-option',
        pagingElement           : '#paging',
        optionPagingElement     : '#option',
        pageInfoElement         : '#info',
        columns                 : [
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
        ]
      });
      datagrid.run();
    }

    $(document).ready(function() {
      get_load('{{ route('datagrid_surat_rs') }}');
    });

    function statusAktif(rowData, rowIndex) {
      var statusSimpan = rowData.status_simpan;
      if (statusSimpan == 'draf') {
        return 'Simpan Belum Lengkap';
      }else{
        var status = rowData.status_aktif;
        if (status == 'Menunggu') {
          return 'Menunggu Pengecekan';
        } else if (status == 'Aktif') {
          return 'Proses Tanda Tangan Basah ';
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
  </script>
@endsection
