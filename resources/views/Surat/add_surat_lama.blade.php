@extends('layouts.admin-template')
@section('content')
<?php
$id_surat               = '0';
$id_jenis_sarana        = '';
$nama_tempat_praktik    = '';
$alamat_tempat_praktik  = '';
$keterangan_jenis_praktik = '';
$nomor_str              = '';
$nomor_op               = '';
$waktu_praktik          = '';
$id_jenis_praktik       = '';
$sip_ke                 = count($list_surat)+1;
$sebagai_jabatan        = '';
$tanggal_berlaku_str    = '';
// $fasyankes_id         = '';


if (isset($surat)) {
  $id_surat                       = $surat->id_surat;
  $id_jenis_sarana                = $surat->id_jenis_sarana;
  $nama_tempat_praktik            = $surat->nama_tempat_praktik;
  $alamat_tempat_praktik          = $surat->alamat_tempat_praktik;
  $keterangan_jenis_praktik       = $surat->keterangan_jenis_praktik;
  $nomor_str                      = $surat->nomor_str;
  $nomor_op                       = $surat->nomor_op;
  $waktu_praktik                  = $surat->waktu_praktik;
  $id_jenis_praktik               = $surat->id_jenis_praktik;
  $sip_ke                         = $surat->sip_ke;
  $sebagai_jabatan                = $surat->sebagai_jabatan;
  $tanggal_berlaku_str            = $surat->tanggal_berlaku_str;

  // if (!empty($surat->fasyankes_id)) {
  //   $fasyankes_id                   = $surat->fasyankes_id;        
  // }
}
?>

