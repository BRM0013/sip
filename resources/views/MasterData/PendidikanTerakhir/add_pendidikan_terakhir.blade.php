
<?php
  $id_pendidikan_terakhir   = '';
  $pendidikan_terakhir      = '';
  $jenjang                  = '';

  if (isset($jpendidikan_terakhir)) {
    $id_pendidikan_terakhir   = $jpendidikan_terakhir->id_pendidikan_terakhir;
    $pendidikan_terakhir      = $jpendidikan_terakhir->pendidikan_terakhir;
    $jenjang                  = $jpendidikan_terakhir->jenjang;
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
                            <form action="{{ route('save_pendidikan_terakhir') }}" method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_pendidikan_terakhir" value="{{ $id_pendidikan_terakhir }}">
                            <div class="row">
                              <div class="col-md-12">
                                
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Pendidikan Terakhir</label>
                                  <div class="col-sm-12">
                                    <input type="text" name="pendidikan_terakhir" required class="form-control" value="{{ $pendidikan_terakhir }}" placeholder="Tulis pendidikan terakhir baru...">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Jenjang (S-1)</label>
                                  <div class="col-sm-12">
                                    <input type="text" name="jenjang" required class="form-control" value="{{ $jenjang }}" placeholder="Tulis jenjang pendidikan terakhir baru...">
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