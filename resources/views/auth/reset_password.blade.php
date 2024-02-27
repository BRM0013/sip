@extends('layouts.app')

@section('content')
<style type="text/css">
    
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top: 120px;">
                <div class="card-header" style="background-color: #2fa5e9; "><span style="color: white; font-size: 18px;">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('store_resetpassword') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <p class='messageError errorKTP' style="color:red;"></p>
                                <input type='hidden' name='statusEmail' value='Exist' id='statusEmail' class='form-control'>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('No. WA') }}<span style="color: red;">* (ex: 6289123421242)</span></label>

                            <div class="col-md-6">
                                <input id="no_wa" type="number" class="form-control @error('no_wa') is-invalid @enderror" name="no_wa" value="{{ old('no_wa') }}" required autocomplete="no_wa" autofocus pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==13) return false;" >

                                <p class='messageError errorWA' style="color:red;font-weight: bold;"></p>
                                <input type='hidden' name='statusWA' value='Exist' id='statusWA' class='form-control'>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset') }}
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
        var email = $('#email').val();

        if (no_wa.length >= 2 && no_wa[0]+''+no_wa[1] != '62') {
            $('#no_wa').val('');
            swal('Whoops','Angka diawal harus menggunakan 62');            
        } else {
            $.post("{!! route('cek_resetwa') !!}", {no_wa:no_wa,email:email}).done(function(data){
              if (data.status == 'success') {
                console.log('mas');
                $('.errorWA').html('Data Ditemukan, Klik Tombol Reset dibawah ini');
                $('#statusWA').val('Ready');
                $('#statusEmail').val('Ready');
                disabledBtn();
              }else{
                console.log('ayang');
                $('.errorWA').html('No WA  dan Email Tidak Sesuai,Silahkan Hubungi Admin');
                $('#statusWA').val('Exist');
                $('#statusEmail').val('Exist');
                disabledBtn();
              }
            });
        }
    })

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function disabledBtn() {
      var stWA = $('#statusWA').val();       
      var stUsername = $('#statusEmail').val();

      if (stWA == 'Ready' && stUsername == 'Ready') {
        $('.btn-primary').removeAttr('disabled');
      }else{
        $('.btn-primary').attr('disabled', true);
      }
    }
</script>
@endsection        