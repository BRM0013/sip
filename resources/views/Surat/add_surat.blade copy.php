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
$rdenable = '';
$rddisable = '';
$berlaku_disable = 'none';
$id_jenis_surat         = $users->id_jenis_surat ?? '';
$edit_pengajuan         = 'false';
// $fasyankes_id         = '';


if (isset($surat)) {
  $id_surat                       = $surat->id_surat;
  $tahun = substr($surat->tanggal_berlaku_str,0,4);
  if ($tahun == '2100') {
    $rdenable = 'checked';
    $berlaku_disable = 'none';
  }else{
    $rddisable = 'checked';
    $berlaku_disable = '';
  }
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
  $id_jenis_surat                 = $surat->id_jenis_surat;
  $edit_pengajuan                 = 'true';


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
      <form class="form-save">
        @csrf
        <input type="hidden" name="id_surat" value="{{ $id_surat }}">
        <input type="hidden" name="id_jenis_surat" value="{{ $id_jenis_surat }}">
        <input type="hidden" name="edit_pengajuan" value="{{ $edit_pengajuan }}">
        <input type="hidden" name="id_pemohon" value="{{ $users->id }}">
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
                 <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group" style="margin: 0px;">
                      <label>Surat Izin Ke (Sesuai nomor STR)<span class="colorRed">*</span></label>
                      <input type="text"name="sip_ke" class="form-control input-sm " value="{{ $sip_ke }}" onkeyup="return cekAngka(event)" maxlength="1" id="pilihan">
                    </div>
                  </div>
                    <div class="col-sm-6" >
                    <div class="form-group" style="margin: 0px;">
                       <label>Jenis Pengajuan SIP <span class="colorRed">*</span></label>
                        <select name="jenis_pengajuan" class="form-control" id="jenis_pengajuan">
                            <option value="SIP Baru">SIP Baru</option>
                             <option value="SIP Perpanjang">SIP Perpanjang</option>
                        </select>
                    </div>
                  </div>
                </div>
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
                <label>- Surat Tanda Registrasi</label>

                <div class="row">
                  <div class="col-sm-12">
                    <label>Nomor STR<span class="colorRed">*</span></label>
                    <input type="text"name="nomor_str" class="form-control input-sm " value="<?php if ($perpanjangan != '/perpanjangan'){ echo $nomor_str; } ?>" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
                  </div>
                  <div class="col-sm-12">
                    <label>Masa Berlaku SIP  <span class="colorRed">*</span></label>
                    <br><Input type = 'Radio' Name ='target' value= 'r1' id="enable" <?php echo $rdenable; ?>>STR SEUMUR HIDUP
                    <br><Input type = 'Radio' Name ='target' value= 'r2' id="disable" <?php echo $rddisable; ?>>STR MEMILIKI MASA BERLAKU
                    <input style="display: <?php echo $berlaku_disable; ?>;" type="text" style="text-transform:uppercase" name="tanggal_berlaku_str" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $tanggal_berlaku_str; } ?>" class="form-control input-sm" id="datepicker" placeholder="Tanggal STR">
                    <small style="font-weight: 900; color: red;">*Format Inputan : Tahun - Bulan - Tanggal.</small>
                  </div>
                </div>
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>



              <div class="form-group" style="margin: 0px;">
                <label>Profesi<span class="colorRed">*</span></label>
                <input readonly type="text" class="form-control input-sm" value="{{$users->profesi}}">
              <div class="clearfix" style="margin-bottom: 10px;"></div>


              <div class="form-group" style="margin: 0px;">
                <label>Nama Tempat Praktik (Khusus Praktek Mandiri diisi PRAKTEK PERSEORANGAN)<span class="colorRed">*</span></label>
                <input type="text"name="nama_tempat_praktik" class="form-control input-sm " value="{{ $nama_tempat_praktik }}" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Alamat Tempat Praktik<span class="colorRed">*</span></label>
                <input type="text"name="alamat_tempat_praktik" class="form-control input-sm " value="{{ $alamat_tempat_praktik }}" style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

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
                                <input id="input_hari_0" type="text" name="" class="form-control to_text_shift " placeholder="Senin s.d Sabtu">
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
                                <input id="input_jam_shift_0_1" type="text" name="" class="form-control to_text_shift " placeholder="07.00 - 14.00">
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
                <button id="button-submit-sip" type="button" onclick="remove_input_waktu_praktik()" class="btn btn-primary pull-right btn-simpan" data-btn="preview" style="margin-left: 15px;">Ajukan Permohonan
                <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                &nbsp;
                <button id="button-submit-draf" type="button" class="btn btn-success pull-right btn-draf" data-btn="draf" style="margin-left: 15px;">Draf
                <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>

              <?php elseif ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik dengan Shift'): ?>
                <button id="button-submit-sip" type="button" onclick="remove_input_waktu_praktik_shift()" class="btn btn-primary pull-right btn-simpan" data-btn="preview" style="margin-left: 15px;">Ajukan Permohonan
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                &nbsp;
                <button id="button-submit-draf" type="button" class="btn btn-success pull-right btn-draf" data-btn="draf" style="margin-left: 15px;">Draf
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>

              <?php else: ?>
                <button id="button-submit-draf" type="button" class="btn btn-success pull-right btn-draf" data-btn="draf" style="margin-left: 15px;">Draf
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                &nbsp;

                <button id="button-submit-sip" type="button" class="btn btn-primary pull-right btn-simpan" data-btn="preview" style="margin-left: 15px;">Ajukan Permohonan
                  <span style="margin-left: 5px;" class="fa fa-file"></span>
                </button>

                <!-- <button id="btn-simpan-ajukan" type="button" class="btn btn-primary pull-right btn-simpan" data-btn="ajukan" style="margin-left: 15px;display: block;">Ajukan Permohonan
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button> -->

                <?php endif ?>
                <a href="@if(isset($surat)){{route('surat')}}/list/{{$surat->id_jenis_surat}}@else{{route('surat')}}@endif" class="btn btn-warning btn-cencel pull-right">
                  <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                </a>

                <!-- <button type="button" class="btn btn-warning btn-cencel pull-right">
                    <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                </button> -->

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

