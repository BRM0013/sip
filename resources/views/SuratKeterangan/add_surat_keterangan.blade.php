@extends('layouts.admin-template')
@section('content')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

<?php
  $id_surat_keterangan  = '0';
  $id_user              = '';
  $id_jenis_surat       = '';
  $tanggal_pengajuan    = '';
  $keperluan            = '';
  $tmpPrak              = '';

  if (isset($surat_keterangan)) {
    $id_surat_keterangan  = $surat_keterangan->id_surat_keterangan;
    $id_user              = $surat_keterangan->id_user;
    $id_jenis_surat       = $surat_keterangan->id_jenis_surat;
    $tanggal_pengajuan    = $surat_keterangan->tanggal_pengajuan;
    $keperluan            = $surat_keterangan->keperluan;
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
      <form class="form" action="{{ route('save_surat_keterangan') }}" enctype="multipart/form-data" method="post">
        @csrf
        @if (isset($surat_keterangan))
          <input type="hidden" name="id_surat_keterangan" value="{{ $surat_keterangan->id_surat_keterangan }}">
        @endif
        <input type="hidden" name="id_user" value="{{ $users->id }}">
        <div class="box-body">
          <div class="col-md-6 col-xs-12">
            <h4 style="font-weight: bold;">Data Pribadi</h4>

            <div class="form-group" style="margin: 0px;">
              <label>Nama Lengkap<span class="colorRed">*</span></label>
              <input readonly type="text" name="namaLengkap" class="form-control input-sm" value="{{$users->name}}">
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
              <input readonly type="text" class="form-control input-sm" value="{{$users->jenis_kelamin}}">
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
          </div>

          <div class="col-md-6 col-xs-12">
            <h4 style="font-weight: bold;">Formulir Pengajuan</h4>

            <div class="form-group" style="margin: 0px;">
              <label>Keperluan<span class="colorRed">*</span></label>
              <textarea style="text-transform:uppercase" class="disablecopypaste form-control" name="keperluan" required="required" onkeypress="return cekAngkaHuruf(event)">{{ $keperluan }}</textarea>
            </div>
            <div class="clearfix" style="margin-bottom: 10px;"></div>
            <button type="button" class="btn btn-primary doAddTempat">Tambah Tempat Praktek</button>
            <h4 style="font-weight: bold;">Tempat Praktik Aktif Saya</h4>

            <div class="clearfix" style="margin-bottom: 10px;"></div>
            <?php if (count($list_surat) > 0): ?>
              <div id="tempatPraktek">
                <?php foreach ($list_surat as $row): ?>
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
                <?php endforeach ?>
              </div>
            <?php else: ?>
              <?php echo 'Anda belum memiliki tempat praktik.' ?>
              <div class="clearfix" style="margin-bottom: 10px;"></div>
            <?php endif ?>
            @if ($suratKeterangan != '')
              @foreach ($suratKeterangan as $key)
                <div class="form-group" style="margin: 0px;">
                  <div class="col-sm-1">.</div>
                  <div class="col-sm-11">
                    <b>{{ $key->nama_praktek }}</b>
                    <br>
                    ({{ $key->no_sip }})
                    <br>
                    {{ $key->tempat_praktek }}
                  </div>
                </div>
              @endforeach
            @endif
            <div class="clearfix" style="margin-bottom: 10px;"></div>
          </div>
          <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>
          <h4 style="font-weight: bold;">Upload Dokumen Pendukung</h4>

            <?php
              $no = 1;
              $jumlah = count($berkas_persyaratan)/2;
              $persyaratan_required = [];
            ?>
            <?php for ($i = 0; $i < count($berkas_persyaratan); $i++){ ?>
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                <label class="col-sm-12 control-label">
                  <?php echo $no++ ?>. <?php echo $berkas_persyaratan[$i]->nama_jenis_persyaratan; ?>
                  <span class="colorRed">*</span>
                </label>
                <div class="col-sm-12">
                  <input accept="<?php echo $berkas_persyaratan[$i]->jenis_input; ?>" style="border: solid 1px #d2d6de; padding: 5px;" type="file" accept="image/*,.pdf" onchange="checkSize('<?php echo $berkas_persyaratan[$i]->nama_variable; ?>')" id="<?php echo str_replace("/","_",$berkas_persyaratan[$i]->nama_variable); ?>" name="<?php echo $berkas_persyaratan[$i]->nama_variable; ?>" class="form-control input-sm input-berkas-pengajuan">
                  <span id="info-file-<?php echo $berkas_persyaratan[$i]->nama_variable; ?>" style="color:red; display:none;">File yang anda masukan harus kurang dari 2MB</span>
                  <?php if (isset($syarat_persyaratan)): ?>
                    <?php foreach ($syarat_persyaratan as $row2): ?>
                      <?php if ($row2->id_jenis_persyaratan == $berkas_persyaratan[$i]->id_jenis_persyaratan): ?>
                        <?php $status = 'Tidak ada file'; ?>
                        @if($row2->nama_file_persyaratan != '')
                          @if(file_exists('upload/file_berkas/'.$row2->nama_file_persyaratan))
                            <?php $ext = explode('.',$row2->nama_file_persyaratan); ?>
                            <?php $status = '<a href="javascript:void(0)" onclick="view_lapiran(\''.$row2->id_jenis_persyaratan.'\', \''.$row2->id_surat.'\', \''.$row2->id_surat_keterangan.'\')" class="btn btn-sm btn-success">File terlampir</a>'; ?>
                            <?php array_push($persyaratan_required,str_replace("/","_",$berkas_persyaratan[$i]->nama_variable)) ?>
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
          <button id="button-submit-sip" type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Ajukan Permohonan
              <span style="margin-left: 5px;" class="fa fa-save"></span>
            </button>
          <a href="{{ route('surat_keterangan') }}" class="btn btn-warning btn-cencel pull-right">
            <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
          </a>
        </div>
      </form>
    </div><!-- /.box -->
  </section>
</div>
<div class="modal-dialog"></div>
<div class="other-page"></div>
@endsection

@section('js')
  <script src="{{ asset('js/validate.js') }}"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
  <?php
  /*
  // for ($i = 0; $i < count($berkas_persyaratan); $i++)
  // if(in_array(str_replace("/","_",$berkas_persyaratan[$i]->nama_variable),$persyaratan_required))
  // else
  // var varia = '{{str_replace("/","_",$berkas_persyaratan[$i]->nama_variable)}}';
  // $('#'+varia).attr('required','required');
  // endif
  // endfor
  */
  ?>

     $(document).ready(function () {
      $('input.disablecopypaste').bind('copy paste', function (e) {
         e.preventDefault();
      });
      $('textarea.disablecopypaste').bind('copy paste', function (e) {
         e.preventDefault();
      });
    });
 
    function cekAngkaHuruf(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) || (charCode == 44 || charCode == 46)  || charCode == 32 || charCode == 45 || charCode == 47 || charCode == 64){
        return true;
      }else{
        return false;
      }
    }

  // VIEW LAMPIRAN
  function view_lapiran(id, id_surat, id_surat_keterangan){
    $.post("{{ route('get_file_berkas') }}", {id:id, id_surat:id_surat, id_surat_keterangan:id_surat_keterangan}).done(function(data){
      console.log(id);
      console.log(id_surat);
      console.log(id_surat_keterangan);
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
  }

  function checkSize(input_id){
    var input = document.getElementById(input_id);
    if(input.files && input.files.length == 1){
      if (input.files[0].size > 2097152) {
        $('#info-file-'+input_id).css('display', 'block');
        $('#button-submit-sip').attr('disabled', 'disabled');
        return false;
      }
    }
    $('#button-submit-sip').removeAttr('disabled');
    $('#info-file-'+input_id).css('display', 'none');
    return true;
  }

  $('.doAddTempat').click(function(){
    $.post('{{ route('formSuket') }}').done(function(data) {
      if (data.status == 'success') {
        $('.modal-dialog').html(data.content);
      }
    }).fail(function() {
      swal('',"Failed to Reject Order!","error");
    });
  });

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
