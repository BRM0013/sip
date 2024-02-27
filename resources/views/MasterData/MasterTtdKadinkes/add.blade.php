<?php
  $id_master_ttd = '';
  $nama  = '';
  $nip  = '';
  $jabatan  = '';
  $tanggal_menjabat  = '';
  $tanggal_awal  = '';
  $tanggal_akhir  = '';

  if (isset($data)) {
    $id_master_ttd = $data->id_master_ttd;
    $nama = $data->nama;
    $nip = $data->nip;
    $jabatan = $data->jabatan;
    $tanggal_menjabat = $data->tanggal_menjabat;
    $tanggal_awal = $data->tanggal_awal;
    $tanggal_akhir = $data->tanggal_akhir;    
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
                            <form action="{{ route('save_ttdkadinkes') }}" method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_master_ttd" value="{{ $id_master_ttd }}">
                            <div class="row">
                              <div class="col-md-12">                                
                                
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Kadinkes</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama" value="{{ $nama }}" class="form-control" placeholder="Tulis nama baru...">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nip Kadinkes</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nip" value="{{ $nip }}" class="form-control" placeholder="Tulis alamat baru...">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Jabatan</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="jabatan" value="{{ $jabatan }}" class="form-control" placeholder="Tulis kelas baru...">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Tanggal Menjabat</label>
                                  <div class="col-sm-12">
                                    <input type="date" required name="tanggal_menjabat" value="{{ $tanggal_menjabat }}" class="form-control" placeholder="Tulis kelas baru...">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Tanggal Awal Menjabat</label>
                                  <div class="col-sm-12">
                                    <input type="date" required name="tanggal_awal" value="{{ $tanggal_awal }}" class="form-control" placeholder="Tulis kelas baru...">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Tanggal Akhir Menjabat</label>
                                  <div class="col-sm-12">
                                    <input type="date" required name="tanggal_akhir" value="{{ $tanggal_akhir }}" class="form-control" placeholder="Tulis kelas baru...">
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
            'width'     : '50%'
        });
        $('#detail-dialog').modal('show');
    })();
    $('#detail-dialog').on('hidden.bs.modal', function () {
        $('.modal-dialog').html('');
    })
</script>