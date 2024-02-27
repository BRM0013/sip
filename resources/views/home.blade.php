  @extends('layouts.admin-template')
  @section('content')

<style type="text/css">
#container {
  height: 500px;
  min-width: 100%;
  max-width: 100%;
  margin: 0 auto;
}
#button-bar {
    min-width: 100%;
    max-width: 100%;
    margin: 0 auto;
}

.panel-pertahun {
    font-size: 14px;
    color: white;
    width: 110px;
    float: right;
    margin-top: 10px;
    margin-left: 20px;
    margin-right: -10px;
    padding-top: 10px;
    height: 100px;
    overflow-y: auto;
  }
  .panel-pertahun > ul {
    padding-left: 10px;
  }
  .panel-pertahun > ul > li {
    list-style-type: none;
  }
</style>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Dashboard
        <small>Dinas Kesehatan Kabupaten Sidoarjo</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">
      @if (Auth::getUser()->id_level_user == 1 || Auth::getUser()->id_level_user == 3 || Auth::getUser()->id_level_user == 4 || Auth::getUser()->id_level_user == 8)
        <div class="row">
          @php
            $surat_menunggu = App\Models\Surat::join('users as u','u.id','surat.id_user')->where('status_aktif', 'Menunggu')->where('status_simpan', 'simpan')->get();
          @endphp

        @if(count($surat_menunggu) > 0)
        <a href="{{route('surat-all')}}?filter=Menunggu" style="text-decoration: none;" title="Ada {{count($surat_menunggu)}} Surat yang masih Menunggu Pengecekan">
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{$total_pengajuan}}</h3>
                <p>Pengajuan</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">

                </div>
              </div>
            </div>
          </div>
        </a>
        @else
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{$total_pengajuan}}</h3>
                <p>Pengajuan</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">

                </div>
              </div>
            </div>
          </div>
        @endif
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-olive">
              <div class="inner">
                <h3>{{$total_pengajuan_disetujui}}</h3>
                <p>Pengajuan Diterima</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">
                 <ul>
                  @if (count($recordAktif) > 0)
                  @foreach ($recordAktif as $key)
                  <li>{{ $key->tahun }} <i class="fa fa-arrow-right p-l-5 p-r-5"></i> {{ $key->jumlah }}</li>
                  @endforeach
                  @endif
                </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-red">
              <div class="inner">
                <h3>{{$total_pengajuan_ditolak}}</h3>
                <p>Pengajuan Ditolak</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">

                </div>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>{{$total_pengajuan_dicabut}}</h3>
                <p>Dicabut</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">

                </div>
              </div>
            </div>
          </div>
          <!-- ./col -->
         <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-purple">
              <div class="inner">
                <h3>{{$total_pengajuan_kedaluarsa}}</h3>
                <p>Kedaluarsa</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">

                </div>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner">
                <h3>{{$total_pengajuan_revisi}}</h3>
                <p>Revisi</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital-o"></i>
                <div class="panel-pertahun">

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="row" style="background: #2c9dff;margin-left: 0px;margin-right: 0px;padding: 5px;">
              <div class="row" style="padding: 10px;">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                      <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                        <div style="padding-left: 0px; padding-right: 5px;" class="col-xs-6">
                          <label>Di wilayah</label>
                          <select id="wilayah" name="wilayah" class="form-control select2">
                            <option value="Diwilayah">Di wilayah</option>
                            <option value="Di Luar wilayah">Dari Luar wilayah</option>
                          </select>
                        </div>
                        <div style="padding-left: 0px; padding-right: 5px;" class="col-xs-6">
                          <label>Provinsi</label>
                          <select id="provinsi" name="provinsi" class="form-control select2">
                            <option value="">..:: Pilih Provinsi ::..</option>
                            <?php foreach ($list_provinsi as $row): ?>
                              <option value="{{ $row->id_provinsi }}">{{ $row->nama_provinsi }}</option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-8">
                        <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                          <label>Kabupaten</label>
                          <select id="kabupaten" name="kabupaten" class="form-control select2">
                            <option value="">..:: Pilih Provinsi ::..</option>
                          </select>
                        </div>
                        <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                          <label>Kecamatan</label>
                          <select id="kecamatan" name="kecamatan" class="form-control select2">
                            <option value="">..:: Pilih Provinsi ::..</option>
                          </select>
                        </div>
                        <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                          <label>Desa</label>
                          <select id="desa" name="desa" class="form-control select2">
                            <option value="">..:: Pilih Provinsi ::..</option>
                          </select>
                      </div>
                    </div>
                      </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <div class="col-sm-12">
                    <div class="col-sm-3" style="padding-right: 5px;">
                      <select id="status_kepegawaian" name="status_kepegawaian" class="form-control select2">
                        <option value="">.:: Status Kepegawaian ::.</option>
                        <option value="PNS">PNS</option>
                        <option value="NON PNS">NON PNS</option>
                      </select>
                    </div>
                    <div class="col-sm-3" style="margin-left: 0px;">
                      <input class="form-control" id="datepicker1" type="text" name="tanggal_awal" placeholder="Mulai tanggal">
                      <small style="font-weight: 900;">*Format Inputan : Tahun - Bulan - Tanggal.</small>
                    </div>
                    <div class="col-sm-3" style="padding-right: 5px;">
                      <input class="form-control" id="datepicker2" type="text" name="tanggal_akhir" placeholder="Sampai tanggal">
                      <small style="font-weight: 900;">*Format Inputan : Tahun - Bulan - Tanggal.</small>
                    </div>
                    <div class="col-sm-3" style="padding-right: 5px;">
                      <input class="btn btn-success" type="button" onclick="tampilkan_grafik()" style="width: 95%;" value="Tampilkan">
                    </div>
                  </div>
              </div>
            </div>
            <div id="container"></div>
          </div>
        </div>
      @elseif (Auth::getUser()->id_level_user == 2)
       @foreach ($list_surat as $key)
            @if (count($key->row) > 0)
              @foreach ($key->row as $syarat_pengajuan)

                <?php if (isset($syarat_pengajuan)): ?>
                    <?php if ($syarat_pengajuan->id_jenis_persyaratan == $syarat_pengajuan->id_jenis_persyaratan): ?>
                      @if($syarat_pengajuan->nama_file_persyaratan != '')
                        @if(file_exists('upload/file_berkas/'.$syarat_pengajuan->nama_file_persyaratan))
                        <!-- <h3 style="text-align: center; font-weight:bold;">Syarat Pengajuan SIP anda Sudah Valid</h3><br>                                                 -->
                        @else
                          <h3 style="text-align: center; font-weight:bold;">Pemberitahuan!, Validasi Berkas persyaratan SIP dan Foto</h3><br>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="box box-info" id="btn-add" style="border-top:none">
                                <h4 style="padding: 10px;margin:0px;color:#fff;background: #f39c12;">
                                  <i class="fa fa-file iconLabel m-r-15"></i> Upload Ulang
                                </h4>
                                <div class="box-body" style="text-align:justify; padding:20px;">
                                    <br><h5><b>Ikuti Langkah Berikut :</b></h5>
                                    <ul>
                                      <li>Silahkan melakukan validasi ulang berkas persyaratan pengjuan SIP anda.<a href="{{ url('/') }}/home/form_validasi/{{$key->id}}"><b>Klik link</b></a></li>
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endif
                    <?php endif ?>
                <?php endif ?>

              @endforeach
            @endif
          @endforeach
          <h3 style="text-align: center; font-weight:bold;">INFORMASI UMUM</h3><br>
          <div class="row">
            <div class="col-md-4">
              <div class="box box-info" id="btn-add" style="border-top:none">
                <h4 style="padding: 10px;margin:0px;color:#fff;background: #3c8dbc;">
                  <i class="fa fa-file iconLabel m-r-15"></i> Pengajuan
                </h4>
                <div class="box-body" style="text-align:justify; padding:20px;">
                    Jika anda ingin melakukan pengajuan, maka harus melengkapi dokumen sesuai dengan persyaratan yang ada.
                    <br><h5>Info penting :</h5>
                    <ul>
                      <li>√. Verifikasi email anda terlebih dahulu.</li>
                      <li>√. Pastikan anda telah melengkapi data diri.</li>
                      <li>√. Anda dapat menyimpan data pengajuan dalam draf jika form belum terisi semua.</li>
                      <li>√. Jika form telah terisi semuanya barulah anda dapat melakukan pengajuan dan pengajuan anda akan diproses.</li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="box box-info" id="btn-add" style="border-top:none">
                <h4 style="padding: 10px;margin:0px;color:#fff;background: #3c8dbc;">
                  <i class="fa fa-clipboard iconLabel m-r-15"></i> Perpanjangan
                </h4>
                <div class="box-body" style="text-align:justify; padding:20px;">
                  Proses perpanjangan dapat dilakukan dalam waktu 6 (enam) bulan sebelum masa berlaku surat telah habis.
                </div>
              </div>
              <div class="box box-info" id="btn-add" style="border-top:none">
                <h4 style="padding: 10px;margin:0px;color:#fff;background: #3c8dbc;">
                   <i class="fa fa-unlink iconLabel m-r-15"> </i>Pencabutan
                </h4>

                <div class="box-body" style="text-align:justify; padding:20px;">
                    Proses pencabutan dapat dilakukan jika sudah tidak dilakukan praktek lagi atau tempat praktek telah pindah.
                  </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="box box-info" id="btn-add" style="border-top:none">
                <h4 style="padding: 10px;margin:0px;color:#fff;background: #3c8dbc;">
                    <i class="fa fa-exchange iconLabel m-r-15"></i> Pindah Tempat Praktek
                </h4>
                <div class="box-body" style="text-align:justify; padding:20px;">
                    Pindah Tempat prakik yang masih dalam satu wilayah kabupaten Sidoarjo
                  </div>
              </div>
            </div>

            <div class="col-md-4">

              </div>

          </div>
      @else
      <h3 style="text-align: center; font-weight:bold;">INFORMASI UMUM</h3><br>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info" id="btn-add" style="border-top:none">
            <h4 style="padding: 10px;margin:0px;color:#fff;background: #3c8dbc;">
              <i class="fa fa-file iconLabel m-r-15"></i> APLIKASI SIP
            </h4>
            <div class="box-body" style="text-align:justify; padding:20px;">
                Sebuah Aplikasi yang di gunakan untuk mengurus Perizinan Surat Praktek Tenaga Kesehatan di Wilayah Dinas Kesehatan Sidoarjo.
            </div>
          </div>
        </div>
      @endif
    </section>

    <div class="modal-dialog"></div>
  </div>
  @endsection

