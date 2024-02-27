@extends('layouts.admin-template')
@section('content')

<?php
$id                     = '';
$id_level_user          = '';
$id_jenis_surat         = '';
$id_jabatan             = '';
$id_pendidikan_terakhir = '';
$id_desa                = '';
$nama_desa              = '';
$id_kecamatan           = '';
$nama_kecamatan         = '';
$id_kabupaten            = '';
$nama_kabupaten         = '';
$dusun            = '';
$id_provinsi            = '';
$status_verifikasi      = '';
$name                   = '';
$email                  = '';
$password               = '';
$jenis_kelamin          = '';
$tempat_lahir           = '';
$tanggal_lahir          = '';
$status_perkawinan      = '';
$alamat_jalan_rt_rw     = '';
$alamat_domisili        = '';
$nomor_telpon           = '';
$nomor_ktp              = '';
$photo                  = url('/').'/dist/img/user2-160x160.jpg';
$agama                  = '';
$gelar_belakang         = '';
$gelar_depan            = '';
$tahun_lulus            = '';
$status_kepegawaian     = '';
$profesi                = '';

if (isset($users)) {
  $id                     = $users->id;
  $id_level_user          = $users->id_level_user;
  $id_jenis_surat         = $users->id_jenis_surat;
  $id_jabatan             = $users->id_jabatan;
  $id_pendidikan_terakhir = $users->id_pendidikan_terakhir;
  $id_desa                = $users->id_desa;
  $dusun                  = $users->dusun;
  $nama_desa              = isset($users->desa) ? $users->desa->nama_desa : '';
  $id_kecamatan           = $users->id_kecamatan;
  $nama_kecamatan         = isset($users->kecamatan) ? $users->kecamatan->nama_kecamatan : '';
  $id_kabupaten           = $users->id_kabupaten;
  $nama_kabupaten         = isset($users->kabupaten) ? $users->kabupaten->nama_kabupaten : '';
  $id_provinsi            = $users->id_provinsi;
  $status_verifikasi      = $users->status_verifikasi;
  $name                   = $users->name;
  $email                  = $users->email;
  $password               = $users->password;
  $jenis_kelamin          = $users->jenis_kelamin;
  $tempat_lahir           = $users->tempat_lahir;
  $tanggal_lahir          = $users->tanggal_lahir;
  $status_perkawinan      = $users->status_perkawinan;
  $alamat_jalan_rt_rw     = $users->alamat_jalan_rt_rw;
  $alamat_domisili        = $users->alamat_domisili;
  $nomor_telpon           = $users->nomor_telpon;
  $nomor_ktp              = $users->nomor_ktp;
  $photo                  = url('/').'/upload/users/'.$users->photo;
  $agama                  = $users->agama;
  $gelar_belakang         = $users->gelar_belakang;
  $gelar_depan            = $users->gelar_depan;
  $tahun_lulus            = $users->tahun_lulus;
  $status_kepegawaian     = $users->status_kepegawaian;
  $profesi                = $users->profesi;
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>{{ $title }}</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Master Data</a></li>
      <li class="active"> {{ $title }}</li>
    </ol>
  </section>

  <section class="content">
    <div class="box" style="border-top:none">
      <div class="col-md-1"></div>
      <div class="col-md-10">
        <div class="box box-info" id="btn-add" style="border-top:none">
          <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
            <i class="fa fa fa-cog iconLabel m-r-15"></i> Data Pengguna
          </h4>
          <div class="box-body">
            <div class="col-md-12">
              <form class="form-save" action="javascript:void(0)" method="post" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" value="{{ $id }}">
               <input type="hidden" name="back_to_set" value="{{ $title }}">
               <div class="row">
                <div class="col-md-3">
                  <!--<label>Foto Resmi (Merah)</label>-->
                  <!--<br>-->
                  <h4>Foto Resmi (Merah)</h4><i style="color:red"><h6>PAS FOTO ASLI (FILE DARI CD/FILE MENTAH/SOFTFILE)</h6></i>
                  <img id="output" style="border: 2px solid gray; height: 150px; width: 150px;" src="{{ $photo }}">
                  <input name="photo" accept="image/*" onchange="loadFile(event)" style="width: 150px;" type="file" class="form-control input-sm" @if(empty($photo))@endif>
                  <i style="color:red">Ukuran maks 2 MB</i>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <label class="col-sm-12 control-label">Nama Lengkap / Gelar</label>
                    <div class="col-sm-2">
                      <input type="text" name="gelar_depan" value="{{ $gelar_depan }}" class="form-control disablecopypaste" placeholder="Gelar Depan" onkeypress="return cekHuruf(event)">                  
                    </div>
                    <div class="col-sm-1">
                      <span style="font-weight: bold;">.</span>
                    </div>                    
                    <div class="col-sm-5">
                      <input type="text" name="name" value="{{ $name }}" class="form-control disablecopypaste" placeholder="Nama Lengkap" style="text-transform:uppercase" onkeypress="return cekHuruf(event)">
                    </div>
                    <div class="col-sm-1">
                      <span style="font-weight: bold;">,</span>
                    </div>
                    <div class="col-sm-3">
                      <input type="text" name="gelar_belakang" value="{{ $gelar_belakang }}" class="form-control disablecopypaste" placeholder="Gelar Belakang">
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label">Email</label>
                    <div class="col-sm-12">
                      <input type="email" name="email" value="{{ $email }}" class="form-control disablecopypaste" placeholder="Tulis email pengguna">
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <div class="form-group">                      
                    <label class="col-sm-12 control-label">Password <small style="color: red;">* (diisi jika ingin merubah password, jika tidak dikosongi)</small></label>
                    <div class="col-sm-12">
                      <input type="password" name="password" class="form-control disablecopypaste" placeholder="Tulis kata sandi pengguna">
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <?php if ($setting != 'setting'): ?>
                    <div class="col-sm-12">
                      <label>Hak akses</label>
                      <select name="level_user" class="form-control select2"  style="text-transform:uppercase">
                        <option value="">..:: Pilih Hak akses ::..</option>
                        <?php foreach ($list_level_user as $row): ?>
                          <?php if ($row->id_level_user == $id_level_user): ?>
                            <option value="{{ $row->id_level_user }}" selected="selected">{{ $row->nama_level_user }}</option>
                            <?php else: ?>
                              <option value="{{ $row->id_level_user }}">{{ $row->nama_level_user }}</option>
                            <?php endif ?>
                          <?php endforeach ?>
                        </select>
                      </div>
                      <div class="clearfix" style="margin-bottom: 10px;"></div>
                      <div class="col-sm-12">
                        <label>Jenis Surat Izin</label>
                        <select name="jenis_surat" class="form-control select2"  style="text-transform:uppercase">
                          <option value="">..:: Pilih Jenis Surat Izin ::..</option>
                          <?php foreach ($list_jenis_surat as $row): ?>
                            <?php if ($row->id_jenis_surat == $id_jenis_surat): ?>
                              <option value="{{ $row->id_jenis_surat }}" selected="selected">{{ $row->nama_surat }}</option>
                              <?php else: ?>
                                <option value="{{ $row->id_jenis_surat }}">{{ $row->nama_surat }}</option>
                              <?php endif ?>
                            <?php endforeach ?>
                          </select>
                        </div>
                        <div class="clearfix" style="margin-bottom: 10px;"></div>
                      <?php endif ?>
                      <div class="form-group">
                        <label class="col-sm-12 control-label">Nomor Telpon <small style="color:red;">* harus Terdaftar di WA, tulis seperti contoh (6289123421242)</small></label>
                        <div class="col-sm-12">
                          <input type="number" name="nomor_telpon" value="{{ $nomor_telpon }}" id="nomor_telpon" class="form-control disablecopypaste" maxlength="13" placeholder="Tulis nomor telpon pengguna" onkeypress="return cekAngka(event)">

                          <p class='messageError errorWA' style="color:red;"></p>
                          <input type='hidden' name='statusWA' value='Exist' id='statusWA' class='form-control'>

                        </div>
                      </div>
                      <div class="clearfix" style="margin-bottom: 10px;"></div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label">Nomor KTP</label>
                        <div class="col-sm-12">
                          <input style="text-transform:uppercase" type="number" name="nomor_ktp" id="nomor_ktp" value="{{ $nomor_ktp }}" class="form-control disablecopypaste" maxlength="16" placeholder="Tulis nomor KTP pengguna" onkeypress="return cekAngka(event)">

                          <p class='messageError errorKTP' style="color:red;"></p>
                          <input type='hidden' name='statusKTP' value='Exist' id='statusKTP' class='form-control'>

                        </div>
                      </div>
                      <div class="clearfix" style="margin-bottom: 10px;"></div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label">Tempat & Tanggal Lahir</label>
                        <div class="col-sm-5">
                          <select id="tempat_lahir" name="tempat_lahir" class="form-control select2"  style="text-transform:uppercase">
                            <option>.:: Pilih Tempat Lahir ::.</option>
                            <?php foreach ($list_kabupaten as $row): ?>
                              <?php if ($row->nama_kabupaten == $tempat_lahir && !empty($tempat_lahir)): ?>
                                <option value="{{ $row->nama_kabupaten }}" selected="selected">{{ $row->nama_kabupaten }}</option>
                                <?php else: ?>
                                  <option value="{{ $row->nama_kabupaten }}">{{ $row->nama_kabupaten }}</option>
                                <?php endif ?>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <div class="col-sm-7">
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="tanggal_lahir" value="{{ $tanggal_lahir }}" class="form-control pull-right disablecopypaste" id="datepicker" placeholder="yyyy-mm-dd">
                            </div>
                            <small style="color: red;">*Format Inputan : Tahun - Bulan - Tanggal.</small>
                          </div>
                        </div>
                        <div class="clearfix" style="margin-bottom: 10px;"></div>
                        <div class="form-group">
                          <label class="col-sm-12 control-label">Agama</label>
                          <div class="col-sm-12">
                            <select name="agama" class="form-control select2" style="text-transform:uppercase">
                              <option>.:: Pilih Agama ::.</option>
                              <option <?php if ($agama == 'Islam'){echo 'selected="selected"';} ?> value="Islam">Islam</option>
                              <option <?php if ($agama == 'Kristen'){echo 'selected="selected"';} ?> value="Kristen">Kristen</option>
                              <option <?php if ($agama == 'Katolik'){echo 'selected="selected"';} ?> value="Katolik">Katolik</option>
                              <option <?php if ($agama == 'Hindu'){echo 'selected="selected"';} ?> value="Hindu">Hindu</option>
                              <option <?php if ($agama == 'Buddha'){echo 'selected="selected"';} ?> value="Buddha">Buddha</option>
                              <option <?php if ($agama == 'Kong Hu Cu'){echo 'selected="selected"';} ?> value="Kong Hu Cu">Kong Hu Cu</option>
                              <option <?php if ($agama == 'Lainnya'){echo 'selected="selected"';} ?> value="Lainnya">Lainnya</option>
                            </select>
                          </div>
                        </div>
                        <div class="clearfix" style="margin-bottom: 10px;"></div>
                        <div class="form-group">
                          <label class="col-sm-12 control-label">Jenis Kelamin</label>
                          <div class="col-sm-12">
                            <select class="form-control select2" name="jenis_kelamin" style="text-transform:uppercase">
                              <option value="-">.:: PILIH JENIS KELAMIN ::.</option>
                              <option  <?php if ($jenis_kelamin == 'Laki-Laki'){echo 'selected="selected"';} ?> value="Laki-Laki">LAKI - LAKI</option>
                              <option <?php if ($jenis_kelamin == 'Perempuan'){echo 'selected="selected"';} ?> value="Perempuan">PEREMPUAN</option>
                            </select>
                          </div>
                        </div>
                        <div class="clearfix" style="margin-bottom: 10px;"></div>
                        <div style="display: none;" class="form-group">
                          <label class="col-sm-12 control-label">Status Perkawinan</label>
                          <div class="col-sm-12">
                            <select class="form-control select2" name="status_perkawinan" style="text-transform:uppercase">
                              <option value="-">.:: Pilih Status ::.</option>
                              <option <?php if ($status_perkawinan == 'Menikah'){echo 'selected="selected"';} ?> value="Menikah">Menikah</option>
                              <option <?php if ($status_perkawinan == 'Belum Menikah'){echo 'selected="selected"';} ?> value="Belum Menikah">Belum Menikah</option>
                            </select>
                          </div>
                        </div>
                        <!-- <div class="clearfix" style="margin-bottom: 10px;"></div> -->
                        <div class="form-group">
                          <label class="col-sm-12 control-label">Alamat Rumah (Sesuai KTP)</label>
                          <div class="clearfix" style="margin-bottom: 10px;"></div>
                          <div class="col-sm-12">
                            <label>Provinsi</label>
                            <select id="provinsi" name="provinsi" class="form-control select2"  style="text-transform:uppercase">
                              <option value="">..:: Pilih Provinsi ::..</option>
                              <?php foreach ($list_provinsi as $row): ?>
                                <?php if ($row->id_provinsi == $id_provinsi): ?>
                                  <option value="{{ $row->id_provinsi }}" selected="selected">{{ $row->nama_provinsi }}</option>
                                  <?php else: ?>
                                    <option value="{{ $row->id_provinsi }}">{{ $row->nama_provinsi }}</option>
                                  <?php endif ?>
                                <?php endforeach ?>
                              </select>
                            </div>
                            <div class="clearfix" style="margin-bottom: 10px;"></div>
                            <div class="col-sm-12">
                              <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                                <label>Kabupaten</label>
                                <select id="kabupaten" name="kabupaten" class="form-control select2">
                                  <option value="{{ $id_kabupaten }}" selected="selected">{{ $nama_kabupaten }}</option>
                                </select>
                              </div>
                              <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                                <label>Kecamatan</label>
                                <select id="kecamatan" name="kecamatan" class="form-control select2">
                                  <option value="{{ $id_kecamatan }}" selected="selected">{{ $nama_kecamatan }}</option>
                                </select>
                              </div>
                              <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                                <label>Desa</label>
                                <select id="desa" name="desa" class="form-control select2">
                                  <option value="{{ $id_desa }}" selected="selected">{{ $nama_desa }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="clearfix" style="margin-bottom: 10px;"></div>
                          <div class="form-group">
                            <div class="col-sm-6 control-label">
                              <label>Alamat</label>
                              <input style="text-transform:uppercase" type="text" name="dusun" value="{{ $dusun }}" class="form-control disablecopypaste" placeholder="Tambahkan Alamat" onkeypress="return cekAngkaHuruf(event)">
                            </div>
                            <div class="col-sm-6 control-label">
                              <label>RT / RW</label> <small style="color: red;"> *Contoh Format: <b>003/005</b></small>
                              <input id="rt_rw" style="text-transform:uppercase" type="text" name="alamat_jalan_rt_rw" value="<?php if($alamat_jalan_rt_rw != ''){echo $alamat_jalan_rt_rw;}else{ echo "000/000";} ?>" class="form-control" placeholder="003 / 005">
                            </div>
                          </div>
                          <div class="clearfix" style="margin-bottom: 10px;"></div>
                          <div class="form-group">
                            <label class="col-sm-12 control-label">Alamat Domisili</label>
                            <div class="col-sm-12">
                              <textarea class="form-control disablecopypaste" name="alamat_domisili" placeholder="Tulis alamat domisli pengguna"  style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">{{ $alamat_domisili }}</textarea>
                              <small style="color: red;">Alamat domisili (wajib diisi meskipun tidak sesuai dengan ktp).</small>
                            </div>
                          </div>
                          <div class="clearfix" style="margin-bottom: 10px;"></div>
                          <div class="form-group">
                            <div class="col-sm-12">
                              <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-8">
                                <label>Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" class="form-control select2" style="text-transform:uppercase">
                                  <option value="">.:: Pilih Pendidikan Terakhir ::.</option>
                                  <?php foreach ($list_pendidikan_terakhir as $row): ?>
                                    <?php if ($row->id_pendidikan_terakhir == $id_pendidikan_terakhir): ?>
                                      <option value="{{ $row->id_pendidikan_terakhir }}" selected="selected">{{ $row->pendidikan_terakhir }}({{ $row->jenjang }})</option>
                                      <?php else: ?>
                                        <option value="{{ $row->id_pendidikan_terakhir }}">{{ $row->pendidikan_terakhir }}({{ $row->jenjang }})</option>
                                      <?php endif ?>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                                <div style="padding-left: 0px; padding-right: 5px;" class="col-sm-4">
                                  <label>Tahun lulus</label>
                                  <select name="tahun_lulus" class="form-control select2">
                                    <option value="">.:: Tahun Lulus ::.</option>
                                    @for($i = 1980; $i <= date("Y"); $i++)
                                    <?php if ($tahun_lulus == $i): ?>
                                      <option value="{{$i}}" selected="selected">{{$i}}</option>
                                      <?php else: ?>
                                        <option value="{{$i}}">{{$i}}</option>
                                      <?php endif ?>
                                      @endfor
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="clearfix" style="margin-bottom: 10px;"></div>
                              <div class="form-group">
                                <label class="col-sm-12 control-label">Profesi</label>
                                <div class="col-sm-12">
                                  <input style="text-transform:uppercase" type="text" name="profesi" value="{{ $profesi }}" class="form-control disablecopypaste" placeholder="Tulis profesi pengguna" onkeypress="return cekHuruf(event)">
                                </div>
                              </div>
                              <div class="clearfix" style="margin-bottom: 10px;"></div>
                              <div class="form-group">
                                <label class="col-sm-12 control-label">Status Kepegawaian</label>
                                <div class="col-sm-12">
                                  <select id="status_kepegawaian" name="status_kepegawaian" class="form-control select2">
                                    <option value="-">.:: Status Kepegawaian ::.</option>
                                    <option <?php if ($status_kepegawaian == 'PNS'){echo "selected='selected'";} ?> value="PNS">PNS</option>
                                    <option <?php if ($status_kepegawaian == 'NON PNS'){echo "selected='selected'";} ?> value="NON PNS">NON PNS</option>
                                  </select>
                                </div>
                              </div>
                              <div class="clearfix" style="margin-bottom: 10px;"></div>
                              <div id="jabatan" <?php if ($status_kepegawaian != 'PNS'){echo 'style="display: none;"';} ?> class="form-group">
                                <label class="col-sm-12 control-label">Jabatan</label>
                                <div class="col-sm-12">
                                  <select name="jabatan" class="form-control select2">
                                    <option value="1">.:: Pilih Jabatan ::.</option>
                                    <?php foreach ($list_jabatan as $row): ?>
                                      <?php if (!empty($row->jabatan)): ?>
                                        <?php if ($row->id_jabatan == $id_jabatan): ?>
                                          <option value="{{ $row->id_jabatan }}" selected="selected">{{ $row->jabatan }}</option>
                                          <?php else: ?>
                                            <option value="{{ $row->id_jabatan }}">{{ $row->jabatan }}</option>
                                          <?php endif ?>
                                        <?php endif ?>
                                      <?php endforeach ?>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="box-footer">
                              <button class="btn btn-primary pull-right btn-submit" style="margin-left: 15px;">Simpan
                                <span style="margin-left: 5px;" class="fa fa-save"></span>
                              </button>
                              @if($setting=='')
                              <a href="{{route('users')}}" class="btn btn-warning btn-cencel pull-right">
                                <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                              </a>
                              @endif
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-1"></div>
                  </div>
                </div>
              </section>
              <div class="clearfix"></div>
            </div>

            <div class="modal-dialog"></div>

            @endsection
            @section('js')
            <script src="{{ asset('dist/js/jquery.mask.js') }}"></script>
            <script type="text/javascript">

              $(document).ready(function () {
                $('input.disablecopypaste').bind('copy paste', function (e) {
                 e.preventDefault();
               });
                $('textarea.disablecopypaste').bind('copy paste', function (e) {
                 e.preventDefault();
               });
              });

              function cekHuruf(evt) { 
                var charCode = (evt.which) ? evt.which : event.keyCode
                if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 32 || charCode == 46 || charCode == 64 || charCode == 39)
                  return true;
                return false;
              }

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

              $('#rt_rw').mask('999/999');
              $('#rt').mask('003/009/');

              function disabledBtn() {
                var stWA = $('#statusWA').val();        
                var stKTP = $('#statusKTP').val();
                if (stKTP == 'Ready' && stWA == 'Ready') {
                  $('.btn-primary').removeAttr('disabled');
                }else{
                  $('.btn-primary').attr('disabled', true);
                }
              }

              $(document).ready(function() {
                $('.select2').select2();

      //Date picker
      $('#datepicker').datepicker({
        autoclose: true,
        format:'yyyy-mm-dd',
      });

      $('#status_kepegawaian').change(function(){
        if ($('#status_kepegawaian').val() == 'PNS') {
          $('#jabatan').css('display', 'block');
        }else{
          $('#jabatan').css('display', 'none');
        }
      });

      //pengecekan jika id provinsi/kabupaten/kecamatan sudah diisi
      @if($id_provinsi)
      var id = $('#provinsi').val();
      $.post("{{route('get_kabupaten')}}",{id:id},function(data){
        var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              if (k.id_kabupaten == {{($id_kabupaten) ? $id_kabupaten : 'null'}}) {
                kabupaten+='<option value="'+k.id_kabupaten+'" selected="selected">'+k.nama_kabupaten+'</option>';
              } else {
                kabupaten+='<option value="'+k.id_kabupaten+'">'+k.nama_kabupaten+'</option>';
              }
            });
          }
        }
        $('#kabupaten').html(kabupaten);
      });
      @endif

      @if($id_kabupaten)
      var id = $('#kabupaten').val();
      $.post("{{route('get_kecamatan')}}",{id:id},function(data){
        var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              if (k.id_kecamatan == {{($id_kecamatan) ? $id_kecamatan : 'null'}}) {
                kecamatan+='<option value="'+k.id_kecamatan+'" selected="selected">'+k.nama_kecamatan+'</option>';
              } else {
                kecamatan+='<option value="'+k.id_kecamatan+'">'+k.nama_kecamatan+'</option>';
              }
            });
          }
        }
        $('#kecamatan').html(kecamatan);
      });
      @endif

      @if($id_kecamatan)
      var id = $('#kecamatan').val();
      $.post("{{route('get_desa')}}",{id:id},function(data){
        var desa = '<option value="">..:: Pilih Desa ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              if (k.id_desa == {{($id_desa) ? $id_desa : 'null'}}) {
                desa+='<option value="'+k.id_desa+'" selected="selected">'+k.nama_desa+'</option>';
              } else {
                desa+='<option value="'+k.id_desa+'">'+k.nama_desa+'</option>';
              }
            });
          }
        }
        $('#desa').html(desa);
      });
      @endif

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