<style type="text/css">
  .colorRed { color: #f00 !important; }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Pengajuan {{ $jenis_surat->nama_surat }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#"> Surat Izin</a></li>
      <li class="active"> Pengajuan {{ $jenis_surat->nama_surat }}</li>
    </ol>
  </section>

  <section class="content">
    <div class="box box-info" id="btn-add" style="border-top:none">
      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;"><i class="fa fa-plus-square iconLabel m-r-15"></i> Formulir Pengajuan {{ $jenis_surat->nama_surat }}</h4>
      <form class="form" action="{{ route('save_surat') }}{{$perpanjangan}}" enctype="multipart/form-data" method="post">
        @csrf
        <input type="hidden" name="id_surat" value="{{ $id_surat }}">
        <div class="box-body">
          <div class="col-md-6 col-xs-12">
            <h4 style="font-weight: bold;">Data Pribadi</h4>

            <div class="form-group" style="margin: 0px;">
              <label>Nama Lengkap<span class="colorRed">*</span></label>
              <input readonly type="text" class="form-control input-sm" value="{{$users->name}}">
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <div class="form-group" style="margin: 0px;">
              <label>Tempat Lahir<span class="colorRed">*</span></label>
              <input readonly type="text" class="form-control input-sm" value="{{$users->tempat_lahir}}">
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <div class="form-group" style="margin: 0px;">
              <label>Tanggal Lahir<span class="colorRed">*</span></label>
              <input readonly type="text" class="form-control input-sm" value="{{$users->tanggal_lahir}}">
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <div class="form-group" style="margin: 0px;">
              <label>Jenis Kelamin<span class="colorRed">*</span></label>
              <input readonly type="text" class="form-control input-sm" value="{{$users->jenis_kelamin}}" style="text-transform:uppercase">
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <div class="form-group" style="margin: 0px;">
              <label>Alamat Rumah (Sesuai KTP)<span class="colorRed">*</span></label>
              <textarea style="text-transform:uppercase" class="form-control" style="text-transform: lowercase;" class="form-control" readonly>{{$users->alamat_jalan_rt_rw}}, {{ $users->desa->nama_desa }}, {{ $users->kecamatan->nama_kecamatan }}, {{ $users->kabupaten->nama_kabupaten }}, {{ $users->provinsi->nama_provinsi }}</textarea>
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <div class="form-group" style="margin: 0px;">
              <label>Alamat Rumah Domisili<span class="colorRed">*</span></label>
              <textarea style="text-transform:uppercase" class="form-control" class="form-control" readonly>{{$users->alamat_domisili}}</textarea>
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <div class="form-group" style="margin: 0px;">
              <label>No. Telepon/HP<span class="colorRed">*</span></label>
              <input readonly type="text" class="form-control input-sm" value="{{$users->nomor_telpon}}">
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>

            <h4 style="font-weight: bold;">Tempat Praktik Saya</h4>
            <?php if (count($list_surat) > 0): ?>
              <?php foreach ($list_surat as $row): ?>
                <?php if ($row->sip_ke != $sip_ke): ?>
                  <div class="form-group" style="margin: 0px;">
                    <div class="col-sm-1"><?php echo $row->sip_ke ?>.</div>
                    <div class="col-sm-11">
                      <b><?php echo $row->nama_tempat_praktik ?></b>
                      <br>
                      (<?php echo $row->nomor_str ?>)
                      <br>
                      <?php echo $row->alamat_tempat_praktik ?>
                    </div>
                  </div>
                <?php endif ?>
              <?php endforeach ?>
              <?php else: ?>
                <?php echo 'Anda belum memiliki tempat praktik.' ?>
              <?php endif ?>
              <div class="clearfix" style="margin-bottom: 10px;"></div>
            </div>

            <div class="col-md-6 col-xs-12">
              <h4 style="font-weight: bold;">Formulir Pengajuan</h4>

              <div class="form-group" style="margin: 0px;">
                <label>Surat Izin Ke (Sesuai nomor STR)<span class="colorRed">*</span></label>
                <input type="text"name="sip_ke" class="form-control input-sm disablecopypaste" value="{{ $sip_ke }}" onkeyup="return cekAngka(event)" maxlength="1" id="pilihan">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <div class="row">
                  <div class="col-sm-6" id="panel1">
                    <div class="form-group" style="margin: 0px;">
                      <label>
                        Upload File SIP ke-1<span class="colorRed">*</span><br>
                        <span style="color:red">* Maksimal 2 MB</span>
                      </label>
                      <!-- onchange="Filevalidation()" -->
                      <!-- onchange="checkSize('sip_1')" -->
                      <input type="file" name="sip_1" id="sip_1" class="form-control input-sm" accept="image/*,.pdf">
                      <span id="info-file-sip_1" style="color:red; display:none;">File yang anda masukan lebih dari 2MB</span>
                      @if(isset($surat))
                        @if($surat->sip_1!='')
                          @if(file_exists('uploads/file_berkas/'.$surat->sip_1))
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" onclick="modal_sip('Sip 1','{{$surat->sip_1}}')">FIle terlampir</button>
                            <!-- <div id="demo_1" class="collapse">
                              <img src="{{url('uploads/file_berkas/'.$surat->sip_1)}}" style="width: 100%">
                            </div> -->
                          @endif
                        @endif
                      @endif
                      <p id="size"></p>
                    </div>
                  </div>
                  <div class="col-sm-6" id="panel2">
                    <div class="form-group" style="margin: 0px;">
                      <label>
                        Upload File SIP ke-2<span class="colorRed">*</span><br>
                        <span style="color:red">* Maksimal 2 MB</span>
                      </label>
                      <!-- onchange="Filevalidation2()" -->
                      <!-- onchange="checkSize('sip_2')" -->
                      <input type="file" name="sip_2" id="sip_2" class="form-control input-sm" accept="image/*,.pdf">
                      <span id="info-file-sip_2" style="color:red; display:none;">File yang anda masukan lebih dari 2MB</span>
                      @if(isset($surat))
                      @if($surat->sip_2!='')
                      @if(file_exists('uploads/file_berkas/'.$surat->sip_2))
                      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" onclick="modal_sip('Sip 2','{{$surat->sip_2}}')">File terlampir</button>
                      <!-- <div id="demo_2" class="collapse">
                        <img src="{{url('uploads/file_berkas/'.$surat->sip_2)}}" style="width: 100%">
                      </div> -->
                      @endif
                      @endif
                      @endif
                      <p id="size2"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Surat Tanda Registrasi</label>
                <div class="row">
                  <div class="col-sm-7">
                    <label>Nomor STR<span class="colorRed">*</span></label>
                    <input type="text"name="nomor_str" class="form-control input-sm disablecopypaste" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $nomor_str; } ?>" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
                  </div>
                  <div class="col-sm-5">
                    <label>Masa Berlaku STR<span class="colorRed">*</span></label>
                    <input type="text" style="text-transform:uppercase" name="tanggal_berlaku_str" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $tanggal_berlaku_str; } ?>" class="form-control input-sm" id="datepicker" placeholder="Tanggal STR">
                    <small style="font-weight: 900; color: red;">*Format Inputan : Tahun - Bulan - Tanggal.</small>
                  </div>
                </div>
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Sebagai / Jabatan<span class="colorRed">*</span></label>
                <input type="text"name="sebagai_jabatan" class="form-control input-sm disablecopypaste" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $sebagai_jabatan; } ?>" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Nomor Rekomendasi Organisasi Profesi<span class="colorRed">*</span></label>
                <input type="text"name="nomor_op" class="form-control input-sm disablecopypaste" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $nomor_op; } ?>" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Nama Tempat Praktik<span class="colorRed">*</span></label>
                <input type="text"name="nama_tempat_praktik" class="form-control input-sm disablecopypaste" value="{{ $nama_tempat_praktik }}" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Alamat Tempat Praktik<span class="colorRed">*</span></label>
                <input type="text"name="alamat_tempat_praktik" class="form-control input-sm disablecopypaste" value="{{ $alamat_tempat_praktik }}" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              @if($users->id_jenis_surat == 7 || $users->id_jenis_surat == 8 || $users->id_jenis_surat == 14 || $users->id_jenis_surat == 24 )
              <div class="form-group" style="margin: 0px;">
                <label>Jenis Praktik<span class="colorRed">*</span></label>
                <input type="text"name="keterangan_jenis_praktik" class="form-control input-sm disablecopypaste" value="{{ $keterangan_jenis_praktik }}" style="text-transform:uppercase" placeholder="Tambahkan Jenis Praktik" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>
              @endif

              <div class="form-group" style="margin: 0px;">
                <label>Jenis Sarana<span class="colorRed">*</span></label>
                <select name="jenis_sarana" class="form-control" style="text-transform:uppercase">
                  <?php foreach ($list_jenis_sarana as $row): ?>
                    <option <?php if ($row->id_jenis_sarana == $id_jenis_sarana){ echo "selected='selected'"; } ?> value="{{$row->id_jenis_sarana}}" >{{$row->nama_sarana}}</option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>  

               <!-- <div class="form-group" style="margin: 0px;">
                <label>Pilih Fasyankes / Rumah Sakit<small style="color: red;">*</small></label>
                <select name="fasyankes_id" class="form-control" style="text-transform:uppercase">
                  <?php foreach ($list_fasyankes as $row): ?>
                      <option value="{{ $row->id_fasyankes }}">{{ $row->nama }}</option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>            -->

              <?php if (count($list_ts_praktik) > 1){ ?>
                <div class="form-group" style="margin: 0px;">
                  <label>Jenis Praktik<span class="colorRed">*</span></label>
                  <select name="jenis_praktik" class="form-control">
                    <?php foreach ($list_ts_praktik as $row): ?>
                      <option <?php if ($row->id_jenis_praktik == $id_jenis_praktik){ echo "selected='selected'"; } ?> value="{{$row->id_jenis_praktik}}" >{{$row->nama_jenis_praktik}}</option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="clearfix" style="margin-bottom: 10px;"></div>
              <?php }else{ ?>
                <input type="hidden" name="jenis_praktik" value="{{$list_ts_praktik[0]->id_jenis_praktik}}">
              <?php } ?>

              <input type="hidden" id="input_waktu_praktik" name="waktu_praktik" value="">
                <?php if ($jenis_surat->jenis_waktu_praktik == "Waktu Praktik dengan Shift"): ?>
                <div class="form-group" style="margin: 0px;">
                  <label>Waktu Praktik<span class="colorRed">*</span></label>
                  <div id="waktu_praktik">
                    <?php if (empty($waktu_praktik)): ?>
                      <table id="waktu_praktik_shift" style="width: 100%;">
                        <tr class="tr_hari_shift_0">
                          <td>Hari</td>
                          <td class="to_text_shift" id="td_hari_0" colspan="2">
                            <div class="row">
                              <div style="margin-left: 30px;" class="col-sm-10">
                                <input id="input_hari_0" type="text" name="" class="form-control to_text_shift disablecopypaste" placeholder="Senin s.d Sabtu">
                              </div>
                              <div style="padding-left: 0px;" class="col-sm-1">
                                <a href="javascript:void(0)" onclick="remove_waktu_praktik_shift('tr_hari_shift_0')" style="width: 39px;" class="btn btn-warning"><i class="fa fa-eraser"></i></a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="tr_hari_shift_0">
                          <td>Jam</td>
                          <td>: Shift I</td>
                          <td class="to_text_shift" id="td_jam_shift_0_1">
                            <div class="row">
                              <div style="padding-right: 0px;" class="col-sm-10">
                                <input id="input_jam_shift_0_1" type="text" name="" class="form-control to_text_shift disablecopypaste" placeholder="07.00 - 14.00">
                              </div>
                              <div class="remove_add_shift" style="padding: 0px;" class="col-sm-1">
                                <a href="javascript:void(0)" onclick="add_waktu_praktik_shift_kolom(1, '0')" style="margin-left: 16px;" class="btn btn-success"><i class="fa fa-plus-square"></i></a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </table>
                      <?php else: ?>
                        <?php echo $waktu_praktik ?>
                      <?php endif ?>
                    </div>
                    <a href="javascript:void(0)" onclick="add_waktu_praktik_shift()" style="margin-top: 20px;" class="form-control btn btn-primary">Tambah Hari Kerja</a>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                <?php endif ?>

                <?php if ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik'): ?>
                 <div class="form-group" style="margin: 0px;">
                  <label>Waktu Praktik<span class="colorRed">*</span></label>
                  <div id="waktu_praktik">
                    <?php if (empty($waktu_praktik)): ?>
                      <table id="table_waktu_praktik" style="width: 100%;">
                        <tr>
                          <td><b>Hari : </b></td>
                          <td><b>Jam : </b></td>
                        </tr>
                        <tr id="tr_waktu_praktik_0">
                          <td class="to_text" id="td_hari_0"><input id="input_hari_0" type="text" name="" class="form-control to_text" placeholder="Senin s.d Sabtu"></td>
                          <td class="to_text" id="td_jam_0">
                            <div class="row">
                              <div style="padding-right: 0px;" class="col-sm-10">
                                <input id="input_jam_0" type="text" name="" class="form-control to_text" placeholder="07.00 - 14.00">
                              </div>
                              <div style="padding-left: 0px;" class="col-sm-1">
                                <a href="javascript:void(0)" onclick="remove_waktu_praktik('tr_waktu_praktik_0')" class="btn btn-warning"><i class="fa fa-eraser"></i></a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </table>
                      <?php else: ?>
                        <?php echo $waktu_praktik ?>
                      <?php endif ?>
                    </div>
                    <a href="javascript:void(0)" onclick="add_waktu_praktik()" style="margin-top: 20px;" class="form-control btn btn-primary">Tambah Hari Kerja</a>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                <?php endif ?>               
              </div>

              <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>
              <h4 style="font-weight: bold;">Upload Dokumen Pendukung</h4>

              <?php
              $no = 1;
              $jumlah = count($berkas_persyaratan)/2;
              ?>
              <?php for ($i = 0; $i < count($berkas_persyaratan); $i++){ ?>
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label class="col-sm-12 control-label">
                      <?php echo $no++ ?>. <?php echo $berkas_persyaratan[$i]->nama_jenis_persyaratan; ?>
                      <span class="colorRed">* Mak File 2MB</span></br>                      
                    </label>
                    <div class="col-sm-12">
                      <input <?php if (empty($surat) || $perpanjangan == '/perpanjangan')?> style="border: solid 1px #d2d6de; padding: 5px;" accept="<?php echo $berkas_persyaratan[$i]->jenis_input; ?>" type="file" onchange="checkSize('<?php echo str_replace('/', '', $berkas_persyaratan[$i]->nama_variable); ?>')" id="<?php echo str_replace('/', '', $berkas_persyaratan[$i]->nama_variable); ?>" name="<?php echo $berkas_persyaratan[$i]->nama_variable; ?>" class="form-control input-sm input-berkas-pengajuan">
                      <span class="colorRed">*Extensions : PDF / JPG / JPEG / PNG</span>
                      <span id="info-file-<?php echo str_replace('/', '', $berkas_persyaratan[$i]->nama_variable); ?>" style="color:red; display:none;">File yang anda masukan lebih dari 2MB</span>
                      <?php if (isset($syarat_persyaratan)): ?>
                        <?php foreach ($syarat_persyaratan as $row2): ?>
                          <?php if ($row2->id_jenis_persyaratan == $berkas_persyaratan[$i]->id_jenis_persyaratan): ?>
                            <?php $status = 'Tidak ada file'; ?>

                            @if($row2->nama_file_persyaratan != '')
                            @if(file_exists('upload/file_berkas/'.$row2->nama_file_persyaratan))
                            <?php $status = '<a href="javascript:void(0)" onclick="view_lapiran(\''.$row2->id_jenis_persyaratan.'\', \''.$surat->id_surat.'\')" class="btn btn-sm btn-success">File terlampir</a>'; ?>
                            @endif
                            @endif

                            {!! $status !!}
                          <?php endif ?>
                        <?php endforeach ?>
                      <?php endif ?>
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                </div>
              <?php } ?>
              <div class="clearfix"></div>
            </div>

            <div class="box-footer">
              <div class="form-group" style="float: left;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="status_simpan" value="Simpan" id="invalidCheck2">
                    <label class="form-check-label" for="invalidCheck2">
                      Dengan menekan tombol <b style="color: red;">*Ajukan Permohonan </b> anda harus mengisi semua colom yang tersedia.
                    </label>
                  </br>
                  <label class="form-check-label">
                    NB : Tekan tombol <b style="color: red;">*Draf </b> jika permohonan anda belum terisi semua.
                  </label>
                </div>
              </div>

              <?php if ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik'): ?>
                <button id="button-submit-sip" type="submit" onclick="remove_input_waktu_praktik()" class="btn btn-primary pull-right btn-simpan" style="margin-left: 15px;">Ajukan Permohonan
                <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button> 
                &nbsp;
                <button id="button-submit-draf" type="submit" class="btn btn-success pull-right btn-draf" style="margin-left: 15px;">Draf
                <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>

              <?php elseif ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik dengan Shift'): ?>
                <button id="button-submit-sip" type="submit" onclick="remove_input_waktu_praktik_shift()" class="btn btn-primary pull-right btn-simpan" style="margin-left: 15px;">Ajukan Permohonan
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                &nbsp;
                <button id="button-submit-draf" type="submit" class="btn btn-success pull-right btn-draf" style="margin-left: 15px;">Draf
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>

              <?php else: ?>
                <button id="button-submit-draf" type="submit" class="btn btn-success pull-right btn-draf" style="margin-left: 15px;">Draf
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                &nbsp;
                 <button id="button-submit-sip" type="submit" class="btn btn-primary pull-right btn-simpan" style="margin-left: 15px;">Ajukan Permohonan
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>

                <?php endif ?>
                <a href="@if(isset($surat)){{route('surat')}}/list/{{$surat->id_jenis_surat}}@else{{route('surat')}}@endif" class="btn btn-warning btn-cencel pull-right">
                  <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                </a>
            </div>
      </form>
    </div><!-- /.box -->
  </section>
