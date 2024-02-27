<?php 
  $id_jenis_persyaratan   = '';
  $nama_variable          = '';
  $nama_jenis_persyaratan = '';
  $jenis_input            = '';
  $keterangan_persyaratan = '';

  if (isset($jpersyaratan)) {
      $id_jenis_persyaratan   = $jpersyaratan->id_jenis_persyaratan;
      $nama_variable          = $jpersyaratan->nama_variable;
      $nama_jenis_persyaratan = $jpersyaratan->nama_jenis_persyaratan;
      $jenis_input            = $jpersyaratan->jenis_input;
      $keterangan_persyaratan = $jpersyaratan->keterangan_persyaratan;
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
                            <form action="{{ route('save_jenis_persyaratan') }}" method="post" enctype="multipart/form-data">
                             @csrf
                             <input type="hidden" name="id_jenis_persyaratan" value="{{ $id_jenis_persyaratan }}">
                            <div class="row">
                              <div class="col-md-12">
                                
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Janis Persyaratan</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama_persyaratan" value="{{ $nama_jenis_persyaratan }}" class="form-control" placeholder="Tulis jenis persyaratan baru..." style="text-transform:uppercase">
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Janis Input Persyaratan</label>
                                  <div class="col-sm-12">
                                    <select class="form-control" name="jenis_input">
                                      <option <?php if ($jenis_input == 'image/*,.pdf'){echo "selected='selected'";}?> value="*">File Gambar / PDF</option>
                                      <option <?php if ($jenis_input == 'image/*'){echo "selected='selected'";}?> value="image/*">File Gambar</option>
                                      <option <?php if ($jenis_input == '.pdf'){echo "selected='selected'";}?> value=".pdf">File PDF</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>

                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Keterangan Persyaratan</label>
                                  <div class="col-sm-12">
                                    <textarea style="text-transform:uppercase" class="form-control" required name="keterangan_persyaratan" class="form-control" placeholder="Tulis Keterangan Persyaratan...">{{ $keterangan_persyaratan }}</textarea>
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
            'width'     : '50%'
        });
        $('#detail-dialog').modal('show');
    })();
    $('#detail-dialog').on('hidden.bs.modal', function () {
        $('.modal-dialog').html('');
    })
</script>