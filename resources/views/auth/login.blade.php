@extends('layouts.app')
<link rel="stylesheet" href="{{ url('/') }}/build/sweetalert/sweetalert.css">
@section('content')
<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-8">
<!-- <div style="background:#fff;padding:10px">
<b style="color:red">Pemberitahuan : </b><br>
Mohon maaf, Sehubungan dengan adanya cuti bersama Hari Raya Idul Fitri 1444 H sejak tanggal <b style="color:black">19 April 2023 s.d 25 April 2023.</b> Maka Pelayanan Surat Izin Praktik Dinas Kesehatan Kabupaten Sidoarjo akan dilayani  kembali pada <b style="color:black">26 April 2023</b></div> -->
            <div class="card" style="margin-top: 60px;">
                <div class="card-header" style="background-color: #2fa5e9; "><span style="color: white; font-size: 18px;">{{ __('Login') }}</span></div>
	
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                <br>

                                <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Lupa Password ?') }}
                                    </a>
                                @endif -->

                                @if (Route::has('password.request'))
                                    <!-- <a href="javascript:void(0)" onclick="reg()" class="btn btn-primary cari">{{ __('Registrasi') }}</a> -->
                                    <a class="btn btn-link" href="{{ route('reset_password') }}">
                                        {{ __('Lupa Password ?') }}
                                    </a>
                                @endif
                                
                                </br>
                                @if (Route::has('register'))
                                    <!-- <a href="javascript:void(0)" onclick="reg()" class="btn btn-primary cari">{{ __('Registrasi') }}</a> -->
                                    <a class="btn btn-primary btn-register" id="btn-register" href="{{ route('register') }}" style="color: white;">{{ __('Registrasi') }}</a>
                                @endif
                            </div>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ url('/') }}/build/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">
   function reg(){
        swal("Maaf!", "Maaf Fiture Register sedang dalam perbaikan", "error");         
   };    
</script>