</div>

<div class="modal-dialog"></div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" style="overflow: auto;height: 60vh">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>      

@endsection
@section('js')
<script type="text/javascript">

    $(document).ready(function () {
      $('input.disablecopypaste').bind('copy paste', function (e) {
         e.preventDefault();
      });
      $('textarea.disablecopypaste').bind('copy paste', function (e) {
         e.preventDefault();
      });
    });

    function cekAngka(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
      return true;
    }

    function cekAngkaHuruf(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) || (charCode == 44 || charCode == 46)  || charCode == 32 || charCode == 45 || charCode == 47 || charCode == 64){
        return true;
      }else{
        return false;
      }
    }

    function modal_sip(title,image){
      $('.modal-title').html(title);
    //   var content = '<img src="{{url('/')}}/uploads/file_berkas/'+image+'" style="width: 100%">';
        var content = '';
        var file = image.split(".");
        if (file[1] == 'pdf' || file[1] == 'PDF') {
            content = '< src="{{ url('/') }}/uploads/file_berkas/'+image+'" width="100%" height="950px"></iframe>';

            // content = '< src="{{url('/')}}/viewpdf.php?filepath={{ url('/') }}/uploads/file_berkas/'+image+'" width="100%" height="950px"></iframe>';
        } else {
            content = '<img src="{{url('/')}}/uploads/file_berkas/'+image+'" style="width: 100%">';
        }
      $('.modal-body').html(content);
    }

  $('#invalidCheck2').click(function(){
    requireder();
  });

  $(document).ready(function(){
    requireder();
  })

  function requireder(){
   var centang = $('#invalidCheck2').is(':checked');
    if(centang==true){
       $('.btn-simpan').show();
       $('.btn-draf').hide();
       $('input[name=sip_ke]').attr('required','required');
       $('input[name=nomor_str]').attr('required','required');
       $('input[name=tanggal_berlaku_str]').attr('required','required');
       $('input[name=sebagai_jabatan]').attr('required','required');
       $('input[name=nomor_op]').attr('required','required');
       $('input[name=nama_tempat_praktik]').attr('required','required');
       $('input[name=alamat_tempat_praktik]').attr('required','required');
       $('input[name=keterangan_jenis_praktik]').attr('required','required');
       $('select[name=jenis_sarana]').attr('required','required');
       $('select[name=jenis_praktik]').attr('required','required');
       $('input[name=nama_variable').attr('required','required');
       <?php
       /*
       // for($i=0; $i< count($berkas_persyaratan);$i++)
       // if(in_array(str_replace("/","_",$berkas_persyaratan[$i]->nama_variable),$persyaratan_required))
       // else
       // var varia = '{{str_replace("/","_",$berkas_persyaratan[$i]->nama_variable)}}';
       // $('#'+varia).attr('required','required');
       // endif
       // endfor
       */
       ?>
    }else{
     $('.btn-simpan').hide();
     $('.btn-draf').show();
     $('input[name=sip_ke]').removeAttr('required');
     $('input[name=nomor_str]').removeAttr('required');
     $('input[name=tanggal_berlaku_str]').removeAttr('required');
     $('input[name=sebagai_jabatan]').removeAttr('required');
     $('input[name=nomor_op]').removeAttr('required');
     $('input[name=nama_tempat_praktik]').removeAttr('required');
     $('input[name=alamat_tempat_praktik]').removeAttr('required');
     $('input[name=keterangan_jenis_praktik]').removeAttr('required');
     $('select[name=jenis_sarana]').removeAttr('required');
     $('select[name=jenis_praktik]').removeAttr('required');
     $('input[name=nama_variable').removeAttr('required');
     <?php
     /*
     // for($i=0; $i< count($berkas_persyaratan);$i++)
     // var varia = '{{str_replace("/","_",$berkas_persyaratan[$i]->nama_variable)}}';
     // $('#'+varia).removeAttr('required');
     // endfor
     */
     ?>
    }
  }

  function getValue() {
    // Selecting the input element and get its value
    var inputVal = document.getElementById("pilihan").value;
    // Displaying the value
    if (inputVal == 1) {
      $('#panel1').hide();
      $('#panel2').hide();

    }else if(inputVal == 2){
      var centang = $('#invalidCheck2').is(':checked');
      if(centang==true){

      }
      $('#panel1').show();
      $('#panel2').hide();

    }else if(inputVal == 3){
      $('#panel1').show();
      $('#panel2').show();

    }else{
      //ketika isiannya kosong
      $('#panel1').hide();
      $('#panel2').hide();
    }
  }

  function cekAngka(evt) {
    try{
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode < 49 || charCode > 51)
        return false;
      return true;
    }finally{
      getValue();
    }
  }
  var waktu_praktik = 0;

  //Date picker
  $('#datepicker').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd',
  });

  $(document).ready(function() {
    <?php if ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik' && !empty($waktu_praktik) && $id_surat != 0): ?>
      add_input_waktu_praktik();
      <?php elseif ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik dengan Shift' && !empty($waktu_praktik) && $id_surat != 0): ?>
        add_input_waktu_praktik_shift();
      <?php endif ?>
      getValue();
    });

  // VIEW LAMPIRAN
  function view_lapiran(id, id_surat){
    $.post("{{ route('get_file_berkas') }}", {id:id, id_surat:id_surat}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }

  //============================================================

  function add_waktu_praktik() {
    waktu_praktik = waktu_praktik + 1;
    $('#table_waktu_praktik').append('<tr id="tr_waktu_praktik_'+waktu_praktik+'">'+
      '<td class="to_text" id="td_hari_'+waktu_praktik+'"><input id="input_hari_'+waktu_praktik+'" type="text" name="" class="form-control to_text" placeholder="Senin s.d Sabtu"></td>'+
      '<td class="to_text" id="td_jam_'+waktu_praktik+'">'+
      '<div class="row">'+
      '<div style="padding-right: 0px;" class="col-sm-10">'+
      '<input id="input_jam_'+waktu_praktik+'" type="text" name="" class="form-control to_text" placeholder="07.00 - 14.00">'+
      '</div>'+
      '<div style="padding-left: 0px;" class="col-sm-1">'+
      '<a href="javascript:void(0)" onclick="remove_waktu_praktik(\'tr_waktu_praktik_'+waktu_praktik+'\')" class="btn btn-warning"><i class="fa fa-eraser"></i></a>'+
      '</div>'+
      '</div>'+
      '</td>'+
      '</tr>');
  }

  function remove_waktu_praktik(id) {
    waktu_praktik = waktu_praktik - 1;
    $('#'+id).remove();
  }

  function remove_input_waktu_praktik() {
    var list_input = $('input.to_text');
    for(var i = 0; i < list_input.length; i++){
      $('#td_hari_'+i).html($('#input_hari_'+i).val());
      $('#td_jam_'+i).html($('#input_jam_'+i).val());
    }
    $('#input_waktu_praktik').val($('#waktu_praktik').html());
  }

  function add_input_waktu_praktik() {
    var list_input = $('td.to_text');
    for(var i = 0; i < list_input.length; i++){
      $('#td_hari_'+i).html('<input id="input_hari_'+i+'" type="text" name="" class="form-control to_text" value="'+$('#td_hari_'+i).html()+'">');
      $('#td_jam_'+i).html('<div class="row">'+
        '<div style="padding-right: 0px;" class="col-sm-10">'+
        '<input id="input_jam_'+i+'" type="text" name="" class="form-control to_text" value="'+$('#td_jam_'+i).html()+'" placeholder="07.00 - 14.00">'+
        '</div>'+
        '<div style="padding-left: 0px;" class="col-sm-1">'+
        '<a href="javascript:void(0)" onclick="remove_waktu_praktik(\'tr_waktu_praktik_'+i+'\')" class="btn btn-warning"><i class="fa fa-eraser"></i></a>'+
        '</div>'+
        '</div>');
      waktu_praktik = waktu_praktik + 1;
    }
  }

  //=======================================================

  function remove_input_waktu_praktik_shift() {
    var list_input = $('input.to_text_shift');
    for(var i = 0; i < list_input.length; i++){
      $('#td_hari_'+i).html(' : '+$('#input_hari_'+i).val());
      for(var j = 1; j < 7; j++){
        $('#td_jam_shift_'+i+'_'+j).html(' : '+$('#input_jam_shift_'+i+'_'+j).val());
      }
    }
    $('#input_waktu_praktik').val($('#waktu_praktik').html());
  }

  function add_input_waktu_praktik_shift() {
    var list_input = $('td.to_text_shift');
    var tmp_i = 0;
    var tmp_j = 0;

    if(list_input.length>0){
      for(var i = 0; i < list_input.length; i++){
        $('#td_hari_'+i).html('<div class="row">'+
        '<div style="margin-left: 30px;" class="col-sm-10">'+
        '<input id="input_hari_'+i+'" type="text" name="" class="form-control to_text_shift" value="'+String($('#td_hari_'+i).html()).replace(" : ", "")+'" placeholder="Senin s.d Sabtu">'+
        '</div>'+
        '<div style="padding-left: 0px;" class="col-sm-1">'+
        '<a href="javascript:void(0)" onclick="remove_waktu_praktik_shift(\'tr_hari_shift_'+i+'\')"  style="width: 39px;" class="btn btn-warning"><i class="fa fa-eraser"></i></a>'+
        '</div>'+
        '</div>');

        for(var j = 1; j < 7; j++){
          $('#td_jam_shift_'+i+'_'+j).html('<div class="row">'+
          '<div style="padding-right: 0px;" class="col-sm-10">'+
          '<input id="input_jam_shift_'+i+'_'+j+'" type="text" name="" class="form-control to_text_shift" value="'+String($('#td_jam_shift_'+i+'_'+j).html()).replace(" : ", "")+'" placeholder="07.00 - 14.00">'+
          '</div>'+
          '<div class="remove_add_shift" style="padding: 0px;" class="col-sm-1"></div>'+
          '</div>');

          tmp_i = i;
          tmp_j = j;

        }
        waktu_praktik = waktu_praktik + 1;
      }

      ($('.remove_add_shift')[$('.remove_add_shift').length-1]).innerHTML = '<a href="javascript:void(0)" onclick="add_waktu_praktik_shift_kolom('+tmp_j+', '+tmp_i+')" style="margin-left: 16px;" class="btn btn-success"><i class="fa fa-plus-square"></i></a>';
    }
  }

  function remove_waktu_praktik_shift(id) {
    waktu_praktik = waktu_praktik - 1;
    $('.'+id).remove();
  }

  var romawi = ['I', "II", 'III', "IV", "V", "VI"];
  function add_waktu_praktik_shift_kolom(counter, pw) {
    $('.remove_add_shift').remove();
    if (counter >= romawi.length) {return;}
    counter = counter + 1;
    $('#waktu_praktik_shift').append('<tr class="tr_hari_shift_'+pw+'">'+
      '<td></td>'+
      '<td>: Shift '+romawi[counter-1]+'</td>'+
      '<td class="to_text_shift" id="td_jam_shift_'+pw+'_'+counter+'">'+
      '<div class="row">'+
      '<div style="padding-right: 0px;" class="col-sm-10">'+
      '<input id="input_jam_shift_'+pw+'_'+counter+'" type="text" name="" class="form-control to_text_shift" placeholder="07.00 - 14.00">'+
      '</div>'+
      '<div class="remove_add_shift" style="padding: 0px;" class="col-sm-1">'+
      '<a href="javascript:void(0)" onclick="add_waktu_praktik_shift_kolom('+(counter)+', '+pw+')" style="margin-left: 16px;" class="btn btn-success"><i class="fa fa-plus-square"></i></a>'+
      '</div>'+
      '</div>'+
      '</td>'+
      '</tr>');
  }

  function add_waktu_praktik_shift() {
    waktu_praktik = waktu_praktik + 1;
    $('.remove_add_shift').remove();
    $('#waktu_praktik_shift').append('<tr class="tr_hari_shift_'+waktu_praktik+'">'+
      '<td>Hari</td>'+
      '<td class="to_text_shift" id="td_hari_'+waktu_praktik+'" colspan="2">'+
      '<div class="row">'+
      '<div style="margin-left: 30px;" class="col-sm-10">'+
      '<input id="input_hari_'+waktu_praktik+'" type="text" name="" class="form-control to_text_shift" placeholder="Senin s.d Sabtu">'+
      '</div>'+
      '<div style="padding-left: 0px;" class="col-sm-1">'+
      '<a href="javascript:void(0)" onclick="remove_waktu_praktik_shift(\'tr_hari_shift_'+waktu_praktik+'\')"  style="width: 39px;" class="btn btn-warning"><i class="fa fa-eraser"></i></a>'+
      '</div>'+
      '</div>'+
      '</td>'+
      '</tr>'+
      '<tr class="tr_hari_shift_'+waktu_praktik+'">'+
      '<td>Jam</td>'+
      '<td>: Shift I</td>'+
      '<td class="to_text_shift" id="td_jam_shift_'+waktu_praktik+'_1">'+
      '<div class="row">'+
      '<div style="padding-right: 0px;" class="col-sm-10">'+
      '<input id="input_jam_shift_'+waktu_praktik+'_1" type="text" name="" class="form-control to_text_shift" placeholder="07.00 - 14.00">'+
      '</div>'+
      '<div class="remove_add_shift" style="padding: 0px;" class="col-sm-1">'+
      '<a href="javascript:void(0)" onclick="add_waktu_praktik_shift_kolom(1, \''+waktu_praktik+'\')" style="margin-left: 16px;" class="btn btn-success"><i class="fa fa-plus-square"></i></a>'+
      '</div>'+
      '</div>'+
      '</td>'+
      '</tr>');
  }

  function checkSize(input_id){
    console.log(input_id);
    var input = document.getElementById(input_id);
    if(input.files && input.files.length == 1){
      if (input.files[0].size > 2097152) {
        $('#info-file-'+input_id).css('display', 'block');
        $('#button-submit-sip').attr('disabled', 'disabled');
        $('#button-submit-draf').attr('disabled', 'disabled');
        return false;
      }
    }
    $('#button-submit-sip').removeAttr('disabled');
    $('#button-submit-draf').removeAttr('disabled');
    $('#info-file-'+input_id).css('display', 'none');
    return true;
  }

  Filevalidation = () => {
  const fi = document.getElementById('sip_1');
// Check if any file is selected.
  if (fi.files.length > 0) {
    for (const i = 0; i <= fi.files.length - 1; i++) {

      const fsize = fi.files.item(i).size;
      const file = Math.round((fsize / 1024));
          // The size of the file.
          if (file >= 2048) {
            alert(
              "File terlalu besar, file harus kurang dari 2MB");
            document.getElementById('size').innerHTML = '<b style="color:red;">' + '* Anda harus upload ulang file' ;
          } else {
            document.getElementById('size').innerHTML = '<b>'
            + file + '</b> KB' + ' ' + 'file sudah cukup' ;
          }
        }
      }
  }

  Filevalidation2 = () => {
    const fi = document.getElementById('sip_2');
  // Check if any file is selected.
   if (fi.files.length > 0) {
      for (const i = 0; i <= fi.files.length - 1; i++) {
      const fsize = fi.files.item(i).size;
      const file = Math.round((fsize / 1024));
          // The size of the file.
        if (file >= 2048) {
          alert(
            "File terlalu besar, file harus kurang dari 2MB");
          document.getElementById('size2').innerHTML = '<b style="color:red;">' + '* Anda harus upload ulang file' ;
        } else {
          document.getElementById('size2').innerHTML = '<b>'
          + file + '</b> KB' + ' ' + 'file sudah cukup' ;
        }
      }
    }
  }

  $('input[type=file]').on('change',function (e) {
      var extValidation = new Array(".jpg", ".jpeg", ".pdf");
      var fileExt = e.target.value;
      fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
      if (extValidation.indexOf(fileExt) < 0) {
          swal('Extensi File Tidak Valid !','Upload file bertipe .jpg, .jpeg atau .pdf, untuk dapat melakukan upload data...','warning')
          $(this).val("")
          return false;
      }
      else return true;
  })

  </script>
  @endsection
