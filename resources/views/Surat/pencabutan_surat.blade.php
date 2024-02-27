@extends('layouts.admin-template')

@section('content')



<?php

  $id_surat               = '0';

  $id_jenis_sarana        = '';

  $nama_tempat_praktik    = '';

  $alamat_tempat_praktik  = '';

  $nomor_str              = '';

  $nomor_op               = '';

  $waktu_praktik          = '';

  $id_jenis_praktik       = '';

  $sip_ke                 = count($list_surat)+1;

  

  if (isset($surat)) {

    $id_surat               = $surat->id_surat;

    $id_jenis_sarana        = $surat->id_jenis_sarana;

    $nama_tempat_praktik    = $surat->nama_tempat_praktik;

    $alamat_tempat_praktik  = $surat->alamat_tempat_praktik;

    $nomor_str              = $surat->nomor_str;

    $nomor_op               = $surat->nomor_op;

    $waktu_praktik          = $surat->waktu_praktik;

    $id_jenis_praktik       = $surat->id_jenis_praktik;

    $sip_ke                 = $surat->sip_ke;

  }

?>



<style type="text/css">

  .colorRed { color: #f00 !important; }

</style>



<div class="content-wrapper">

  <section class="content-header">


    
    <h1>@if($pencabutan_pindah == 'true') Pencabutan Pindah Tempat Praktik <b>{{ $jenis_surat->nama_surat }}</b> @else Pencabutan <b>{{ $jenis_surat->nama_surat }}</b>@endif</h1>    

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

      <li><a href="#"> Surat Izin</a></li>

      <li class="active"> Pencabutan {{ $jenis_surat->nama_surat }}</li>

    </ol>

  </section>



  <section class="content">

    <div class="box box-info" id="btn-add" style="border-top:none">

      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;"><i class="fa fa-plus-square iconLabel m-r-15"></i> Formulir Pencabutan {{ $jenis_surat->nama_surat }}</h4>

      <form class="form form-save" action="{{ route('pencabutan_surat') }}" enctype="multipart/form-data" method="post">

        @csrf

        <input type="hidden" name="id_surat" value="{{ $id_surat }}">
        <input type="hidden" name="jenis_sarana" value="{{ $id_jenis_sarana }}">
        <input type="hidden" name="sip_ke" value="{{ $sip_ke }}">
        <input type="hidden" name="jenis_praktik" value="{{ $id_jenis_praktik }}">
        <input type="hidden" name="pencabutan_pindah" value="{{ $pencabutan_pindah }}">

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

            <h4 style="font-weight: bold;">Formulir Pencabutan</h4>



            <div class="form-group" style="margin: 0px;">

              <label>Surat Izin Ke (Sesuai nomor STR)<span class="colorRed">*</span></label>

              <input type="text" readonly name="sip_ke" class="form-control input-sm" value="{{ $sip_ke }}">

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>



            <div class="form-group" style="margin: 0px;">

              <label>Nomor STR<span class="colorRed">*</span></label>

              <input type="text" readonly name="nomor_str" class="form-control input-sm" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $nomor_str; } ?>">

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>



            <div class="form-group" style="margin: 0px;">

              <label>No. Rekomendasi OP<span class="colorRed">*</span></label>

              <input type="text" readonly name="nomor_op" class="form-control input-sm" value="<?php if ($perpanjangan != '/perpanjangan'){ echo $nomor_op; } ?>">

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>

            

            <div class="form-group" style="margin: 0px;">

              <label>Nama Tempat Praktik<span class="colorRed">*</span></label>

              <input type="text" readonly name="nama_tempat_praktik" class="form-control input-sm" value="{{ $nama_tempat_praktik }}">

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>



            <div class="form-group" style="margin: 0px;">

              <label>Alamat Tempat Praktik<span class="colorRed">*</span></label>

              <input type="text" readonly name="alamat_tempat_praktik" class="form-control input-sm" value="{{ $alamat_tempat_praktik }}">

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>



            <div class="form-group" style="margin: 0px;">

              <label>Jenis Sarana<span class="colorRed">*</span></label>

              <select name="jenis_sarana" class="form-control" readonly>

                <?php foreach ($list_jenis_sarana as $row): ?>

                  <option <?php if ($row->id_jenis_sarana == $id_jenis_sarana){ echo "selected='selected'"; } ?> value="{{$row->id_jenis_sarana}}" >{{$row->nama_sarana}}</option>

                <?php endforeach ?>

              </select>

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>



            <div class="form-group" style="margin: 0px;">

              <label>Jenis Praktik<span class="colorRed">*</span></label>

              <select name="jenis_praktik" readonly class="form-control">

                <?php foreach ($list_ts_praktik as $row): ?>

                  <option <?php if ($row->id_jenis_praktik == $id_jenis_praktik){ echo "selected='selected'"; } ?> value="{{$row->id_jenis_praktik}}" >{{$row->nama_jenis_praktik}}</option>

                <?php endforeach ?>

              </select>

            </div>

            <div class="clearfix" style="margin-bottom: 10px;"></div>



            <input type="hidden" id="input_waktu_praktik" name="waktu_praktik" value="">

            <?php if ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik' || $jenis_surat->jenis_waktu_praktik == 'Waktu Praktik dengan Shift'): ?>

               <div class="form-group" style="margin: 0px;">

                <label>Waktu Praktik<span class="colorRed">*</span></label>

                <div id="waktu_praktik">

                  <?php echo $waktu_praktik ?>

                </div>

              </div>

              <div class="clearfix" style="margin-bottom: 10px;"></div>

            <?php endif ?>

          </div>

          @if($pencabutan_pindah == 'true')
            <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>
            <h4 style="font-weight: bold;">Form Pengajuan Pindah Tempat Praktik</h4>
            <h4 style="font-weight: bold;">(<span style="color:red;">Upload File Dokumen Pendukung * </span> serta Isikan Nomor Rekom, Nama dan Alamat Tempat Praktik yang Baru untuk Dilakukan Pengajuan)</h4>
            <?php
              $no = 1;
              $jumlah = count($berkas_persyaratan_baru)/2;
              ?>
              <?php for ($i = 0; $i < count($berkas_persyaratan_baru); $i++){ ?>
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label class="col-sm-12 control-label">
                      <?php echo $no++ ?>. <?php echo $berkas_persyaratan_baru[$i]->nama_jenis_persyaratan; ?>
                      <span class="colorRed">* Mak File 2MB</span></br>                      
                    </label>
                    <div class="col-sm-12">
                      <input style="border: solid 1px #d2d6de; padding: 5px;" accept="<?php echo $berkas_persyaratan_baru[$i]->jenis_input; ?>" type="file" onchange="checkSize('<?php echo str_replace('/', '', $berkas_persyaratan_baru[$i]->nama_variable); ?>')" id="<?php echo str_replace('/', '', $berkas_persyaratan_baru[$i]->nama_variable); ?>" name="<?php echo $berkas_persyaratan_baru[$i]->nama_variable; ?>_terbaru" class="form-control input-sm input-berkas-pengajuan">
                      <span class="colorRed">*Extensions : PDF / JPG / JPEG / PNG</span>
                      <span id="info-file-<?php echo str_replace('/', '', $berkas_persyaratan_baru[$i]->nama_variable); ?>" style="color:red; display:none;">File yang anda masukan lebih dari 2MB</span>                      
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                </div>
              <?php } ?>    

              <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>
              </br>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label class="col-sm-12 control-label">
                    Nomor Rekom Terbaru
                    <span class="colorRed">*</span>
                  </label>
                  <div class="col-sm-12">
                    <input style="border: solid 1px #d2d6de; padding: 5px;" type="text"  name="nomor_rekom_terbaru" class="form-control input-sm">
                  </div>
                </div>
                <div class="clearfix" style="margin-bottom: 10px;"></div> 
              </div> 

              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label class="col-sm-12 control-label">
                    Nama Tempat praktik
                    <span class="colorRed">*</span>
                  </label>
                  <div class="col-sm-12">
                    <input style="border: solid 1px #d2d6de; padding: 5px;" type="text"  name="nama_tempat_praktik_terbaru" class="form-control input-sm">
                  </div>
                </div>
                <div class="clearfix" style="margin-bottom: 10px;"></div> 
              </div> 

              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label class="col-sm-12 control-label">
                    Alamat Tempat praktik
                    <span class="colorRed">*</span>
                  </label>
                  <div class="col-sm-12">
                    <input style="border: solid 1px #d2d6de; padding: 5px;" type="text"  name="alamat_tempat_praktik_terbaru" class="form-control input-sm">
                  </div>
                </div>
                <div class="clearfix" style="margin-bottom: 10px;"></div> 
              </div>           
          @endif

          <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>
          @if($pencabutan_pindah == 'true')
          <h4 style="font-weight: bold;">Dokumen Pendukung SIP Lama</h4>          
          @else
          <h4 style="font-weight: bold;">Upload Dokumen Pendukung</h4>
          @endif

            <?php 
              $no = 1;
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
                  <input style="border: solid 1px #d2d6de; padding: 5px;" type="file" accept="image/*,.pdf" name="<?php echo $berkas_persyaratan[$i]->nama_variable; ?>" id="{{str_replace("/","_",$berkas_persyaratan[$i]->nama_variable)}}" class="form-control input-sm">
                  <?php if (isset($syarat_persyaratan)): ?>
                    <?php foreach ($syarat_persyaratan as $row2): ?>
                      <?php if ($row2->id_jenis_persyaratan == $berkas_persyaratan[$i]->id_jenis_persyaratan): ?>
                        <?php $status = 'Tidak ada file'; ?>
                        @if($row2->nama_file_persyaratan != '')
                          @if(file_exists('upload/file_berkas/'.$row2->nama_file_persyaratan))
                            <?php $status = '<a href="javascript:void(0)" onclick="view_lapiran(\''.$row2->id_jenis_persyaratan.'\', \''.$surat->id_surat.'\')" class="btn btn-sm btn-success">File terlampir</a>'; ?>
                            <?php
                            $persyaratan_required[$i] = str_replace("/","_",$berkas_persyaratan[$i]->nama_variable);
                            ?>
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
                  Dengan menekan tombol 
                  <b style="color: red;">
                  @if($pencabutan_pindah == 'true')
                    *Ajukan Pencabutan dan Permohonan Baru</b> 
                  @else
                    *Ajukan Pencabutan</b> 
                  @endif
                    anda harus mengisi semua form pengajuan pindah tempat.
                </label>
              </br>              
            </div>
          </div>

          <button type="button" class="btn btn-primary pull-right" id="btn_pencabutan" style="margin-left: 15px;">
            @if($pencabutan_pindah == 'true') 
            Ajukan Pencabutan dan Permohonan Baru
            @else 
            Ajukan Pencabutan 
            @endif
            <span style="margin-left: 5px;" class="fa fa-save"></span>
          </button>
          <a href="@if(isset($surat)){{route('surat')}}/list/{{$surat->id_jenis_surat}}@else{{route('surat')}}@endif" class="btn btn-warning btn-cencel pull-right">
            <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
          </a>
        </div>

      </form>

    </div><!-- /.box -->

  </section>

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

