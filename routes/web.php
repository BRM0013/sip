<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!


|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE';
});

Route::get('/sendmail', 'SuratController@mailtest');
Route::get('/', 'Auth\LoginController@check_auth');
Route::get('/verifikasi/{id}', 'Auth\VerificationController@verifikasi');
Route::get('/manual_guide', 'ManualGuideController@index')->name('manual_guide');
Route::post('/getKTP',       'ManualGuideController@getKTP')->name('getKTP');
Route::post('/getWA',       'ManualGuideController@getWA')->name('getWA');

//reset password
Route::get('/reset_password', 'ManualGuideController@reset_password')->name('reset_password');
Route::post('/cek_resetwa', 'ManualGuideController@cek_resetwa')->name('cek_resetwa');
Route::post('/store_resetpassword', 'ManualGuideController@store_resetpassword')->name('store_resetpassword');
Route::get('/verifikasi_reset/{id}', 'ManualGuideController@verifikasi_reset');
Route::post('/store_reset_password', 'ManualGuideController@store_reset_password')->name('store_reset_password');

//route baru
Route::get('/cek_sip', 'ManualGuideController@cek_sip')->name('cek_sip');
Route::post('/data_sip','ManualGuideController@data')->name('data-pengecekan-sip');
Route::post('/baris_sip','ManualGuideController@baris')->name('baris-pengecekan-sip');

Auth::routes();
Route::group(['prefix' => 'getController'], function(){
	Route::post('getKabupaten','GetController@getKabupaten')->name('get_kabupaten');
	Route::post('getKecamatan','GetController@getKecamatan')->name('get_kecamatan');
	Route::post('getDesa','GetController@getDesa')->name('get_desa');
  Route::post('searchKabupaten','GetController@getSearchKabupaten')->name('search_kabupaten');
  Route::post('getBerkas','GetController@getBerkasPengajuan')->name('get_file_berkas');
  Route::post('delPreview','SuratController@deleteBerkas')->name('del_file_berkas');
});

