@extends('layouts.admin-template')
@section('content')

<?php
  $id_jenis_surat             = '';
  $nama_surat                 = '';
  $nomor_surat                = '0';
  $maksimal_pengajuan         = '';
  $format_str                 = '';
  $jenis_praktik              = '';
  $jenis_waktu_praktik        = '';
  $syarat_pengajuan           = '';
  $syarat_pencabutan          = '';
  $syarat_perpanjangan        = '';
  $syarat_pindah_tempat        = '';
  $paraf_kasi                 = url('/').'/dist/img/paraf_1.png';
  $paraf_kabid                = url('/').'/dist/img/paraf_2.png';
  $ttd_kadinkes               = url('/').'/dist/img/ttd.png';
  $stempel_dinkes             = url('/').'/dist/img/stempel.png';

  if (isset($jsurat)) {
    $id_jenis_surat             = $jsurat->id_jenis_surat;
    $nama_surat                 = $jsurat->nama_surat;
    $nomor_surat                = $jsurat->nomor_surat;
    $maksimal_pengajuan         = $jsurat->maksimal_pengajuan;
    $format_str                 = $jsurat->format_str;
    $jenis_praktik              = $jsurat->jenis_praktik;
    $jenis_waktu_praktik        = $jsurat->jenis_waktu_praktik;
    $syarat_pengajuan           = $jsurat->syarat_pengajuan;
    $syarat_pencabutan          = $jsurat->syarat_pencabutan;
    $syarat_perpanjangan        = $jsurat->syarat_perpanjangan;
    $syarat_pindah_tempat       = $jsurat->syarat_pindah_tempat;
    $paraf_kasi                 = url('/').'/upload/file_master/'.$jsurat->paraf_kasi;
    $paraf_kabid                = url('/').'/upload/file_master/'.$jsurat->paraf_kabid;
    $ttd_kadinkes               = url('/').'/upload/file_master/'.$jsurat->ttd_kadinkes;
    $stempel_dinkes             = url('/').'/upload/file_master/'.$jsurat->stempel_dinkes;
  }
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href=""> Tambah Jenis Surat</a></li>
      <li class="active">{{ $title }}</li>
    </ol>
  </section>

  <section class="content">
    <div class="box" style="border-top:none">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="box box-info" id="btn-add" style="border-top:none">
        <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;"></h4>
          <div class="box-body">
            <div class="col-md-12">
              <form action="{{ route('save_jenis_surat') }}" method="post" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id_jenis_surat" value="{{ $id_jenis_surat }}">
              <div class="row">
                <div class="col-md-12">

                  <div class="form-group">
                    <label class="col-sm-12 control-label">Nama Surat Izin Praktik : </label>
                    <div class="col-sm-12">
                      <input type="text" required name="nama_surat" class="form-control" value="{{ $nama_surat }}" placeholder="Tulis nama surat izin praktik baru...">
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>

                  <div class="form-group">
                    <label class="col-sm-12 control-label">Nomor Surat : </label>
                    <div class="col-sm-12">
                      <input type="text" required name="nomor_surat" class="form-control" value="{{ $nomor_surat }}" placeholder="Tulis nomor surat baru...">
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>

                  <?php if ($id_jenis_surat != 28): ?>
                    <div class="form-group">
                      <label class="col-sm-12 control-label">Jumlah Maksimal Pengajuan : </label>
                      <div class="col-sm-12">
                        <input type="number" required name="maksimal_pengajuan" class="form-control" value="{{ $maksimal_pengajuan }}" placeholder="Tulis maksimal surat izin praktik baru...">
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label">Format Penulisan STR : </label>
                      <div class="col-sm-12">
                        <input type="text" required name="format_str" class="form-control" value="{{ $format_str }}" placeholder="Tulis format STR baru...">
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label">Jenis Praktik : </label>
                      <div class="col-sm-10">
                        <select name="jenis_praktik[]" required class="form-control select2" multiple="multiple" data-placeholder="Pilih Jenis Praktik">
                          <?php foreach ($list_jenis_praktik as $row): ?>
                            <option value="{{ $row->id_jenis_praktik }}"
                              <?php if (preg_match("/{$row->id_jenis_praktik}/i", $jenis_praktik)){ echo "selected='selected'";}?>>
                              {{ $row->nama_jenis_praktik }}
                            </option>
                          <?php endforeach ?>
                        </select>
                      </div>
                      <div class="col-sm-2">
                        <input type="submit" name="submit" class="btn btn-info" value="Terapkan">
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label">Jenis Waktu Praktik : </label>
                      <div class="col-sm-12">
                        <select class="form-control" name="jenis_waktu_praktik">
                          <option <?php if ($jenis_waktu_praktik == 'Tanpa Waktu Praktik'){ echo "selected='selected'";}?> value="Tanpa Waktu Praktik">Tanpa Waktu Praktik</option>
                          <option <?php if ($jenis_waktu_praktik == 'Waktu Praktik'){ echo "selected='selected'";}?> value="Waktu Praktik">Waktu Praktik</option>
                          <option <?php if ($jenis_waktu_praktik == 'Waktu Praktik dengan Shift'){ echo "selected='selected'";}?> value="Waktu Praktik dengan Shift">Waktu Praktik dengan Shift</option>
                        </select>
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <?php endif ?>

                  <div class="form-group">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-3">
                          <label>Paraf Kasi</label>
                          <br>
                          <img id="output_1" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ $paraf_kasi }}">
                          <input name="paraf_kasi" accept="image/*" onchange="loadFileParaf_1(event)" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>

                        <div class="col-md-3">
                          <label>Paraf Kabid</label>
                          <br>
                          <img id="output_2" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ $paraf_kabid }}">
                          <input name="paraf_kabid" accept="image/*" onchange="loadFileParaf_2(event)" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>

                        <div class="col-md-3">
                          <label>TTD Kadinkes</label>
                          <br>
                          <img id="output_ttd" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ $ttd_kadinkes }}">
                          <input name="ttd_kadinkes" accept="image/*" onchange="loadFileParaf_ttd(event)" style="width: 130px" type="file" class="form-control input-sm">
                        </div>

                        <div class="col-md-3">
                          <label>Stampel</label>
                          <br>
                          <img id="output_stampel" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ $stempel_dinkes }}">
                          <input name="stempel_dinkes" accept="image/*" onchange="loadFileParaf_stampel(event)" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>

                  <div class="form-group">
                    <div class="col-md-12">
                    <label>Persyaratan Pengajuan</label>
                      <select name="syarat_pengajuan[]" class="form-control select2" multiple="multiple" data-placeholder="Pilih Persyaratan">
                        <?php foreach ($jenis_persyaratan as $row): ?>
                          <?php $explodes = explode(',', $syarat_pengajuan); ?>
                          <option value="{{ $row->id_jenis_persyaratan }}"
                            <?php if (in_array($row->id_jenis_persyaratan, $explodes)){ echo "selected='selected'";}?>>
                            {{ $row->nama_jenis_persyaratan }}
                          </option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>

                  <?php if ($id_jenis_surat != 28): ?>
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>Persyaratan Pencabutan</label>
                        <select name="syarat_pencabutan[]" class="form-control select2" multiple="multiple" data-placeholder="Pilih Persyaratan">
                          <?php foreach ($jenis_persyaratan as $row): ?>
                            <?php $explodes = explode(',', $syarat_pencabutan); ?>
                            <option value="{{ $row->id_jenis_persyaratan }}"
                              <?php if (in_array($row->id_jenis_persyaratan, $explodes)){ echo "selected='selected'";}?>>
                              {{ $row->nama_jenis_persyaratan }}
                            </option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>

                    <div class="form-group">
                      <div class="col-md-12">
                        <label>Persyaratan Perpanjangan</label>
                        <select name="syarat_perpanjangan[]" class="form-control select2" multiple="multiple" data-placeholder="Pilih Persyaratan">
                          <?php foreach ($jenis_persyaratan as $row): ?>
                            <?php $explodes = explode(',', $syarat_perpanjangan); ?>
                            <option value="{{ $row->id_jenis_persyaratan }}"
                              <?php if (in_array($row->id_jenis_persyaratan, $explodes)){ echo "selected='selected'";}?>>
                              {{ $row->nama_jenis_persyaratan }}
                            </option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>

                    <div class="form-group">
                      <div class="col-md-12">
                        <label>Persyaratan Pindah Tempat Praktik</label>
                        <select name="syarat_pindah_tempat[]" class="form-control select2" multiple="multiple" data-placeholder="Pilih Persyaratan">
                          <?php foreach ($jenis_persyaratan as $row): ?>
                            <?php $explodes = explode(',', $syarat_pindah_tempat); ?>
                            <option value="{{ $row->id_jenis_persyaratan }}"
                              <?php if (in_array($row->id_jenis_persyaratan, $explodes)){ echo "selected='selected'";}?>>
                              {{ $row->nama_jenis_persyaratan }}
                            </option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>

                  <?php endif ?>

                  <hr>
                  <?php foreach ($list_ts_praktik as $row): ?>
                    <?php $templates ?>
                    <?php foreach ($template_surat as $row2): ?>
                      <?php if ($row->id_jenis_praktik == $row2->id_jenis_praktik && $row2->jenis == 'PENGAJUAN'):
                        $templates = $row2->template_surat;
                        break;
                      endif ?>
                    <?php endforeach ?>
                    <div class="form-group">
                      <label class="col-sm-12 control-label">
                        <?php if ($id_jenis_surat == 28): ?>
                          Template Keterangan :
                        <?php else: ?>
                          Template Surat Izin Praktik {{$row->nama_jenis_praktik}} :
                        <?php endif ?>
                      </label>
                      <div class="col-sm-12">
                        <textarea style="text-transform:uppercase" class="form-control" class="ckeditor" id='editor' required name="{{ $row->nama_variable }}" aria-labelledby="document" placeholder="Tulis template surat izin praktik baru...">{{ $templates }}</textarea>
                      </div>
                    </div>
                    <div class="clearfix" style="margin-bottom: 10px;"></div>
                  <?php endforeach ?>

                  <?php if ($id_jenis_surat != 28): ?>
                    <br><br>
                    <?php foreach ($list_ts_praktik as $row): ?>
                      <?php $templates ?>
                      <?php foreach ($template_surat as $row2): ?>
                        <?php if ($row->id_jenis_praktik == $row2->id_jenis_praktik && $row2->jenis == 'PENCABUTAN'):
                          $templates = $row2->template_surat;
                          break;
                        endif ?>
                      <?php endforeach ?>
                      <div class="form-group">
                        <label class="col-sm-12 control-label">Template Pencabutan Surat Izin Praktik {{$row->nama_jenis_praktik}} : </label>
                        <div class="col-sm-12">
                          <textarea style="text-transform:uppercase" class="form-control ckeditor" id='editor1' required name="pencabutan_{{ $row->nama_variable }}" aria-labelledby="document" placeholder="Tulis template pencabutan surat izin praktik baru...">{{ $templates }}</textarea>
                        </div>
                      </div>
                      <div class="clearfix" style="margin-bottom: 10px;"></div>
                    <?php endforeach ?>
                  <?php endif ?>

                </div>
              </div>
               <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Simpan
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                <a href="{{ route('jenis_surat') }}" class="btn btn-warning btn-cencel pull-right">
                  <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                </a>
              </div>
             </form>
            </div>
          </div>
        </div>
      <div class="col-md-2"></div>
    </div>
   </div>
  </section>
  <div class="clearfix"></div>