var loadFile = function(event) {
  var output = document.getElementById('output');
  output.src = URL.createObjectURL(event.target.files[0]);
};

$('input[type=file]').on('change',function (e) {
  var extValidation = new Array(".jpg", ".jpeg", ".png", ".JPG", ".JPEG", ".PNG" );
  var fileExt = e.target.value;
  fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
  if (extValidation.indexOf(fileExt) < 0) {
    swal('Extensi File Tidak Valid !','Upload file bertipe .jpg, .jpeg, dan .png untuk dapat melakukan upload data...','warning')
    $(this).val("")
    return false;
  }
  else return true;
})

$('#nomor_telpon').on('keyup', function() {
  var nomor_telpon = $('#nomor_telpon').val();
  if (nomor_telpon.length >= 2 && nomor_telpon[0]+''+nomor_telpon[1] != '62') {
    $('#nomor_telpon').val('');
    swal('Whoops','Angka diawal harus menggunakan 62');            
  } else {
    $.post("{!! route('getWA') !!}", {nomor_telpon:nomor_telpon}).done(function(data){
      if (data.status == 'success') {
        $('.errorWA').html('No WA Telah Terdaftar');
        $('#statusWA').val('Exist');
        disabledBtn();
      }else{
        $('.errorWA').html('');
        $('#statusWA').val('Ready');
            // disabledBtn();
          }
        });
  }
})

