<?php
  $id_kecamatan   = '';
  $nama_kecamatan = '';
  $id_provinsi    = '';
  $id_kabupaten   = '';
  $nama_kabupaten = '';

  if (isset($kecamatan)) {
    $id_kecamatan   = $kecamatan->id_kecamatan;
    $nama_kecamatan = $kecamatan->nama_kecamatan;
    $provinsi_id    = App\Models\Kabupaten::where('id_kabupaten',$kecamatan->id_kabupaten)->first();
    $id_provinsi    = $provinsi_id->id_provinsi;
    $id_kabupaten   = $kecamatan->id_kabupaten;
    $nama_kabupaten = isset($kecamatan->id_kabupaten) ? $provinsi_id->nama_kabupaten : '';
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
                            <form action="{{ route('save_kecamatan') }}" method="post" enctype="multipart/form-data">
                             @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <input type="hidden" required name="id_kecamatan" value="{{$id_kecamatan}}" class="form-control" placeholder="Id kecamatan baru..." style="text-transform:uppercase">
                                <div class="clearfix" style="margin-bottom: 10px;"></div>
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Provinsi</label>
                                  <div class="col-sm-12">
                                    <select id="provinsi" class="form-control select2" name="provinsi_id">
                                      <option value="" disabled selected>::. Pilih Provinsi .::</option>
                                      @foreach ($provinsi as $prov)
                                        <?php if ($prov->id_provinsi == $id_provinsi): ?>
                                          <option value="{{ $prov->id_provinsi }}" selected>{{ $prov->nama_provinsi }}</option>
                                        <?php else: ?>
                                          <option value="{{ $prov->id_provinsi }}">{{ $prov->nama_provinsi }}</option>
                                        <?php endif ?>
                                      @endforeach
                                    </select>
                                    {{-- <input type="text" required name="nama_kecamatan" value="{{ $nama_kecamatan }}" class="form-control" placeholder="Tulis nama kecamatan baru..." style="text-transform:uppercase"> --}}
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Kabupaten</label>
                                  <div class="col-sm-12">
                                      <select id="kabupaten" name="kabupaten_id" class="form-control select2">
                                        <option value="" disabled selected>::. Pilih Kabupaten .::</option>
                                        <option value="{{ $id_kabupaten }}" selected="selected">{{ $nama_kabupaten }}</option>
                                      </select>
                                    {{-- <input type="text" required name="nama_kecamatan" value="{{ $nama_kecamatan }}" class="form-control" placeholder="Tulis nama kecamatan baru..." style="text-transform:uppercase"> --}}
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Kecamatan</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama_kecamatan" value="{{ $nama_kecamatan }}" class="form-control" placeholder="Tulis nama kecamatan baru..." style="text-transform:uppercase">
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

    $('#provinsi').change(function(){
      var id = $('#provinsi').val();
      $.post("{{route('get_kabupaten')}}",{id:id},function(data){
        var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              kabupaten+='<option value="'+k.id_kabupaten+'">'+k.nama_kabupaten+'</option>';
            });
          }
        }
        $('#kabupaten').html(kabupaten);
      });
    });
</script>
