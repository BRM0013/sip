@extends('layouts.admin-template')
@section('content')

<?php
  $photo                  = '';

  if (isset($users)) {
    $photo = url('/').'/upload/users/'.$users->photo;
    $id    = $users->id;
  }
?>

<style type="text/css">
  .colorRed { color: #f00 !important; }
</style>

<div class="content-wrapper">
  <section class="content">
    <div class="box box-info" id="btn-add" style="border-top:none">
      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;"><i class="fa fa-plus-square iconLabel m-r-15"></i> Validasi Ulang Berkas Pengajuan SIP</h4>
      <form class="form" action="{{ route('save_uploadberkas') }}" enctype="multipart/form-data" method="post">
        @csrf
        <input type="hidden" name="id_user" value="{{ $id }}">
        <div class="box-body">
          <div class="col-md-12">
            <h4>Foto Resmi (Merah)</h4><i style="color:red"><h6>PAS FOTO ASLI (FILE DARI CD/FILE MENTAH/SOFTFILE)</h6></i>
            <img id="output" style="border: 2px solid gray; height: 150px; width: 150px;" src="{{ $photo }}">
            <input name="photo" accept="image/*" onchange="loadFile(event)" style="width: 150px;" type="file" class="form-control input-sm" @if(empty($photo))@endif>
            <i style="color:red">Ukuran maks 2 MB</i>
          </div>

          <div class="clearfix"></div>
          <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>

          <?php $x = 0; ?>
           @foreach ($list_surat as $key)
            <?php
            $no = 1;
            ?>
            <input type="hidden" name="id_surat[]" value="{{ $key->id_surat }}">
            <div class="col-md-6 col-xs-12">

              <h5 style="font-weight: bold;">Upload Dokumen Pendukung SIP ke {{$key->sip_ke}}</h5>

              @if (count($key->row) > 0)
                @foreach ($key->row as $syarat_pengajuan)
                <label class="col-sm-12 control-label">
                  {{ $no }}. {{ $syarat_pengajuan->nama_jenis_persyaratan }}
                  <span class="colorRed">* Maksimal 2MB</span>
                </label>

                <input style="border: solid 1px #d2d6de; padding: 5px;" accept="<?php echo $syarat_pengajuan->jenis_input; ?>" type="file" onchange="checkSize('{{ str_replace('/', '_', $syarat_pengajuan->nama_variable) }}_{{ $no }}_{{ $x }}')" id="{{ str_replace('/', '_', $syarat_pengajuan->nama_variable) }}_{{ $no }}_{{ $x }}" name="<?php echo $syarat_pengajuan->nama_variable; ?>[]" class="form-control input-sm input-berkas-pengajuan" required="">

                 <span id="info-file-{{ str_replace('/', '_', $syarat_pengajuan->nama_variable) }}_{{ $no }}_{{ $x }}" style="color:red; display:none;">File yang anda masukan lebih dari 2MB</span>
                  <!-- {{ $syarat_pengajuan->nama_file_persyaratan }} -->
                  <?php if (isset($syarat_pengajuan)): ?>
                      <?php if ($syarat_pengajuan->id_jenis_persyaratan == $syarat_pengajuan->id_jenis_persyaratan): ?>
                        <?php $status = 'Tidak ada file'; ?>

                        @if($syarat_pengajuan->nama_file_persyaratan != '')
                          @if(file_exists('upload/file_berkas/'.$syarat_pengajuan->nama_file_persyaratan))
                          <?php $status = '<a href="javascript:void(0)" onclick="view_lapiran(\''.$syarat_pengajuan->id_jenis_persyaratan.'\', \''.$key->id_surat.'\')" class="btn btn-sm btn-success">File terlampir</a>'; ?>
                          @endif
                        @endif

                        {!! $status !!}
                      <?php endif ?>
                  <?php endif ?>
                  <?php $no++ ?>
                @endforeach
                 <div class="clearfix" style="border-bottom: solid 1px #d2d6de"></div>
              @endif
            </div>
              <?php $x++; ?>
           @endforeach
            <div class="clearfix"></div>
        </div>

        <!--  <div class="form-group" style="float: left;">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="status_simpan" value="Simpan" id="invalidCheck2">
                <label class="form-check-label" for="invalidCheck2">
                 <b style="color: red;">lanjutkan untuk menekan tombol Simpan Validasi.</b>
                  Jika Semua <b style="color: red;">*File Terlampir </b> Sudah Benar, lanjutkan untuk menekan tombol Simpan Validasi.
                </label>
              </br>
            </div>
          </div> -->

         <div class="box-footer">
            <button id="button-submit-sip" type="submit" class="btn btn-primary pull-right btn-simpan btn-sm" style="margin-left: 15px;">Upload Berkas
              <span style="margin-left: 5px;" class="fa fa-save"></span>
            </button>
             <!-- <button id="button-submit-validasi" type="submit" class="btn btn-primary pull-right btn-simpan btn-sm" style="margin-left: 15px;">Simpan Validasi
              <span style="margin-left: 5px;" class="fa fa-save"></span>
            </button> -->
              <a href="{{ route('home') }}" class="btn btn-warning btn-cencel pull-right">
                <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
              </a>
         </div>

      </form>
    </div>
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

   // $('#invalidCheck2').click(function(){
   //      requireder();
   //    });

   //    function requireder(){
   //     var centang = $('#invalidCheck2').is(':checked');
   //     if(centang==true){
   //       $('#button-submit-sip').show();
   //       // $('#button-submit-validasi').show();
   //       $('#button-kembali').show()

   //       $('input[name=nama_variable').attr('required','required');
   //     }else{
   //       $('#button-submit-sip').show();
   //       // $('#button-submit-validasi').hide();
   //       $('#button-kembali').show()

   //       $('input[name=nama_variable').removeAttr('required');
   //     }
   //   }

  $('input[type=file]').on('change',function (e) {
      var extValidation = new Array(".jpg", ".jpeg", ".pdf", ".PDF", ".png", ".JPG", ".JPEG", ".PNG" );
      var fileExt = e.target.value;
      if (fileExt != '') {
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (extValidation.indexOf(fileExt) < 0) {
            swal('Extensi File Tidak Valid !','Upload file bertipe .jpg, .jpeg, .png atau .pdf, untuk dapat melakukan upload data...','warning')
            $(this).val("")
            return false;
        }
        else return true;
      }
  })

  function checkSize(input_id){
    const fi = document.getElementById(input_id);
    if (fi.files.length > 0) {
        for (const i = 0; i <= fi.files.length - 1; i++) {
        const fsize = fi.files.item(i).size;
        const file = Math.round((fsize / 1024));
            // The size of the file.
          if (file >= 2048) {
            $('#info-file-'+input_id).css('display', 'block');
            $('#'+input_id).val('');
            $('#button-submit-sip').attr('disabled', 'disabled');
            return false;
            // document.getElementById('size2').innerHTML = '<b style="color:red;">' + '* Anda harus upload ulang file' ;
          } else {
            return true;
            // alert('lanjut');
            // document.getElementById('size2').innerHTML = '<b>'
            // + file + '</b> KB' + ' ' + 'file sudah cukup' ;
          }
        }
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

</script>
@endsection