@section('js')
<?php if (Auth::getUser()->id_level_user != 2): ?>
<script>
//Date picker
      $('#datepicker1').datepicker({
        autoclose: true,
        format:'yyyy-mm-dd',
      });

      //Date picker
      $('#datepicker2').datepicker({
        autoclose: true,
        format:'yyyy-mm-dd',
      });

  var category;
  var data_grafik_pengajuan = new Array();
  var data_grafik_pengajuan_disetujui = new Array();
  var data_grafik_pengajuan_ditolak = new Array();
  var data_grafik_pencabutan = new Array();

tampilkan_grafik();
function tampilkan_grafik() {
  $.post("{{ route('grafik') }}", {tanggal_awal:$('#datepicker1').val(), tanggal_akhir:$('#datepicker2').val(), status_kepegawaian:$('#status_kepegawaian').val(), provinsi:$('#provinsi').val(), kabupaten:$('#kabupaten').val(), kecamatan:$('#kecamatan').val(), desa:$('#desa').val(), wilayah:$('#wilayah').val()}).done(function(data){
    var dataku = JSON.parse(data);

    data_grafik_pengajuan = new Array();
    data_grafik_pengajuan_disetujui = new Array();
    data_grafik_pengajuan_ditolak = new Array();
    data_grafik_pencabutan = new Array();

    category = dataku.keys;
    for (var i = 0; i < dataku.keys.length; i++) {
      data_grafik_pengajuan.push(dataku.data[dataku.keys[i]][0]);
      data_grafik_pengajuan_disetujui.push(dataku.data[dataku.keys[i]][1]);
      data_grafik_pengajuan_ditolak.push(dataku.data[dataku.keys[i]][2]);
      data_grafik_pencabutan.push(dataku.data[dataku.keys[i]][3]);
    }

    create_grafik(category, data_grafik_pengajuan, data_grafik_pengajuan_disetujui, data_grafik_pengajuan_ditolak, data_grafik_pencabutan);
  });
}