<!-- Modal Preview SIP -->
<div class="modal fade" id="modal-body-preview" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background: #b6d1f2; text-shadow: 0 1px 0 #aecef4">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="product-detail-dialog" style="color: #fff; font-size: 18px; font-weight: 600;"><i class="fa fa-file m-r-15 m-l-5"></i>  Preview SIP</h4>
      </div>
      <input type="hidden" name="id_surat_preview" id="id_surat_preview">
      <div class="modal-body" id="modal-body-view">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-btn="ajukan">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script type="text/javascript">

    $(document).ready(function () {

      // window.onbeforeunload = function (e) {
      //     e = e || window.event;

      //     // For IE and Firefox prior to version 4
      //     if (e) {
      //         e.returnValue = 'Sure?';
      //     }

      //     // For Safari
      //     return 'Sure?';
      // };
      $("#enable").click(function () {
            $("#datepicker").hide();
            let now = new Date("2100-01-01");
            let today = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();
            console.log(today);
            $("#datepicker").attr("disabled", false);
            $("#datepicker").val(today);
        });

        $("#disable").click(function () {
            $("#datepicker").attr("disabled", false);
            $("#datepicker").val('');
            $("#datepicker").show()
        });

      $('input.disablecopypaste').bind('copy paste', function (e) {
         e.preventDefault();
      });
      $('textarea.disablecopypaste').bind('copy paste', function (e) {
         e.preventDefault();
      });

      $('#modal-body-preview').on('hidden.bs.modal', function () {
        var id_surat_preview = $('#id_surat_preview').val();
        $.post("{{ route('del_file_berkas') }}", {id_surat_preview:id_surat_preview}).done(function(data){
          if(data.status == 'success'){
            $('.modal-dialog').html(data.content);
          }
        });
        // console.log('closed modal');
      });

      $('.btn-info').click(function () {
        swal({
         title:"Konfirmasi Permohonan",
         text:"Apakah data yang anda inputkan sudah sesuai?",
         type:"warning",
         showCancelButton: true,
         confirmButtonColor: "#DD6B55",
         confirmButtonText: "YA",
         cancelButtonText: "BATAL!",
         closeOnConfirm: true
        },
        function (result) {
          console.log(result);
          var id_surat_preview = $('#id_surat_preview').val();
          if (result) {
            $.ajax({
              url: "{{ route('del_file_berkas') }}",
               type: 'POST',
               data: {id_surat_preview:id_surat_preview},
               async: false,
            }).done(function (data) {
              if(data.status == 'success'){
                $('.modal-dialog').html(data.content);
              }
            })
            $('.btn-simpan').data('btn', 'ajukan');
            $('.btn-simpan').trigger('click');
          } else {
            $('#modal-body-preview').modal('hide');
          }
        });
        // console.log('closed dibawah');
      })
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
       // if ($("input[name=id_surat]").val() == 0) { // JIKA FORM TAMBAH
         // $('input[type=file]').attr('required','required');
       // }
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
     $('input[name=status_simpan]').val('');
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
     // $('input[type=file').removeAttr('required');
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

  $('.btn-simpan, .btn-draf').click(function(e){
       e.preventDefault();
       var btnType = $(this).data('btn');
       console.log(console.log);

       if (btnType == 'preview') {
         var url = "{{ route('save_surat_preview') }}";
       } else {
         var url = "{{ route('save_surat') }}{{$perpanjangan}}";
       }
       console.log(btnType);
       if (!$('.form-save')[0].checkValidity()) { // VALIDATE THE FORM
       $('.form-save')[0].reportValidity();
        return; // RETURN NOTHING WHEN INVALIDATED
       }
       $('.btn-simpan-ajukan').html('Please wait...').attr('disabled', true);
       var data  = new FormData($('.form-save')[0]);
       data.append('btn_type', btnType);
       $.ajax({
         url: url,
         type: 'POST',
         data: data,
         async: true,
         cache: false,
         contentType: false,
         processData: false
       }).done(function(data){
         if(data.status == 'success'){

          if (data.message == "Preview") {
            var content = '';
            content = '<iframe src="{{ url('/') }}/upload/file_sip_salinan/'+data.data.file_surat_sip_salinan+'" width="100%" height="950px"></iframe>';
            $('#modal-body-view').html(content);
            $('#id_surat_preview').val(data.data.id_surat);
            $('#modal-body-preview').modal('show');
            $('#btn-simpan-ajukan').html('Ajukan Permohonan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
          }else{
           window.location.href ="{{ url('/') }}/home/surat/list/"+data.data.id_jenis_surat;
           swal(data.status, data.message, 'success');
          }

         } else if(data.status == 'error') {

           $('.btn-simpan').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
           swal('Whoops !', data.message, 'warning');

         } else {
           var n = 0;
           for(key in data){
             if (n == 0) {var dt0 = key;}
             n++;
           }
           $('.btn-simpan').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
           swal('Whoops !', 'Kolom '+dt0+' Tidak Boleh Kosong !!', 'error');
         }
       }).fail(function() {
         swal("MAAF !","Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
         $('.btn-simpan').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
       });
     });
  </script>
  @endsection
