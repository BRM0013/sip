<?php
  $id_provinsi  = $id_terakhir->id_provinsi+1;
  $nama_provinsi= '';

  if (isset($provinsi)) {
    $id_provinsi   = $provinsi->id_provinsi;
    $nama_provinsi = $provinsi->nama_provinsi;
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
                            <form action="{{ route('save_provinsi') }}" method="post" enctype="multipart/form-data">
                             @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">ID Provinsi</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="id_provinsi" value="{{$id_provinsi}}" class="form-control" placeholder="Id provinsi baru..." style="text-transform:uppercase">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Provinsi</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama_provinsi" value="{{ $nama_provinsi }}" class="form-control" placeholder="Tulis nama provinsi baru..." style="text-transform:uppercase">
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
