@extends('layouts.admin-template')
@section('content')

<?php
  $id                     = '';
  $id_level_user          = '';
  $id_jenis_surat         = '';
  $status_verifikasi      = '';
  $name                   = '';
  $email                  = '';
  $password               = '';
  $alamat_domisili        = '';
  $nomor_telpon           = '';
  $profesi                = '';

  if (isset($users)) {
    $id                     = $users->id;
    $id_level_user          = $users->id_level_user;
    $id_jenis_surat         = $users->id_jenis_surat;    
    $status_verifikasi      = $users->status_verifikasi;
    $name                   = $users->name;
    $email                  = $users->email;
    $password               = $users->password;
    $alamat_domisili        = $users->alamat_domisili;
    $nomor_telpon           = $users->nomor_telpon;
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
          <i class="fa fa fa-cog iconLabel m-r-15"></i> Data Pengguna Organisasi Profesi
        </h4>
          <div class="box-body">
            <div class="col-md-12">
              <form action="{{ route('save_data_op') }}" method="post" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" value="{{ $id }}">
               <input type="hidden" name="back_to_set" value="{{ $title }}">
              <div class="row">                
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="col-sm-12 control-label">Nama Organisasi Profesi <small style="color: red;">*</small></label>                                      
                    <div class="col-sm-12">
                      <input type="text" name="name" value="{{ $name }}" class="form-control disablecopypaste" placeholder="Nama Organisasi Profesi" style="text-transform:uppercase" onkeypress="return cekHuruf(event)">
                    </div>                    
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label">Email<small style="color: red;">*</small></label>
                    <div class="col-sm-12">
                      <input type="email" name="email" value="{{ $email }}" class="form-control disablecopypaste" placeholder="Tulis email pengguna" onkeypress="return cekHuruf(event)">
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
                      <label>Jenis Organisasi Profesi<small style="color: red;">*</small></label>
                      <select name="jenis_surat" class="form-control select2"  style="text-transform:uppercase">
                        <option value="">..:: Pilih Jenis Organisasi Profesi ::..</option>
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
                      <input type="number" name="nomor_telpon" value="{{ $nomor_telpon }}" id="nomor_telpon" class="form-control disablecopypaste" maxlength="13" placeholder="Tulis nomor telpon" onkeypress="return cekAngka(event)">

                      <p class='messageError errorWA' style="color:red;"></p>
                      <input type='hidden' name='statusWA' value='Exist' id='statusWA' class='form-control'>

                    </div>
                  </div>                              
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label">Alamat Domisili <small style="color: red;">*Alamat (wajib diisi).</small></label>
                    <div class="col-sm-12">
                      <textarea class="form-control disablecopypaste" name="alamat_domisili" placeholder="Tulis alamat"  style="text-transform:uppercase" onkeypress="return cekAngkaHuruf(event)">{{ $alamat_domisili }}</textarea>                      
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                </div>
              </div>
               <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Simpan
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
            disabledBtn();
          }
        });
      }
    })
  </script>
@endsection
