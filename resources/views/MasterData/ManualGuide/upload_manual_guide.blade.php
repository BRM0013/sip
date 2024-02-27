@extends('layouts.admin-template')
@section('content')

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Update Manual Guide
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Update Manual Guide</li>
    </ol>
  </section>

  <section class="content">
    <div class="box" style="border-top:none">
      <div class="col-md-1"></div>
      <div class="col-md-10">
        <div class="box box-info" id="btn-add" style="border-top:none">
        <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;"></h4>
          <div class="box-body">
            <div class="col-md-12">
              <form action="{{ route('update_manual_guide') }}" method="post" enctype="multipart/form-data">
               @csrf
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-2">
                          <label>Manual Admin</label>
                          <br>
                          <img id="output_1" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ url('/') }}/img/aid1728243-v4-900px-Create-a-User-Manual-Step-1.jpg">
                          <input name="manual_admin" accept=".pdf" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>
                          
                        <div class="col-md-2">
                          <label>Manual Kasi</label>
                          <br>
                          <img id="output_1" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ url('/') }}/img/aid1728243-v4-900px-Create-a-User-Manual-Step-1.jpg">
                          <input name="manual_kasi" accept=".pdf" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>

                        <div class="col-md-2">
                          <label>Manual Kabid</label>
                          <br>
                          <img id="output_2" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ url('/') }}/img/aid1728243-v4-900px-Create-a-User-Manual-Step-1.jpg">
                          <input name="manual_kabid" accept=".pdf" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>

                        <div class="col-md-2">
                          <label>Manual Kadinkes</label>
                          <br>
                          <img id="output_ttd" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ url('/') }}/img/aid1728243-v4-900px-Create-a-User-Manual-Step-1.jpg">
                          <input name="manual_kadinkes" accept=".pdf" style="width: 130px" type="file" class="form-control input-sm">
                        </div>

                        <div class="col-md-2">
                          <label>Manual Pemohon</label>
                          <br>
                          <img id="manual_pemohon" style="border: 2px solid gray; height: 150px; width: 130px; height: 130px;" src="{{ url('/') }}/img/aid1728243-v4-900px-Create-a-User-Manual-Step-1.jpg">
                          <input name="stempel_dinkes" accept=".pdf" style="width: 130px;" type="file" class="form-control input-sm">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 10px;"></div>
                </div>
              </div>
               <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 15px;">Simpan
                  <span style="margin-left: 5px;" class="fa fa-save"></span>
                </button>
                <a href="{{ route('jenis_surat') }}" class="btn btn-warning btn-cencel pull-right">
                  <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                </a>
              </div>
             </form>
            </div>
          </div>
        </div>
      <div class="col-md-1"></div>
    </div>
   </div>
  </section>
  <div class="clearfix"></div>
</div>
@endsection

@section('js')

@endsection