Route::group(['middleware'=>'admin'],function(){
	Route::group(['prefix'=>'home'],function(){
		//Master route
    Route::group(['prefix' => 'master' ], function(){

      Route::group(['prefix' => 'data_op' ], function(){
        Route::get('/', 'MasterData\DataOPController@index')->name('data_op');
        Route::post('/datagrid', 'MasterData\DataOPController@datagrid')->name('datagrid_data_op');
        Route::get('/add', 'MasterData\DataOPController@create')->name('add_data_op');
        Route::get('/edit/{id}', 'MasterData\DataOPController@create')->name('edit_data_op');

        Route::post('/save', 'MasterData\DataOPController@store')->name('save_data_op');
        Route::post('/delete', 'MasterData\DataOPController@delete')->name('delete_data_op');        
        Route::get('/{setting}', 'MasterData\DataOPController@update_setting')->name('setting_data_op');
      });

      Route::group(['prefix' => 'data_rs' ], function(){
        Route::get('/', 'MasterData\DataRSController@index')->name('data_rs');
        Route::post('/datagrid', 'MasterData\DataRSController@datagrid')->name('datagrid_data_rs');
        Route::get('/add', 'MasterData\DataRSController@create')->name('add_data_rs');
        Route::get('/edit/{id}', 'MasterData\DataRSController@create')->name('edit_data_rs');

        Route::post('/save', 'MasterData\DataRSController@store')->name('save_data_rs');
        Route::post('/delete', 'MasterData\DataRSController@delete')->name('delete_data_rs');        
        Route::get('/{setting}', 'MasterData\DataRSController@update_setting')->name('setting_data_rs');
      });

      Route::group(['prefix' => 'jabatan' ], function(){
        Route::get('/', 'MasterData\JabatanController@index')->name('jabatan');
        Route::post('/datagrid', 'MasterData\JabatanController@datagrid')->name('datagrid_jabatan');
        Route::post('/add', 'MasterData\JabatanController@create')->name('add_jabatan');
        Route::post('/save', 'MasterData\JabatanController@store')->name('save_jabatan');
        Route::post('/delete', 'MasterData\JabatanController@delete')->name('delete_jabatan');
      });

      Route::group(['prefix' => 'fasyankes' ], function(){
        Route::get('/', 'MasterData\FasyankesController@index')->name('fasyankes');
        Route::post('/datagrid', 'MasterData\FasyankesController@datagrid')->name('datagrid_fasyankes');
        Route::post('/add', 'MasterData\FasyankesController@create')->name('add_fasyankes');
        Route::post('/save', 'MasterData\FasyankesController@store')->name('save_fasyankes');
        Route::post('/delete', 'MasterData\FasyankesController@delete')->name('delete_fasyankes');
      });

      Route::group(['prefix' => 'jenis_praktik' ], function(){
        Route::get('/', 'MasterData\JenisPraktikController@index')->name('jenis_praktik');
        Route::post('/datagrid', 'MasterData\JenisPraktikController@datagrid')->name('datagrid_jenis_praktik');

        Route::post('/add', 'MasterData\JenisPraktikController@create')->name('add_jenis_praktik');
        Route::post('/save', 'MasterData\JenisPraktikController@store')->name('save_jenis_praktik');

        Route::post('/delete', 'MasterData\JenisPraktikController@delete')->name('delete_jenis_praktik');
      });

      Route::group(['prefix' => 'level_pengguna' ], function(){
        Route::get('/', 'MasterData\LevelUsersController@index')->name('level_pengguna');
        Route::post('/datagrid', 'MasterData\LevelUsersController@datagrid')->name('datagrid_level_pengguna');
      });

			Route::group(['prefix' => 'master_provinsi' ], function(){
				Route::get('/', 'MasterData\MasterProvinsiController@index')->name('master_provinsi');
				Route::post('/datagrid', 'MasterData\MasterProvinsiController@datagrid')->name('datagrid_master_provinsi');
				Route::post('/add', 'MasterData\MasterProvinsiController@create')->name('add_provinsi');
				Route::post('/save', 'MasterData\MasterProvinsiController@store')->name('save_provinsi');
        Route::post('/delete', 'MasterData\MasterProvinsiController@delete')->name('delete_provinsi');        

			});

			Route::group(['prefix' => 'master_kabupaten' ], function(){
				Route::get('/', 'MasterData\MasterKabupatenController@index')->name('master_kabupaten');
				Route::post('/datagrid', 'MasterData\MasterKabupatenController@datagrid')->name('datagrid_master_kabupaten');
				Route::post('/add', 'MasterData\MasterKabupatenController@create')->name('add_kabupaten');
				Route::post('/save', 'MasterData\MasterKabupatenController@store')->name('save_kabupaten');
        Route::post('/delete', 'MasterData\MasterKabupatenController@delete')->name('delete_kabupaten');        
			});

			Route::group(['prefix' => 'master_kecamatan' ], function(){
				Route::get('/', 'MasterData\MasterKecamatanController@index')->name('master_kecamatan');
				Route::post('/datagrid', 'MasterData\MasterKecamatanController@datagrid')->name('datagrid_master_kecamatan');
				Route::post('/add', 'MasterData\MasterKecamatanController@create')->name('add_kecamatan');
				Route::post('/save', 'MasterData\MasterKecamatanController@store')->name('save_kecamatan');
			});

			Route::group(['prefix' => 'master_desa' ], function(){
				Route::get('/', 'MasterData\MasterDesaController@index')->name('master_desa');
				Route::post('/datagrid', 'MasterData\MasterDesaController@datagrid')->name('datagrid_master_desa');
				Route::post('/add', 'MasterData\MasterDesaController@create')->name('add_desa');
				Route::post('/save', 'MasterData\MasterDesaController@store')->name('save_desa');
			});

      Route::group(['prefix' => 'pendidikan_terakhir' ], function(){
        Route::get('/', 'MasterData\PendidikanTerakhirController@index')->name('pendidikan_terakhir');
        Route::post('/datagrid', 'MasterData\PendidikanTerakhirController@datagrid')->name('datagrid_pendidikan_terakhir');

        Route::post('/add', 'MasterData\PendidikanTerakhirController@create')->name('add_pendidikan_terakhir');
        Route::post('/save', 'MasterData\PendidikanTerakhirController@store')->name('save_pendidikan_terakhir');

        Route::post('/delete', 'MasterData\PendidikanTerakhirController@delete')->name('delete_pendidikan_terakhir');
      });

      Route::group(['prefix' => 'jenis_surat' ], function(){
        Route::get('/', 'MasterData\JenisSuratController@index')->name('jenis_surat');
        Route::post('/datagrid', 'MasterData\JenisSuratController@datagrid')->name('datagrid_jenis_surat');

        Route::get('/add', 'MasterData\JenisSuratController@create')->name('add_jenis_surat');
        Route::get('/edit/{id}', 'MasterData\JenisSuratController@create')->name('edit_jenis_surat');
        Route::post('/save', 'MasterData\JenisSuratController@store')->name('save_jenis_surat');

        Route::post('/delete', 'MasterData\JenisSuratController@delete')->name('delete_jenis_surat');
      });

      Route::group(['prefix' => 'jenis_persyaratan' ], function(){
        Route::get('/', 'MasterData\JenisPersyaratanController@index')->name('jenis_persyaratan');
        Route::post('/datagrid', 'MasterData\JenisPersyaratanController@datagrid')->name('datagrid_jenis_persyaratan');

        Route::post('/add', 'MasterData\JenisPersyaratanController@create')->name('add_jenis_persyaratan');
        Route::post('/save', 'MasterData\JenisPersyaratanController@store')->name('save_jenis_persyaratan');

        Route::post('/delete', 'MasterData\JenisPersyaratanController@delete')->name('delete_jenis_persyaratan');
      });

      Route::group(['prefix' => 'jenis_sarana' ], function(){
        Route::get('/', 'MasterData\JenisSaranaController@index')->name('jenis_sarana');
        Route::post('/datagrid', 'MasterData\JenisSaranaController@datagrid')->name('datagrid_jenis_sarana');

        Route::post('/add', 'MasterData\JenisSaranaController@create')->name('add_jenis_sarana');
        Route::post('/save', 'MasterData\JenisSaranaController@store')->name('save_jenis_sarana');

        Route::post('/delete', 'MasterData\JenisSaranaController@delete')->name('delete_jenis_sarana');
      });

      Route::group(['prefix' => 'chatbot' ], function(){
        Route::get('/', 'MasterData\ChatBotController@index')->name('chatbot');
        Route::post('/loadData', 'MasterData\ChatBotController@loadData')->name('loadDataChatBot');
        Route::post('/add', 'MasterData\ChatBotController@create')->name('addChatBot');
        Route::post('/save', 'MasterData\ChatBotController@store')->name('simpanChatBot');
        Route::post('/delete', 'MasterData\ChatBotController@delete')->name('deleteChatBot');
      });

      Route::group(['prefix' => 'ttdkadinkes' ], function(){
        Route::get('/', 'MasterData\MasterTtdKadinkesController@index')->name('ttdkadinkes');
        Route::post('/datagrid', 'MasterData\MasterTtdKadinkesController@datagrid')->name('datagrid_ttdkadinkes');
        Route::post('/add', 'MasterData\MasterTtdKadinkesController@create')->name('add_ttdkadinkes');
        Route::post('/save', 'MasterData\MasterTtdKadinkesController@store')->name('save_ttdkadinkes');
        Route::post('/delete', 'MasterData\MasterTtdKadinkesController@delete')->name('delete_ttdkadinkes');
      });

    });

		Route::group(['prefix'=>'users'],function(){
			Route::get('/', 'UsersController@index')->name('users');
			Route::post('/datagrid', 'UsersController@datagrid')->name('datagrid_users');

			Route::get('/add', 'UsersController@create')->name('add_users');
      Route::get('/sendmailprofil', 'UsersController@sendmailprofil')->name('sendmailprofil');

		});
	});

  Route::group(['prefix'=>'laporan'],function(){
    Route::get('/','LaporanController@index')->name('laporan-admin');
    Route::post('/data','LaporanController@data')->name('data-laporan-admin');
    Route::post('/baris','LaporanController@baris')->name('baris-laporan-admin');
  });
});

