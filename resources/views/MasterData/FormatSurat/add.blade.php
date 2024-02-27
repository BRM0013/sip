<?php
  $id_format_surat   = '';
  $nama_surat      = '';
  $keterangan_surat      = '';
  $nama_file_surat      = '';
  $id_jenis_surat     = '';

  if (isset($data_format)) {
    $id_format_surat      = $data_format->id_format_surat;
    $id_jenis_surat      = $data_format->jenis_surat_id;
    $nama_surat          = $data_format->nama;
    $keterangan_surat    = $data_format->keterangan;
    $nama_file_surat     = $data_format->nama_file;    
  }
?>

<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0px;">
                <section>
                  <div class="box" style="border-top:none">
                    <div class="col-md-13">
                      <div class="box box-info" id="btn-add" style="border-top:none">
                      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
                        {{ $title }}
                      </h4>
                        <div class="box-body">
                          <div class="col-md-12">
                            <form action="{{ route('save_format_surat') }}" method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_format_surat" value="{{ $id_format_surat }}">
                            <div class="row">
                              <div class="col-md-12">
                                
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Format Surat</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama" value="{{ $nama_surat }}" class="form-control" placeholder="Tulis Nama Format Surat..." style="text-transform:uppercase" onkeypress="return cekHuruf(event)">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Keterangan</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="keterangan" value="{{ $keterangan_surat }}" class="form-control" placeholder="Keterangan..." style="text-transform:uppercase" onkeypress="return cekHuruf(event)">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Jenis Surat (SIP)</label>
                                  <div class="col-sm-12">
                                    <select class="form-control" name="jenis_surat_id">
                                      <option value="" disabled selected>::. Pilih Jenis Surat .::</option>
                                      @foreach ($jenis_surat as $key)
                                        <option value="{{$key->id_jenis_surat}}" @if($id_jenis_surat == $key->id_jenis_surat) selected @endif>{{$key->nama_surat}}</option>
                                      @endforeach                                     
                                    </select>
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Upload Format Surat <small style="color:red">* Maksimal 2 MB</small></label>
                                  <div class="col-sm-12">
                                    <input type="file" name="nama_file" class="form-control" accept=".pdf" id="nama_file" onchange="checkSize()">
                                    <span id="info-nama_file" style="color:red; display:none;">File yang anda masukan lebih dari 2MB</span>
                                    @if(!empty($nama_file_surat))
                                    <iframe src="{{ url('/')}}/upload/format_surat/{{ $nama_file_surat }}" width="100%" height="550px"></iframe>
                                    @endif
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                              </div>

                            </div>
                             <div class="box-footer">
                              <button type="submit" class="btn btn-primary pull-right" id="button-simpan" style="margin-left: 15px;">Simpan
                                <span style="margin-left: 5px;" class="fa fa-save"></span>
                              </button>
                              <button type="button" class="btn btn-warning btn-cencel pull-right" data-dismiss="modal">
                                <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                              </button>
                            </div>
                           </form> 
                          </div>
                        </div>
                      </div>
                  </div>
                 </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var onLoad = (function() {
        $('#detail-dialog').find('.modal-dialog').css({
            'width'     : '70%'
        });
        $('#detail-dialog').modal('show');
    })();
    $('#detail-dialog').on('hidden.bs.modal', function () {
        $('.modal-dialog').html('');
    }) 

    function cekHuruf(evt) { 
      var charCode = (evt.which) ? evt.which : event.keyCode
      if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 32 || charCode == 46 || charCode == 64 || charCode == 39)
        return true;
      return false;
    }

    function checkSize(){
      // console.log(input_id);
      var input = document.getElementById('nama_file');
      if(input.files && input.files.length == 1){
        if (input.files[0].size > 2097152) {
          $('#info-nama_file').css('display', 'block');
          $('#button-simpan').attr('disabled', 'disabled');          
          return false;
        }
      }
      $('#info-nama_file').css('display', 'none');
      return true;
    }

     $('input[type=file]').on('change',function (e) {
        var extValidation = new Array(".pdf");
        var fileExt = e.target.value;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (extValidation.indexOf(fileExt) < 0) {
            swal('Extensi File Tidak Valid !','Upload file bertipe .pdf, untuk dapat melakukan upload data...','warning')
            $(this).val("")
            return false;
        }
        else return true;
    })
</script>