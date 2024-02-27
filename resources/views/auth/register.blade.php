@extends('layouts.app')

@section('content')
<style type="text/css">
    
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top: 120px;">
                <div class="card-header" style="background-color: #2fa5e9; "><span style="color: white; font-size: 18px;">{{ __('Registrasi') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap') }}<small style="color: red;">* (Tanpa Gelar)</small></label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Ulangi Password') }}<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Pilih Jenis Izin Praktik<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <select required class="form-control select2" name="jenis_surat">
                                    <option value="">-= Pilih Jenis Praktik =-</option>
                                    <?php foreach ($list_jenis_surat as $row): ?>
                                        <?php if ($row->id_jenis_surat != '28'): ?>
                                            <option value="{{ $row->id_jenis_surat }}">{{ $row->nama_surat }}</option>
                                        <?php else: ?>
                                            
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('No. WA') }}<span style="color: red;">* (ex: 6289123421242)</span></label>

                            <div class="col-md-6">
                                <input id="no_wa" type="number" class="form-control @error('no_wa') is-invalid @enderror" name="no_wa" value="{{ old('no_wa') }}" required autocomplete="no_wa" autofocus>

                                <p class='messageError errorWA' style="color:red;"></p>
                                <input type='hidden' name='statusWA' value='Exist' id='statusWA' class='form-control'>
                                    
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('No. KTP') }}<span style="color: red;"> *</span></label>

                            <div class="col-md-6">
                                <input id="no_ktp" type="number" maxlength="16" class="form-control @error('no_ktp') is-invalid @enderror" name="no_ktp" value="{{ old('no_ktp') }}" required autocomplete="no_ktp" autofocus>

                                <p class='messageError errorKTP' style="color:red;"></p>
                                <input type='hidden' name='statusKTP' value='Exist' id='statusKTP' class='form-control'>

                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrasi') }}
                                </button>
                                <a href="{{ route('login') }}" style="color: white;" class="btn btn-danger">
                                    {{ __('Kembali') }}
                                </a>
                            </div>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('#no_wa').on('keyup', function() {
        var no_wa = $('#no_wa').val();
        if (no_wa.length >= 2 && no_wa[0]+''+no_wa[1] != '62') {
            $('#no_wa').val('');
            swal('Whoops','Angka diawal harus menggunakan 62');            
        } else {
            $.post("{!! route('getWA') !!}", {no_wa:no_wa}).done(function(data){
              if (data.status == 'success') {
                $('.errorWA').html('No WA Telah Terdaftar');
                $('#statusWA').val('Exist');
                disabledBtn();
              }else{
                $('.errorWA').html('');
                $('#statusWA').val('Ready');
                disabledBtn();
              }
            });
        }
    })

    $('#no_ktp').on('keyup', function() {
        var no_ktp = $('#no_ktp').val();
        if (no_ktp.length == 16) {
            $.post("{!! route('getKTP') !!}", {no_ktp:no_ktp}).done(function(data){
              if (data.status == 'success') {
                $('.errorKTP').html('NIK Telah Terdaftar');
                $('#statusKTP').val('Exist');
                disabledBtn();
              }else{
                $('.errorKTP').html('');
                $('#statusKTP').val('Ready');
                disabledBtn();
              }
            });
          }else{
            // $('#no_ktp').val('');
            $('.errorKTP').html('NIK Harus 16 Digit');
            $('#statusKTP').val('Exist');
            disabledBtn();
          }        
    })

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function disabledBtn() {
      var stWA = $('#statusWA').val();        
      var stKTP = $('#statusKTP').val();
      if (stKTP == 'Ready' && stWA == 'Ready') {
        $('.btn-primary').removeAttr('disabled');
      }else{
        $('.btn-primary').attr('disabled', true);
      }
    }
</script>
@endsection        