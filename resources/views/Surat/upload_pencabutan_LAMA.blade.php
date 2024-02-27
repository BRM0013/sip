<style type="text/css">
    td{
        padding: 5px;
    }
    .accordion {
      background-color: #eee;
      color: #444;
      cursor: pointer;
      padding: 18px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.4s;
    }

    .active, .accordion:hover {
      background-color: #ccc; 
    }
    *{
        text-transform: uppercase;
    }

    .panel-accordion{
      padding: 0 18px;
      display: none;
      background-color: white;
      overflow: hidden;
    }

</style>


<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header" style="background: #b6d1f2; text-shadow: 0 1px 0 #aecef4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="product-detail-dialog" style="color: #fff; font-size: 18px; font-weight: 600;"><i class="fa fa-user m-r-15 m-l-5"></i> SURAT PENCABUTAN </h4>
            </div>
            <div class="modal-body">
                <label>UPLOAD FILE PENCABUTAN</label>
                <section class="panel panel-default m-b-0">
                    <div class="panel-body">
                        <form class="form-save form-inline">
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <input type="hidden" name="id" value="{{$surat->id_surat}}">
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-4">UPLOAD PENCABUTAN E-BUDDY</label>:
                                        <input type="file" name="file_ebuddy" class="form-control input-sm"  accept=".docx">
                                         @if($surat->file_ebuddy != '')
                                          @if(file_exists('upload/file_ebuddy/'.$surat->file_ebuddy))
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" onclick="modal_sip('file_ebuddy','{{$surat->file_ebuddy}}')">FIle terlampir</button>
                                          @endif
                                        @endif
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-4">NOMOR SURAT</label>:
                                          <div class="form-group mb-2">
                                              <span style="float:left;">440/</span>                                        
                                          </div>
                                          <div class="form-group mx-sm-2 mb-2 box-noSurat">
                                            @if($surat->nomor_surat == '0')
                                              <input type="text" name="nomor_surat" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" size="5"/ class="inNoSurat">
                                            @else
                                              <input type="text" name="nomor_surat" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" size="5"/ class="inNoSurat" value="{{$surat->nomor_surat}}">
                                              <!-- <span style="font-weight:bold;">{{ $surat->nomor_surat }}</span> -->
                                            @endif
                                          </div>
                                          <div class="form-group mb-2" style="margin-left:10px;">
                                            <?php
                                            $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                                             ?>
                                             <span>/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}                                                 
                                             </span>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </form>                       
                    </div>
                </section>               
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

    var acc = document.getElementsByClassName("accordion");
    for (var i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
        } else {
          panel.style.display = "block";
        }
      });
    }

     function modal_sip(title,image){
        $('.modal-title').html(title);
          var content = '';
          content = '<iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/') }}/upload/file_ebuddy/'+image+'" width="100%" height="950px"></iframe>';       
        $('.modal-body').html(content);
      }

    $('.btn-submit').click(function(e){
       e.preventDefault();
       $('.btn-submit').html('Please wait...').attr('disabled', true);
       var data  = new FormData($('.form-save')[0]);
       $.ajax({
         url: "{{ route('save_pencabutan_ebuddy') }}",
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
           swal('Whoops !', 'Kolom '+dt0+' Tidak Boleh Kosong !!', 'error');
         }
       }).fail(function() {
         swal("MAAF !","Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
         $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
       });
     });
</script>