<!-- <div class="modal-dialog"></div> -->

@endsection
@section('js')

<script type="text/javascript">
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

  $(document).ready(function(){
    requireder();
  })

  $('#invalidCheck2').click(function(){
    requireder();
  });

  function requireder(){
   var centang = $('#invalidCheck2').is(':checked');
    if(centang==true){
       $('.btn-primary').show();
       $('input[name=nama_tempat_praktik_terbaru]').attr('required','required');
       $('input[name=alamat_tempat_praktik_terbaru]').attr('required','required');
       $('input[name=nomor_rekom_terbaru]').attr('required','required');       
    }else{
       $('.btn-primary').hide();
       $('input[name=nama_tempat_praktik_terbaru]').removeAttr('required');
       $('input[name=alamat_tempat_praktik_terbaru]').removeAttr('required');
       $('input[name=nomor_rekom_terbaru]').removeAttr('required');
    }
  }

  // VIEW LAMPIRAN
  function view_lapiran(id, id_surat){
    $.post("{{ route('get_file_berkas') }}", {id:id, id_surat:id_surat}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
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

  $('#btn_pencabutan').click(function(e){
   e.preventDefault();
   console.log(console.log);

   var url = "{{ route('save_surat_preview') }}";

   if (!$('.form-save')[0].checkValidity()) { // VALIDATE THE FORM
   $('.form-save')[0].reportValidity();
    return; // RETURN NOTHING WHEN INVALIDATED
   }
   $('#btn_pencabutan').html('Please wait...').attr('disabled', true);
   var data  = new FormData($('.form-save')[0]);
   data.append('btn_type', 'preview');
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
      console.log(data);
      var content = '';
      content = '<iframe src="{{ url('/') }}/upload/file_sip_salinan/'+data.data.file_surat_pencabutan_sip_salinan+'" width="100%" height="950px"></iframe>';
      $('#modal-body-view').html(content);           
      $('#id_surat_preview').val(data.data.id_surat);           
      $('#modal-body-preview').modal('show');
      $('#btn_pencabutan').html('Ajukan Permohonan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');

      // $('#data_form').trigger('submit');

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
        $('.form-save').trigger('submit');

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
      } else {
        $('#modal-body-preview').modal('hide');
      }
    });
    // console.log('closed dibawah');
  })

  $('#modal-body-preview').on('hidden.bs.modal', function () {
    var id_surat_preview = $('#id_surat_preview').val();
    $.post("{{ route('del_file_berkas') }}", {id_surat_preview:id_surat_preview}).done(function(data){
      if(data.status == 'success'){
        $('.modal-dialog').html(data.content);
      }
    });
    // console.log('closed modal');
  });


</script>

@endsection

