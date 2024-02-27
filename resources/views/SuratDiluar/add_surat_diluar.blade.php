<?php

  $id_surat_diluar        = '';
  $id_user                = '';
  $tanggal                = '';
  $nama_tempat            = '';
  $alamat_tempat          = '';
  $nomor_str              = '';
  $tanggal_berlaku_str    = '';
  $sip_ke                 = '';
  $status_aktif           = '';

  if (isset($surat_diluar)) {
    $id_surat_diluar        = $surat_diluar->id_surat_diluar;
    $id_user                = $surat_diluar->id_user;
    $tanggal                = $surat_diluar->tanggal;
    $nama_tempat            = $surat_diluar->nama_tempat;
    $alamat_tempat          = $surat_diluar->alamat_tempat;
    $nomor_str              = $surat_diluar->nomor_str;
    $tanggal_berlaku_str    = $surat_diluar->tanggal_berlaku_str;
    $sip_ke                 = $surat_diluar->sip_ke;
    $status_aktif           = $surat_diluar->status_aktif;
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
                        SIP Diluar
                      </h4>
                        <div class="box-body">
                          <div class="col-md-12">
                            <form action="{{ route('save_surat_diluar') }}" method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_surat_diluar" value="{{ $id_surat_diluar }}">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Tempat Praktik</label>
                                  <div class="col-sm-12">
                                    <input type="text"  style="text-transform:uppercase" required name="nama_tempat" value="{{ $nama_tempat }}" class="form-control disablecopypaste" placeholder="Tulis..." onkeypress="return cekAngkaHuruf(event)">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Alamat Tempat Praktik</label>
                                  <div class="col-sm-12">
                                    <input type="text"  style="text-transform:uppercase" required name="alamat_tempat" value="{{ $alamat_tempat }}" class="form-control disablecopypaste" placeholder="Tulis..." onkeypress="return cekAngkaHuruf(event)">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>


                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nomor STR</label>
                                  <div class="col-sm-12">
                                    <input type="text"  style="text-transform:uppercase" required name="nomor_str" value="{{ $nomor_str }}" class="form-control disablecopypaste" placeholder="Tulis..." onkeypress="return cekAngkaHuruf(event)">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Tanggal Berlaku STR</label>
                                  <div class="col-sm-12">
                                    <input type="text"  style="text-transform:uppercase" id="datepicker" required name="tanggal_berlaku_str" value="{{ $tanggal_berlaku_str }}" class="form-control disablecopypaste" placeholder="Tahun - Bulan - Tanggal">
                                    <small style="font-weight: 900;">*Format Inputan : Tahun - Bulan - Tanggal.</small>
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">SIP Ke</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="sip_ke" value="{{ $sip_ke }}" class="form-control disablecopypaste" placeholder="Tulis..." onkeypress="return cekAngka(event)" maxlength="1">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Alamat Tempat Praktik</label>
                                  <div class="col-sm-12">
                                    <select name="status_aktif" class="form-control"  style="text-transform:uppercase">
                                      <option value="">.:Pilih Status:.</option>
                                      <option <?php if ($status_aktif == 'Aktif'){echo 'selected="selected"';} ?> value="Aktif">Aktif</option>
                                      <option <?php if ($status_aktif == 'Kedaluarsa'){echo 'selected="selected"';} ?> value="Kedaluarsa">Kedaluarsa</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                              </div>
                            </div>
                             <div class="box-footer">
                              <button type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Simpan
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
      if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 32 || charCode == 46 || charCode == 64)
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

    //Date picker
      $('#datepicker').datepicker({
        autoclose: true,
        format:'yyyy-mm-dd',
      });

    var onLoad = (function() {
        $('#detail-dialog').find('.modal-dialog').css({
            'width'     : '30%'
        });
        $('#detail-dialog').modal('show');
    })();
    $('#detail-dialog').on('hidden.bs.modal', function () {
        $('.modal-dialog').html('');
    })

      function cekAngka(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode < 49 || charCode > 51)
        return false;
      return true;
    }
</script>
