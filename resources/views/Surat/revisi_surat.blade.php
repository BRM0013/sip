<?php
  $id_surat               = '';
  $keterangan_revisi      = '';

  if (isset($surat)) {
    $id_surat               = $surat->id_surat;
    $keterangan_revisi      = $surat->keterangan_revisi;
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
                        Revisi Surat Izin Praktik
                      </h4>
                        <div class="box-body">
                          <div class="col-md-12">
                            <form action="
                              <?php if (Auth::getUser()->id_level_user == 1): ?>
                                {{ route('setujui_revisi_surat') }}
                              <?php else: ?>
                                {{ route('save_revisi_surat') }}
                              <?php endif ?>
                              " method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_surat" value="{{ $id_surat }}">
                            <div class="row">
                              <div class="col-md-12">
                                
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Tulis kesalahan : </label>
                                  <div class="col-sm-12">
                                    <textarea style="text-transform:uppercase" class="form-control" <?php if (Auth::getUser()->id_level_user == 1): echo 'disabled="false"'; endif ?> required="required" class="form-control" name="keterangan_revisi">{{ $keterangan_revisi }}</textarea>
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>
                                <?php if (Auth::getUser()->id_level_user == 1): ?>
                                  <label>Sebelum menyetujui revisi ini pastikan data yang anda masukan sudah benar.</label>
                                <?php endif ?>
                              </div> 
                            </div>
                             <div class="box-footer">
                              <?php if (Auth::getUser()->id_level_user == 1): ?>
                                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Revisi Sekarang
                                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                                </button>
                              <?php else: ?>
                                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Ajuikan Revisi
                                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                                </button>
                              <?php endif ?>
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
            'width'     : '30%'
        });
        $('#detail-dialog').modal('show');
    })();
    $('#detail-dialog').on('hidden.bs.modal', function () {
        $('.modal-dialog').html('');
    })
</script>