<?php
  $id_desa        = '';
  $id_kecamatan   = '';
  $nama_kecamatan = '';
  $id_provinsi    = '';
  $id_kabupaten   = '';
  $nama_kabupaten = '';

  if (isset($desa)) {
    $id_desa   = $desa->id_desa;
    $id_kecamatan   = $desa->id_kecamatan;
    $nama_kecamatan = $desa->nama_kecamatan;
    $kabupaten_id   = App\Models\Kecamatan::where('id_kecamatan',$desa->id_kecamatan)->first();
    $provinsi_id    = App\Models\Kabupaten::where('id_kabupaten',$kabupaten_id->id_kabupaten)->first();
    $id_provinsi    = $provinsi_id->id_provinsi;
    $id_kabupaten   = $kabupaten_id->id_kabupaten;
    $id_kecamatan   = $kabupaten_id->id_kecamatan;
    $nama_kabupaten = isset($desa->kabupaten) ? $provinsi_id->nama_kabupaten : '';
    $nama_kecamatan = isset($desa->kecamatan) ? $kabupaten_id->nama_kecamatan : '';
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
                            <form action="{{ route('save_desa') }}" method="post" enctype="multipart/form-data">
                             @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <input type="hidden" required name="id_desa" value="{{$id_desa}}" class="form-control" placeholder="Id desa baru..." style="text-transform:uppercase">
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
                                    {{-- <input type="text" required name="nama_desa" value="{{ $nama_desa }}" class="form-control" placeholder="Tulis nama kecamatan baru..." style="text-transform:uppercase"> --}}
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
                                  <label class="col-sm-12 control-label">Kecamatan</label>
                                  <div class="col-sm-12">
                                      <select id="kecamatan" name="kecamatan_id" class="form-control select2">
                                        <option value="" disabled selected>::. Pilih Kecamatan .::</option>
                                        <option value="{{ $id_kabupaten }}" selected="selected">{{ $nama_kabupaten }}</option>
                                      </select>
                                    {{-- <input type="text" required name="nama_kecamatan" value="{{ $nama_kecamatan }}" class="form-control" placeholder="Tulis nama kecamatan baru..." style="text-transform:uppercase"> --}}
                                  </div>
                                </div>
                                <div class="clearfix" style="margin-bottom: 10px;"></div>
                                <div class="form-group">
                                  <label class="col-sm-12 control-label">Nama Desa</label>
                                  <div class="col-sm-12">
                                    <input type="text" required name="nama_desa" value="{{ $nama_kecamatan }}" class="form-control" placeholder="Tulis nama desa baru..." style="text-transform:uppercase">
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

    $('#kabupaten').change(function(){
      var id = $('#kabupaten').val();
      $.post("{{route('get_kecamatan')}}",{id:id},function(data){
        var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
        if(data.status=='success'){
          if(data.data.length>0){
            $.each(data.data,function(v,k){
              kecamatan+='<option value="'+k.id_kecamatan+'">'+k.nama_kecamatan+'</option>';
            });
          }
        }
        $('#kecamatan').html(kecamatan);
      });
    });
</script>
