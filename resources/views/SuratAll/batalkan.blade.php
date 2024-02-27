<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header" style="background: #b6d1f2; text-shadow: 0 1px 0 #aecef4">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="product-detail-dialog" style="color: #fff; font-size: 18px; font-weight: 600;"><i class="fa fa-user m-r-15 m-l-5"></i> FORM Batalkan atau Mengembalikan Status </h4>
      </div>
      <div class="modal-body">
        <form class="form-save">
          @foreach($id as $val)
          <input type="hidden" name="id_surat[]" value="{{$val}}">
          @endforeach

          <div class="form-group">
            <label>Pilih Status</label>
            <select name="status" id="" class="form-control">
              @foreach($status as $key => $s)
              <option value="{{$status_val[$key]}}">{{$s}}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-info btn-submit" data-dismiss="modal">Simpan</button>
      </div>

    </div>

  </div>

</div>

<!-- /.modal -->

<script type="text/javascript">
  var onLoad = (function() {
    $('#detail-dialog').find('.modal-dialog').css({
      'width'     : '60%'
    });
    $('#detail-dialog').modal('show');
  })();
  $('#detail-dialog').on('hidden.bs.modal', function () {
    $('.modal-dialog').html('');
  });

  $('.btn-submit').click(function(e){
   e.preventDefault();
   $('.btn-submit').html('Please wait...').attr('disabled', true);
   var data  = new FormData($('.form-save')[0]);
   console.log(data);
   $.ajax({
     url: "{{ route('simpanBatalkan') }}",
     type: 'POST',
     data: data,
     async: true,
     cache: false,
     contentType: false,
     processData: false
   }).done(function(data){
         // $('.form-save').validate(data, 'has-error');
         if(data.status == 'success'){
           swal("Success !", data.message, "success");
           $('#detail-dialog').modal('hide');
            datagrid.reload();
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