Route::group(['middleware' => 'semua'],function(){
  Route::group(['prefix' => 'home' ], function(){
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/grafik', 'HomeController@grafik')->name('grafik');

    Route::group(['prefix' => 'users' ], function(){
      Route::get('/edit/{id}', 'UsersController@create')->name('edit_users');
      Route::get('/{setting}', 'UsersController@create')->name('setting_users');

      Route::post('/save', 'UsersController@store')->name('save_users');
      Route::post('/delete', 'UsersController@delete')->name('delete_users');
      Route::post('/kirim_verifikasi_ulang', 'UsersController@kirim_verifikasi_ulang')->name('kirim_verifikasi_ulang');
    });

    Route::get('/form_validasi/{id}', 'SendMailController@form_validasi')->name('form_validasi');
    Route::post('save_uploadberkas', 'SendMailController@save_uploadberkas')->name('save_uploadberkas');
    Route::get('sendWA', 'SendMailController@sendWA')->name('sendWA');

    Route::group(['prefix' => 'surat_rs' ], function(){
      Route::get('/surat_rs', 'SuratController@index')->name('surat_rs');
      Route::post('/datagrid', 'SuratController@datagrid_rs')->name('datagrid_surat_rs');
    });

    Route::group(['prefix' => 'surat' ], function(){
      Route::get('/', 'SuratController@index')->name('surat');
      Route::get('/list/{id_jenis_surat}', 'SuratController@index');
      Route::get('/list/{id_jenis_surat}/document/{id_surat}', 'DocumentPencabutanController@mainPencabutan');
      Route::post('/datagrid', 'SuratController@datagrid')->name('datagrid_surat');

      Route::get('/add', 'SuratController@create')->name('add_surat');
      Route::get('/edit/{id_surat}', 'SuratController@create')->name('edit_surat'); //edit pakai ini
      Route::get('/edit/{id_surat}/{jenis}', 'SuratController@create')->name('perpanjangan_surat'); //ini
      Route::post('/save', 'SuratController@store')->name('save_surat');
      Route::post('/savePreview', 'SuratController@storePreview')->name('save_surat_preview');
      Route::post('/save/{jenis}', 'SuratController@store')->name('perpanjangan_surat');

      Route::post('/pencabutan', 'SuratController@revocation')->name('pencabutan_surat');
      Route::post('/upload_detail_file_pencabutan', 'SuratController@upload_detail_file_pencabutan')->name('upload_detail_file_pencabutan');
      Route::post('/save_pencabutan_ebuddy', 'SuratController@save_pencabutan_ebuddy')->name('save_pencabutan_ebuddy');

      Route::post('/detail', 'SuratController@detail')->name('detail_surat');
      Route::post('/detail_pencabutan', 'SuratController@detail_pencabutan')->name('detail_pencabutan_surat');

      Route::post('/delete', 'SuratController@delete')->name('delete_surat');
      Route::post('/save_nomor', 'SuratController@save_nomor')->name('save_nomor');

      Route::post('/verifikasi_berkas', 'SuratController@verifikasi_berkas')->name('verifikasi_berkas_surat');
      Route::post('/verifikasi_pengajuan', 'SuratController@verifikasi_pengajuan')->name('verifikasi_surat');
      Route::post('/verifikasi_pencabutan', 'SuratController@verifikasi_pencabutan')->name('verifikasi_surat_pencabutan');


      Route::post('/revisi', 'SuratController@form_revisi')->name('revisi_surat');
      Route::post('/save_revisi', 'SuratController@save_revisi')->name('save_revisi_surat');
      Route::post('/setujui_revisi', 'SuratController@setujui_revisi')->name('setujui_revisi_surat');
      Route::post('/perbarui_surat', 'SuratController@perbarui_surat')->name('perbarui_surat');

      Route::get('/surat-all', 'SuratAllController@index')->name('surat-all');
      Route::post('/datagrid-all', 'SuratAllController@datagrid')->name('datagrid_surat-all');

      Route::post('/jadwalkanTanggal','SuratAllController@jadwalkanTanggal')->name('jadwalkanTanggal');
      Route::post('/simpanJadwalkanTanggal','SuratAllController@simpanJadwalkanTanggal')->name('simpanJadwalkanTanggal');

      Route::post('/batalkan','SuratAllController@batalkan')->name('batalkan');
      Route::post('/simpanBatalkan','SuratAllController@simpanBatalkan')->name('simpanBatalkan');

      Route::post('/sudahAmbil','SuratAllController@sudahAmbil')->name('sudahAmbil');

      Route::post('/pilih_pencabutan', 'SuratController@form_pilih_pencabutan')->name('pilih_pencabutan');
    });

    Route::group(['prefix' => 'format_surat' ], function(){
      Route::get('/', 'MasterData\FormatSuratController@index')->name('format_surat');
      Route::post('/datagrid', 'MasterData\FormatSuratController@datagrid')->name('datagrid_format_surat');
      Route::post('/add', 'MasterData\FormatSuratController@create')->name('add_format_surat');
      Route::post('/save', 'MasterData\FormatSuratController@store')->name('save_format_surat');
      Route::post('/delete', 'MasterData\FormatSuratController@delete')->name('delete_format_surat');
    });

    Route::group(['prefix' => 'surat_keterangan' ], function(){
      Route::get('/', 'SuratKeteranganController@index')->name('surat_keterangan');
      Route::post('/datagrid', 'SuratKeteranganController@datagrid')->name('datagrid_surat_keterangan');

      Route::get('/add', 'SuratKeteranganController@create')->name('add_surat_keterangan');
      Route::get('/edit/{id_surat_keterangan}', 'SuratKeteranganController@create')->name('edit_jenis_surat');
      Route::post('/save', 'SuratKeteranganController@store')->name('save_surat_keterangan');
      Route::post('/save_nomor_ebuddy', 'SuratKeteranganController@save_nomor_ebuddy')->name('save_nomor_ebuddy');

      Route::post('/detail', 'SuratKeteranganController@detail')->name('detail_surat_keterangan');
      Route::post('/jadwalkanTanggal','SuratKeteranganController@jadwalkanTanggal')->name('jadwalkanTanggalSuket');
      Route::post('/simpanJadwalkanTanggal','SuratKeteranganController@simpanJadwalkanTanggal')->name('simpanJadwalkanTanggalSuket');
      Route::post('/sudahAmbil','SuratKeteranganController@sudahAmbil')->name('sudahAmbilSuket');
      Route::post('/batalkan','SuratKeteranganController@batalkan')->name('batalkan_keterangan');
      Route::post('/simpanBatalkan','SuratKeteranganController@simpanBatalkanKeterangan')->name('simpanBatalkanKeterangan');
      //silfi
      Route::post('/upload_surat', 'SuratKeteranganController@upload_surat')->name('upload_detail_surat_keterangan');
      //end silfi
      Route::post('/delete', 'SuratKeteranganController@delete')->name('delete_surat_keterangan');

      Route::post('/versifikasi_berkas_ket', 'SuratKeteranganController@verifikasi_berkas_ket')->name('verifikasi_berkas_surat_ket');

      Route::post('/versifikasi_pengajuan', 'SuratKeteranganController@verifikasi_pengajuan')->name('verifikasi_surat_keterangan');

      Route::post('/formSuket', 'SuratKeteranganController@formSuket')->name('formSuket');
      Route::post('/simpanFormSuket', 'SuratKeteranganController@simpanFormSuket')->name('simpanFormSuket');

      Route::get('/document/{id}','DocumentController@main');
    });


    Route::group(['prefix' => 'pemberitahuan' ], function(){
      Route::post('dibaca','PemberitahuanController@set_dibaca')->name('pemberitahuan_dibaca');
    });

    Route::group(['prefix' => 'log_aktifitas' ], function(){
      Route::get('log_aktifitas','LogAktifitasController@index')->name('log_aktifitas');
      Route::post('/datagrid', 'LogAktifitasController@datagrid')->name('datagrid_log_aktifitas');
    });


     Route::group(['prefix' => 'manual_guide_update' ], function(){
      Route::get('/upload_manual_guide','MasterData\ManualGuideController@index')->name('upload_manual_guide');
      Route::post('/update_manual_guide', 'MasterData\ManualGuideController@store')->name('update_manual_guide');
    });


    Route::group(['prefix' => 'surat_diluar' ], function(){
        Route::get('/', 'SuratDiluarController@index')->name('surat_diluar');
        Route::post('/datagrid', 'SuratDiluarController@datagrid')->name('datagrid_surat_diluar');

        Route::post('/add', 'SuratDiluarController@create')->name('add_surat_diluar');
        Route::post('/save', 'SuratDiluarController@store')->name('save_surat_diluar');

        Route::post('/daftar', 'SuratDiluarController@daftar_sip_diluar')->name('daftar_surat_diluar');

        Route::post('/delete', 'SuratDiluarController@delete')->name('delete_surat_diluar');
      });
  });
});
