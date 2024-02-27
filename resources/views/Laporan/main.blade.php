@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <h1>Laporan SIP</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> Laporan SIP</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-info" id="btn-add" style="border-top:none">
      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #aea9e1; text-shadow: 0 1px 0 #aecef4;">
        <i class="fa fa-file iconLabel m-r-15"></i>  Laporan SIP
      </h4>
      <div class="box-body">
        <div class="col-md-12">
          <div class="col-lg-3">
            <select class="form-control profesi" id="getVal">
              <option value="">..:: Pilih Profesi ::..</option>
              <option value="All">Semua</option>
              <?php 
              if($jenis->count()!=0){
                foreach ($jenis as $key) {
                  ?>
                  <option value="{{$key->id_jenis_surat}}">{{$key->nama_surat}}</option>
                  <?php
                }
              }
              ?>
            </select>
          </div>

          <div class="col-lg-3">
            <select class="form-control status" id="getValStatus">
              <option value="">..:: Pilih Status SIP ::..</option>
              <option value="All">Semua</option>
              <option value="Aktif">Proses Tanda Tanggan Basah</option>
              <option value="Menunggu">Menunggu Pengecekan</option>
              <option value="Dijadwalkan Tanggal">Menunggu Pengecekan</option>
              <option value="Sudah Diambil">Sudah Diambil</option>
              <option value="Dicabut">Dicabut</option>
              <option value="Tolak">Tolak</option>
              <option value="Kedaluarsa">Kedaluarsa</option>           
            </select>
          </div>

          <div class="col-lg-3 co-md-3">
            <input type="text" class="form-control mulai" placeholder="Tanggal mulai">
          </div>
          <div class="col-lg-3 co-md-3">
            <input type="text" class="form-control akhir" placeholder="Tanggal akhir">
          </div>
          <div class="clearfix"></div>
          </br>
          <div class="col-lg-4 co-md-4">
            <a href="javascript:void(0)" onclick="cari()" class="btn btn-primary cari">Cari</a>
            <a href="javascript:void(0)" onclick="stoping()" class="btn btn-danger stop" style="display: none">Stop</a>
            <a href="javascript:void(0)" onclick="ExportToExcel()" class="btn btn-success excel" style="display: none">Excel</a>
          </div>
          <div class="clearfix"></div>          

          <div id="my-table-id">                        
            <div align="center" class="judul">
                <p align="center" style="font-size:16pt;font-weight: bold;height: 25px;margin: 0;">LAPORAN SIP Dinas Kesehatan Sidoarjo</p>
                <p align="center" style="font-size:14pt;text-transform:uppercase;font-weight: bold;height: 25px;margin: 0;" id="nama_surat"></p>
                <p align="center" style="font-size:12pt;height: 25px;margin: 0;font-weight: bold;" id="lokasi_bulan"></p>
            </div>
            <br/>            
            <div class="col-lg-12 col-md-12 main-layer" style="overflow: auto">
              <table class="table table-bordered" border="1">
                <thead>
                  <th>No</th>
                  <th>Nama Lengkap, Gelar</th>
                  <th>Status Aktif</th>
                  <th>Tanggal Penetapan</th>
                  <th>Profesi</th>
                  <th>No SIP</th>
                  <th>Jenis Kelamin</th>
                  <th>Tempat Lahir</th>
                  <th>Tanggal Lahir</th>
                  <th>Alamat Rumah</th>
                  <th>Alamat Domisili</th>
                  <th>No Telepon</th>
                  <th>Email</th>
                  <th>Jabatan</th>
                  <th>Jenis Sarana</th>
                  <th>Nama Tempat Praktik</th>
                  <th>Alamat Tempat Praktik</th>
                  <th>No STR</th>
                  <th>Masa Berlaku STR</th>
                  <th>No Rekomendasi OP</th>
                  <th>No KTP</th>
                  <th>Pendidikan Terakhir</th>
                  <th>Status Kepegawaian</th>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-12 col-md-12 loading" style="display:none">
            <h1 style="margin: auto;text-align: center">
              <i class="fa fa-refresh fa-spin"></i><br>
              <i class="persen"></i>%
            </h1>
          </div>
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

  // $('.profesi').chosen();
  $('.mulai').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd',
  });

  $('.akhir').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd',
  });

  var id_surat = [];
  var stop = 0;

  function cari(){

    $('.main-layer').hide();
    $('.loading').show();
    $('.cari').hide();
    $('.stop').show();
    $('.excel').hide();   

    stop = 0;

    var profesi = $('.profesi').val();
    var mulai = $('.mulai').val();
    var akhir = $('.akhir').val();
    var status = $('.status').val();

    id_surat = [];

    if(profesi=='' || mulai=='' || akhir=='' || status==''){
      swal('Whooops','kolom ada yang kosong','error');
    }else{
      if(mulai>akhir){
        swal('Whooops','tangal mulai melebihi tanggal akhir','error');
      }else{
        var data = {
          profesi:profesi,
          mulai:mulai,
          akhir:akhir,
          status:status
        };

        $.post("{{route('data-laporan-admin')}}",data,function(data){
          
          $('#lokasi_bulan').html(data.lokasi_bulan);         
          if (data.profesi == 'SEMUA PROFESI') {
            $('#nama_surat').html(data.profesi);     
          }else{
            $('#nama_surat').html(data.profesi.nama_surat);          
          }
          
          $('tbody').html('');
          if(data.surat.length!=0){           
            $.each(data.surat,function(k,v){
              id_surat.push(v.id_surat);
            });            
            baris(0);
          }else{
            swal('Maaf','Tidak Ada Data Pada Tanggal Tersebut','error');
            $('.main-layer').show();
            $('.loading').hide();
            $('.cari').show();
            $('.stop').hide();
            $('.excel').hide();
            // $('.content-wrapper').reload();
          }
        });
      }
    }
  }

  function baris(id){
    var persen = 0;
    if(id<id_surat.length){
      if(stop==0){
        $.post("{{route('baris-laporan-admin')}}",{id:id_surat[id],index:id},function(data){
          $('tbody').append(data);
          baris((id+1));
        }).fail(function(){
          baris(id);
        });
        persen = (id/id_surat.length)*100;
        $('.persen').html(parseInt(persen));
      }else{
        $('.main-layer').show();
        $('.loading').hide();

        $('.cari').show();
        $('.stop').hide();
        $('.excel').show();
         $('.judul').show();
      }
    }else{
      $('.main-layer').show();
      $('.loading').hide();

      $('.cari').show();
      $('.stop').hide();
      $('.excel').show();
       $('.judul').show();
    }
  }

  function stoping(){
    stop = 1;
    
    id_surat = [];

    console.log(stop);   
    $('.cari').show();
    $('.stop').hide();
    $('.excel').show();
     $('.judul').show();
  }

  function ExportToExcel(){
    var htmltable= document.getElementById('my-table-id');
    htmltable.setAttribute('border','1');
    var html = htmltable.outerHTML;
    window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
  }
</script>
@endsection
