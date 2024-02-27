@extends('layouts.app')

@section('content')
<style type="text/css">
    
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top: 120px;">
                <div class="card-header" style="background-color: #2fa5e9; "><span style="color: white; font-size: 18px;">{{ __('Form Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('store_reset_password') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ (!empty($users)) ? $users->id : '' }}">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" readonly autocomplete="new-email" value="{{ (!empty($users)) ? $users->email : '' }}">                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <input id="inputPassword" type="password" name="password" placeholder="Password" class="form-control">
                                <div class='iconEye'>
                                    <a href="javascript:void(0)" onclick="showPassword()" id='eyePass'><i class="far fa-eye-slash"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Ulangi Password') }}<span style="color: red;">*</span></label>

                            <div class="col-md-6">
                                <input id="inputConfirmPass" type="password" name="confirm_pass" onkeyup="confirmPassword()" placeholder="Konfirmasi Password" class="form-control">
                                <div class='iconEye'>
                                    <a href="javascript:void(0)" onclick="showCornPassword()" id='eyeCornPass'><i class="far fa-eye-slash"></i></a>
                                </div>
                                <div class='iconStatus text-green' id='icon_confirmPass'></div>
                                <p class='messageError errorConfirmPass'></p>
                                <input type='hidden' name='statusConfirmPass' value='Failed' id='statusConfirmPass' class='form-control'>
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
    function showPassword() {
      var tag = document.getElementById("inputPassword").getAttribute("type");
      if (tag == 'password') {
        $('#inputPassword').attr('type','text');
        $('#eyePass').html('<i class="fas fa-eye"></i>');
      }else{
        $('#inputPassword').attr('type','password');
        $('#eyePass').html('<i class="far fa-eye-slash"></i>');
      }
    }

    function showCornPassword() {
      var tag = document.getElementById("inputConfirmPass").getAttribute("type");
      if (tag == 'password') {
        $('#inputConfirmPass').attr('type','text');
        $('#eyeCornPass').html('<i class="fas fa-eye"></i>');
      }else{
        $('#inputConfirmPass').attr('type','password');
        $('#eyeCornPass').html('<i class="far fa-eye-slash"></i>');
      }
    }


    function confirmPassword() {
      var password = $('#inputPassword').val();
      var confirmPass = $('#inputConfirmPass').val();
      if (password != '' && confirmPass != '') {
        if (password != confirmPass) {
          $('#inputConfirmPass').attr('class','form-control is-invalid');
          $('#icon_confirmPass').attr('class','iconStatus text-red');
          $('#icon_confirmPass').html('<i class="fas fa-exclamation-triangle"></i>');
          $('.errorConfirmPass').html('Password Tidak Cocok');
          $('#statusConfirmPass').val('Failed');
          disabledBtn();
        }else{
          $('#inputConfirmPass').attr('class','form-control is-valid');
          $('#icon_confirmPass').attr('class','iconStatus text-green');
          $('#icon_confirmPass').html('<i class="fas fa-check-circle"></i>');
          $('.errorConfirmPass').html('Password Cocok');
          $('#statusConfirmPass').val('Ready');
          disabledBtn();
        }
      }else {
        $('#inputConfirmPass').attr('class','form-control');
        $('#icon_confirmPass').attr('class','iconStatus');
        $('#icon_confirmPass').html('');
        $('.errorConfirmPass').html('');
        $('#statusConfirmPass').val('Failed');
        disabledBtn();
      }
    }

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function disabledBtn() {
      var stConfirmPass = $('#statusConfirmPass').val();
      if (stConfirmPass == 'Ready') {
        $('.btn-primary').removeAttr('disabled');
      }else{
        $('.btn-primary').attr('disabled', true);
      }
    }
</script>
@endsection        