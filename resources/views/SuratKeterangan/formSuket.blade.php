<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
  <div class="modal-dialog modal-besar">
    <div class="modal-content">
      <div class="modal-header modalHeader p-t-15">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-plus"></i> Tambah Tempat Praktek</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 modal_body_map">
            <form class="form-save">
              <div class="form-group" style="margin: 0px;">
                <label>SIP Ke<span class="colorRed">*</span></label>
                <input type="number" style="text-transform:uppercase" class="form-control input-sm disablecopypaste" placeholder="SIP Ke" value="" name="sip_ke" onkeypress="return cekAngka(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>
              <div class="form-group" style="margin: 0px;">
                <label>Nama Praktek<span class="colorRed">*</span></label>
                <input type="text" style="text-transform:uppercase" class="form-control input-sm disablecopypaste" placeholder="Nama Praktek" value="" name="nama_tempat_praktik" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Alamat Praktek<span class="colorRed">*</span></label>
                <textarea style="text-transform:uppercase" oninput="this.value = this.value.toUpperCase()" class="form-control disablecopypaste" placeholder="Alamat Praktek" name="alamat_tempat_praktik" class="form-control" onkeypress="return cekAngkaHuruf(event)"></textarea>
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>No SIP<span class="colorRed">*</span></label>
                <input type="text" style="text-transform:uppercase" class="form-control input-sm disablecopypaste" placeholder="No SIP" value="" name="no_sip" onkeypress="return cekAngka(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>No STR<span class="colorRed">*</span></label>
                <input type="text" style="text-transform:uppercase" class="form-control input-sm disablecopypaste" placeholder="No STR" value="" name="no_str" onkeypress="return cekAngkaHuruf(event)">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="form-group" style="margin: 0px;">
                <label>Upload SIP<span class="colorRed">*</span></label>
                <input type="file" id="file_sip" accept=".pdf" class="form-control input-sm" name="file_sip">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="clearfix" style="margin-bottom: 10px;"></div>
              <div class="form-group" style="margin: 0px;">
                <label>Tanggal Pengajuan<span class="colorRed">*</span></label>
                <input type="text" style="text-transform:uppercase" class="form-control day" data-date-format="dd-mm-yyyy" name="tanggal_pengajuan" placeholder="dd/mm/yyyy">
              </div>
              <div class="clearfix" style="margin-bottom: 10px;"></div>

              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0 m-b-15">
                <div class="col-sm-12 m-b-0">
                  <p class="text-right">
                    <button type="button" class="btn btn-warning m-t-15 waves-effect btn-cancel" data-dismiss="modal"><i class="fa fa-chevron-left fs-14 m-r-5"></i> Kembali</button>
                    <button type="button" class="btn btn-success m-t-15 waves-effect btn-submit">Simpan <i class="fa fa-save fs-14 m-l-5"></i></button>
                  </p>
                </div>
              </div>
            </form>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var onLoad = (function() {
    $('#detail-dialog').find('.modal-dialog').css({
      'width': '70%'
    });
    $('#detail-dialog').modal('show');
  })();
  $('#detail-dialog').on('hidden.bs.modal', function() {
    $('.modal-dialog').html('');
  });

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
      if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 32 || charCode == 46 || charCode == 64)
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
      if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) || (charCode == 44 || charCode == 46)  || charCode == 32 || charCode == 46 || charCode == 64){
        return true;
      }else{
        return false;
      }
    }

  $("input[type=text]").keyup(function(){
    $(this).val( $(this).val().toUpperCase() );
  });

  $(document).ready(function() {
    $('.day').datepicker({
      format: 'yyyy/mm/dd',
      todayHighlight: true,
      autoclose: true,
      todayBtn: "linked"
    });
  });

  $('.btn-cancel').click(function(e) {
    e.preventDefault();
    $('#panel-add').animateCss('bounceOutDown');
    $('.other-page').fadeOut(function() {
      $('.other-page').empty();
      $('.main-layer').fadeIn();
    });
  });

  $('.btn-submit').click(function(e){
    e.preventDefault();
    var tag = '';
    $('.btn-submit').html('Please wait...').attr('disabled', true);
    var data  = new FormData($('.form-save')[0]);
    $.ajax({
      url: "{{ route('simpanFormSuket') }}",
      type: 'POST',
      data: data,
      async: true,
      cache: false,
      contentType: false,
      processData: false
    }).done(function(data){
      if(data.status == 'success'){
        $('#detail-dialog').modal('hide');
        swal("Success !", data.message, "success");
        $.each(data.data, function(k,v){
          tag += '<div class="form-group" style="margin: 0px;">';
          tag += '<div class="col-sm-1">'+v.sip_ke+'</div>';
          tag += '<div class="col-sm-11">';
          tag += '<b>'+v.nama_tempat_praktik+'</b><br>';
          tag += '('+v.nomor_str+')<br>'+v.alamat_tempat_praktik;
          tag += '</div>';
          tag += '</div>';
        });
        $("#tempatPraktek").html(tag);
      } else if(data.status == 'error') {
        $('.btn-submit').html('Simpan <span class="fa fa-save"></span>').removeAttr('disabled');
        swal('Whoops !', data.message, 'warning');
      } else {
        $('.btn-submit').html('Simpan <span class="fa fa-save"></span>').removeAttr('disabled');
        swal('Whoops !', 'Kolom Tidak Boleh Kosong !!', 'error');
      }
    }).fail(function() {
      swal("MAAF !","Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
      $('.btn-submit').html('Simpan <span class="fa fa-save"></span>').removeAttr('disabled');
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
