<div class="box-body main-layer">
  <form class="form-save">
    <input type="hidden" name="id_chatbot" value="{{( $chatbot) ? $chatbot->id_chatbot : '' }}">
    <input type="hidden" name="parent_id" value="{{( $parent_id) ? $parent_id : '' }}">

    <div class="form-group">
      <label>Angka / Huruf / Romawi</label>
      <input type="text" name="angka" class="form-control" value="{{ ($chatbot) ? $chatbot->angka : '' }}">
    </div>
    <div class="form-group">
      <label>Pertanyaan</label>
      <input type="text" name="pertanyaan" class="form-control" value="{{ ($chatbot) ? $chatbot->pertanyaan : '' }}">
    </div>
    <div class="form-group">
      <label class="">Jawaban <span class="text-danger">(Kosongi Jawaban Jika Masih Ada Pertanyaan Lagi Setelahnya)</span></label>
      <textarea class="ckeditor" id='editor' aria-labelledby="document" placeholder="Tulis template pencabutan surat izin praktik baru..." cols="30" rows="10">
        {{ ($chatbot) ? $chatbot->jawaban : '' }}
      </textarea>
    </div>
  </form>

  <button type="button" class="btn btn-warning btn-cancel">Kembali</button>
  <button type="button" class="btn btn-info btn-submit float-right">Simpan</button>
</div>

<script type="text/javascript">
  $('.btn-cancel').click(function(e) {
   $('.other-content').html('');
   $('.main-layer').show();
 })

  $('.btn-submit').click(function(e){
   e.preventDefault();
   $('.btn-submit').html('Please wait...').attr('disabled', true);

   var angka = $('input[name="angka"]').val();
   var pertanyaan = $('input[name="pertanyaan"]').val();

   if (angka == '') {
     swal('Whoops !', 'Kolom Angka / Huruf / Romawi Harus Diisi', 'warning');
       $('.btn-submit').html('Simpan').removeAttr('disabled');
   } else if (pertanyaan == '') {
     swal('Whoops !', 'Kolom Pertanyaan Harus Diisi', 'warning');
       $('.btn-submit').html('Simpan').removeAttr('disabled');
   } else {

     var data  = new FormData($('.form-save')[0]);
     data.append('jawaban',$('#editor').val())
   // console.log(data);
   $.ajax({
     url: "{{ route('simpanChatBot') }}",
     type: 'POST',
     data: data,
     async: true,
     cache: false,
     contentType: false,
     processData: false
   }).done(function(data){
     if(data.status == 'success'){
       swal("Success !", data.message, "success");
       $('#detail-dialog').modal('hide');
       $('.other-content').html('');
       $('.main-layer').show();
       loadData()
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
 }
});

  function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
      return $1.toUpperCase();
    });
  }

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
  </script>