$('#nomor_ktp').on('keyup', function() {
  var nomor_ktp = $('#nomor_ktp').val();
  if (nomor_ktp.length == 16) {
    $.post("{!! route('getKTP') !!}", {nomor_ktp:nomor_ktp}).done(function(data){
      if (data.status == 'success') {
        $('.errorKTP').html('NIK Telah Terdaftar');
        $('#statusKTP').val('Exist');
        disabledBtn();
      }else{
        $('.errorKTP').html('');
        $('#statusKTP').val('Ready');
        disabledBtn();
      }
    });
  }else{
            // $('#nomor_ktp').val('');
            $('.errorKTP').html('NIK Harus 16 Digit');
            $('#statusKTP').val('Exist');
            disabledBtn();
          }        
        })

$('.btn-submit').click(function(e){
 e.preventDefault();
 $('.btn-submit').html('Please wait...').attr('disabled', true);
 var data  = new FormData($('.form-save')[0]);
 $.ajax({
   url: "{{ route('save_users') }}",
   type: 'POST',
   data: data,
   async: true,
   cache: false,
   contentType: false,
   processData: false
 }).done(function(data){
   if(data.status == 'success'){
    swal("Success !", data.message, "success");
    window.location.reload()
  } else if(data.status == 'error') {
   $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
   swal('Whoops !', data.message, 'warning');
 } else {
   var n = 0;
   for(key in data){
     if (n == 0) {var dt0 = key;}
     n++;
   }
   $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
   swal('Whoops !', 'Kolom '+ucwords(dt0.replace('_',' '))+' Tidak Boleh Kosong !!', 'error');
 }
}).fail(function() {
 swal("MAAF !","Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
 $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
});
});

function ucwords (str) {
  return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
    return $1.toUpperCase();
  });
}

</script>
@endsection