</div>

@endsection

@section('js')
  <script type="text/javascript">
    $('.select2').select2();

    var loadFileParaf_1 = function(event) {
      var output = document.getElementById('output_1');
      output.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadFileParaf_2 = function(event) {
      var output = document.getElementById('output_2');
      output.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadFileParaf_ttd = function(event) {
      var output = document.getElementById('output_ttd');
      output.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadFileParaf_stampel = function(event) {
      var output = document.getElementById('output_stampel');
      output.src = URL.createObjectURL(event.target.files[0]);
    };

    $('textarea#editor').ckeditor(
        CKEDITOR.config.extraPlugins = 'texttransform',
        {width:'100%', height: '150px', toolbar: [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: ['NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-'] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'styles', items: [ 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'texttransform', items: [ 'TransformTextToUppercase', 'TransformTextToLowercase', 'TransformTextCapitalize', 'TransformTextSwitcher' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
        CKEDITOR.env.isCompatible = true,
    ]});

    $('textarea#editor1').ckeditor(
        CKEDITOR.config.extraPlugins = 'texttransform',
        {width:'100%', height: '150px', toolbar: [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: ['NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-'] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'styles', items: [ 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'texttransform', items: [ 'TransformTextToUppercase', 'TransformTextToLowercase', 'TransformTextCapitalize', 'TransformTextSwitcher' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
        CKEDITOR.env.isCompatible = true,
    ]});
  </script>
@endsection