function create_grafik(category, data_grafik_pengajuan, data_grafik_pengajuan_disetujui, data_grafik_pengajuan_ditolak, data_grafik_pencabutan) {
  var chart = Highcharts.chart('container', {

    chart: {
          type: 'column'
      },

      title: {
          text: 'Grafik Pengajuan Surat Izin Praktik'
      },

      subtitle: {
          text: 'Dinas Kesehatan Sidoarjo'
      },

      legend: {
          align: 'center',
          verticalAlign: 'top',
          layout: 'horizontal',
          y:15
      },

      xAxis: {
          categories: category,
          labels: {
              x: -10
          }
      },

      yAxis: {
          allowDecimals: false,
          title: {
              text: 'Total'
          }
      },

      series: [{
          name: 'Penganjuan',
          data: data_grafik_pengajuan
      }, {
          name: 'Pengajuan Disetujui',
          data: data_grafik_pengajuan_disetujui
      }, {
          name: 'Pengajuan Ditolak',
          data: data_grafik_pengajuan_ditolak
      }, {
          name: 'Dicabut',
          data: data_grafik_pencabutan
      }],

      responsive: {
          rules: [{
              condition: {
                  maxWidth: 500
              },
              chartOptions: {
                  legend: {
                      align: 'center',
                      verticalAlign: 'bottom',
                      layout: 'horizontal'
                  },
                  yAxis: {
                      labels: {
                          align: 'left',
                          x: 0,
                          y: -5
                      },
                      title: {
                          text: null
                      }
                  },
                  subtitle: {
                      text: null
                  },
                  credits: {
                      enabled: false
                  }
              }
          }]
      }
  });
}
</script>
<?php endif ?>

<script type="text/javascript">
  $(document).ready(function() {
    $('.select2').select2();

    //select
    $('#provinsi').change(function(){
      var id = $('#provinsi').val();
      $.post("{{route('get_kabupaten')}}",{id:id},function(data){
        var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              kabupaten+='<option value="'+k.id_kabupaten+'">'+k.nama_kabupaten+'</option>';
            });
          }
        }
        $('#kabupaten').html(kabupaten);
      });
    });

    $('#kabupaten').change(function(){
      var id = $('#kabupaten').val();
      $.post("{{route('get_kecamatan')}}",{id:id},function(data){
        var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              kecamatan+='<option value="'+k.id_kecamatan+'">'+k.nama_kecamatan+'</option>';
            });
          }
        }
        $('#kecamatan').html(kecamatan);
      });
    });


    $('#kecamatan').change(function(){
      var id = $('#kecamatan').val();
      $.post("{{route('get_desa')}}",{id:id},function(data){
        var desa = '<option value="">..:: Pilih Desa ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              desa+='<option value="'+k.id_desa+'">'+k.nama_desa+'</option>';
            });
          }
        }
        $('#desa').html(desa);
      });
    });
  });
</script>

@endsection
