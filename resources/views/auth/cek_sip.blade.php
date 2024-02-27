@extends('layouts.app')

@section('content')
<style type="text/css">

</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="margin-top: 120px;">
                <div class="card-header" style="background-color: #2fa5e9; "><span style="color: white; font-size: 14px;">{{ __('Pengecekkan Surat Izin Praktik') }}</div>

                <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select required class="form-control jenis_input" name="jenis_input">
                                    <option value="" style="text-align:center;">.::Pilih Jenis Pencarian::.</option>
                                    <option value="nama_pemohon">Nama Pemohon</option>
                                    <option value="nama_praktik">Nama Tempat Praktik</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input id="request_input" type="text" class="form-control request_input" name="request_input" value="{{ old('request_input') }}" required autocomplete="request_input" autofocus style="text-transform:uppercase">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="javascript:void(0)" onclick="cari()" class="btn btn-primary cari btn-sm">Cari</a>
                            </div>
                        </div>

                        <hr style="border: 1px solid #A9A9A9;">
                        <div class="col-lg-12 col-md-12 main-layer" style="overflow: auto">
                          <table class="table table-bordered" border="1">
                            <thead>
                              <th>No</th>
                              <th>Nama Lengkap, Gelar</th>
                              <th>Profesi</th>
                              <th>Status SIP</th>
                              <th>Nama Tempat Praktik</th>
                              <th>Alamat Tempat Praktik</th>
                            </thead>
                            <tbody>

                            </tbody>
                          </table>
                        </div>
                        <div class="col-lg-12 col-md-12 loading" style="display:none">
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
function cari(){
    $('.loading').show();
    stop = 0;

    var jenis_input = $('.jenis_input').val();
    var request_input = $('.request_input').val();

    id_surat = [];

    if(jenis_input=='' || request_input==''){
      swal('Whooops','Lengkapi kolom Pencarian','error');
    }else{
        var data = {
          _token:"{{ csrf_token() }}",
          jenis_input:jenis_input,
          request_input:request_input,
        };
        $.post("{{route('data-pengecekan-sip')}}",data,function(data){

          $('tbody').html('');
          if(data.surat.length!=0){
            $.each(data.surat,function(k,v){
              id_surat.push(v.id_surat);
            });
            baris(0);
          }else{
            swal('Maaf','Tidak Ada Data Atas Pencarian Tersebut','error');
            $('.loading').hide();
          }
        });
    }
  }

  function baris(id){
    var persen = 0;
    if(id<id_surat.length){
      if(stop==0){
        $.post("{{route('baris-pengecekan-sip')}}",{id:id_surat[id],index:id,_token:"{{ csrf_token() }}"},function(data){
          $('tbody').append(data);
          baris((id+1));
        }).fail(function(){
          baris(id);
        });
      }else{
        $('.loading').hide();
        $('.cari').show();
      }
    }else{
      $('.loading').hide();
    }
  }
</script>
