@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <h1>Log Aktifitas</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> Log Aktifitas</li>
    </ol>
  </section>
  <section class="content">
      <div class="box box-info" id="btn-add" style="border-top:none">
        <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
          <i class="fa fa-file iconLabel m-r-15"></i>  Log Aktifitas
        </h4>
      <div class="box-body">
        <div class="col-md-12">
         {{-- DATA GRID --}}
            <div class="col-md-12 form-inline main-layer panelSearch p-10">
              <div class="form-group pull-left"></div>
              <div class="form-group pull-right">
                <select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option"></select>
              </div>
              <div class="form-group pull-right">
                <input type="text" style="text-transform:uppercase" class="input-sm form-control" placeholder="Search" id="search" style="text-transform:uppercase">
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

@endsection
@section('js')
  <script type="text/javascript">
    var datagrid;
    function get_load(url) {

      $("#datagrid").html('');
        datagrid = $("#datagrid").datagrid({
        url                     : url,
        primaryField            : 'id_log_aktifitas',
        rowNumber               : true,
        rowCheck                : false,
        searchInputElement      : '#search',
        searchFieldElement      : '#search-option',
        pagingElement           : '#paging',
        optionPagingElement     : '#option',
        pageInfoElement         : '#info',
        columns                 : [
          {field: 'tanggal', title: 'Tanggal', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'judul', title: 'Judul', editable: false, sortable: true, width: 200, align: 'left', search: true},
          {field: 'keterangan', title: 'Keterangan', editable: false, sortable: true, width: 200, align: 'left', search: true},
        ]
      });
      datagrid.run();
    }

    $(document).ready(function() {
      get_load('{{ route('datagrid_log_aktifitas') }}');
    });
  </script>
@endsection
