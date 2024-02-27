<?php
  $id_fasyankes   = '';
  $nama      = '';
  $alamat      = '';
  $kelas      = '';

  if (isset($fasyankes)) {
    $id_fasyankes = $fasyankes->id_fasyankes;
    $nama    = $fasyankes->nama;
    $alamat    = $fasyankes->alamat;
    $kelas    = $fasyankes->kelas;
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
                            <form action="{{ route('save_fasyankes') }}" method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_fasyankes" value="{{ $id_fasyankes }}">
                            <div class="row">
                              <div class="col-md-12">                                
                                
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Fasyankes</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama" value="{{ $nama }}" class="form-control" placeholder="Tulis nama baru..." style="text-transform:uppercase">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Alamat Fasyankes</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="alamat" value="{{ $alamat }}" class="form-control" placeholder="Tulis alamat baru..." style="text-transform:uppercase">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Kelas</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="kelas" value="{{ $kelas }}" class="form-control" placeholder="Tulis kelas baru..." style="text-transform:uppercase">
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