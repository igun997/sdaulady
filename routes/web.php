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

Route::get('/',function(){
  return redirect(route("login"));
});
Route::get('/api/login',"SiswaControl@login")->name("siswa.api.login");
Route::get('/login',"HomeControl@login")->name("login");
Route::post('/login',"HomeControl@login_action")->name("login_action");
Route::get('/logout',function(){
  session()->flush();
  return redirect(route("login"))->with(["msg"=>"Berhasil Logout"]);
})->name("logout");
Route::get('/api/ujianread',"AdminControl@api_homeread")->name("login.api.read");

Route::group(['middleware' => ['siswa']], function () {
  Route::get('/api/listujian/{nis?}',"SiswaControl@ujian");
  Route::get('/api/listsoal/{ujian?}',"SiswaControl@soal");
  Route::get('/api/download_jawaban/{ujian}/nis/{nis}',"SiswaControl@unduh");
  Route::post('/api/jawaban',"SiswaControl@jawaban");
});
Route::group(['middleware' => ['admin']], function () {
  Route::get('/admin',"AdminControl@index")->name("admin.home");
  Route::get('/admin/export/{id?}',"AdminControl@unduh")->name("admin.export");
  Route::get('/admin/nilai',"AdminControl@nilai")->name("admin.nilai");

  Route::get('/admin/rombel',"AdminControl@rombel")->name("admin.rombel");
  Route::get('/admin/listmatpel/{id}',"AdminControl@listmatpel")->name("admin.listmatpel");
  Route::get('/admin/api/listmatpel/{id?}',"AdminControl@api_readujian")->name("admin.listmatpel.api.ujian");
  Route::get('/admin/api/listujian/{id?}',"AdminControl@api_readujiandetail")->name("admin.listmatpel.api.listujian");
  Route::get('/admin/api/listujiandetail/{id?}',"AdminControl@api_readujiandetaildetail")->name("admin.listmatpel.api.listujiandetail");
  Route::get('/admin/api/ressessay/{id?}',"AdminControl@ressessay")->name("admin.ressessay");
  Route::get('/admin/api/ressjawaban/{id?}',"AdminControl@ressjawaban")->name("admin.ressjawaban");

  Route::get('/admin/rombel',"AdminControl@rombel")->name("admin.rombel");
  Route::get('/admin/rombel/api/read',"AdminControl@api_rombelread")->name("admin.rombel.api.read");
  Route::post('/admin/rombel/api/edit/{id?}',"AdminControl@api_rombeledit")->name("admin.rombel.api.edit");
  Route::post('/admin/rombel/api/add',"AdminControl@api_rombeladd")->name("admin.rombel.api.add");

  Route::get('/admin/kelas',"AdminControl@kelas")->name("admin.kelas");
  Route::get('/admin/kelas/api/show/{id?}',"AdminControl@api_kelashow")->name("admin.kelas.api.show");
  Route::get('/admin/kelas/api/read',"AdminControl@api_kelasread")->name("admin.kelas.api.read");
  Route::post('/admin/kelas/api/edit/{id?}',"AdminControl@api_kelasedit")->name("admin.kelas.api.edit");
  Route::post('/admin/kelas/api/add',"AdminControl@api_kelasadd")->name("admin.kelas.api.add");

  Route::get('/admin/siswa',"AdminControl@siswa")->name("admin.siswa");
  Route::get('/admin/siswa/api/show/{id?}',"AdminControl@api_siswahow")->name("admin.siswa.api.show");
  Route::get('/admin/siswa/api/read',"AdminControl@api_siswaread")->name("admin.siswa.api.read");
  Route::post('/admin/siswa/api/edit/{id?}',"AdminControl@api_siswaedit")->name("admin.siswa.api.edit");
  Route::post('/admin/siswa/api/add',"AdminControl@api_siswaadd")->name("admin.siswa.api.add");
  Route::post('/admin/siswa/api/bulkpassword',"AdminControl@api_siswabulkpassword")->name("admin.siswa.api.bulkpassword");
  Route::get('/admin/siswa/api/template',"AdminControl@api_siswaexport_template")->name("admin.siswa.api.download");

  Route::get('/admin/guru',"AdminControl@guru")->name("admin.guru");
  Route::get('/admin/guru/api/show/{id?}',"AdminControl@api_gurushow")->name("admin.guru.api.show");
  Route::get('/admin/guru/api/read',"AdminControl@api_gururead")->name("admin.guru.api.read");
  Route::post('/admin/guru/api/edit/{id?}',"AdminControl@api_guruedit")->name("admin.guru.api.edit");
  Route::post('/admin/guru/api/add',"AdminControl@api_guruadd")->name("admin.guru.api.add");

  Route::get('/admin/matpel',"AdminControl@matpel")->name("admin.matpel");
  Route::get('/admin/matpel/api/show/{id?}',"AdminControl@api_matpelshow")->name("admin.matpel.api.show");
  Route::get('/admin/matpel/api/read',"AdminControl@api_matpelread")->name("admin.matpel.api.read");
  Route::post('/admin/matpel/api/edit/{id?}',"AdminControl@api_matpeledit")->name("admin.matpel.api.edit");
  Route::post('/admin/matpel/api/add',"AdminControl@api_matpeladd")->name("admin.matpel.api.add");

});
Route::group(['middleware' => ['guru']], function () {
  Route::get('/guru',"GuruControl@index")->name("guru.home");

  Route::get('/guru/ujian/rinci/{id?}',"GuruControl@ujian_rincian")->name("guru.ujian.rinci");
  Route::get('/guru/api/rinci/read/{id?}',"GuruControl@api_ujiannilairead")->name("guru.ujian.haisl.api.read");
  Route::post('/guru/api/rinci/update/{id?}',"GuruControl@api_ujianessay")->name("guru.ujian.hasil.api.update");

  Route::get('/guru/banksoal',"GuruControl@banksoal")->name("guru.banksoal");
  Route::get('/guru/banksoal/api/read',"GuruControl@api_banksoalread")->name("guru.banksoal.api.read");
  Route::post('/guru/banksoal/api/edit/{id?}',"GuruControl@api_banksoaledit")->name("guru.banksoal.api.edit");
  Route::post('/guru/banksoal/api/add',"GuruControl@api_banksoaladd")->name("guru.banksoal.api.add");
  Route::post('/guru/banksoal/api/upload',"GuruControl@upload")->name("guru.banksoal.api.upload");
  Route::get('/guru/banksoal/api/view/{id?}',"GuruControl@api_banksoalview")->name("guru.banksoal.api.view");

  Route::get('/guru/ujian',"GuruControl@ujian")->name("guru.ujian");
  Route::get('/guru/ujian/add',"GuruControl@ujian_add")->name("guru.ujian_add");
  Route::get('/guru/ujian/edit/{id?}',"GuruControl@ujian_edit")->name("guru.ujian_edit");
  Route::get('/guru/ujian/api/read',"GuruControl@api_ujianread")->name("guru.ujian.api.read");
  Route::post('/guru/ujian/api/edit/{id?}',"GuruControl@api_ujianedit")->name("guru.ujian.api.edit");
  Route::post('/guru/ujian/api/add',"GuruControl@api_ujianadd")->name("guru.ujian.api.add");
  Route::post('/guru/ujian/api/upload',"GuruControl@upload")->name("guru.ujian.api.upload");
  Route::get('/guru/ujian/api/view/{id?}',"GuruControl@api_ujianview")->name("guru.ujian.api.view");
  Route::get('/guru/ujian/api/banksoal/{id?}',"GuruControl@api_ujiansoal")->name("guru.ujian.api.banksoal");
  Route::get('/guru/ujian/api/essay',"GuruControl@api_getessay")->name("guru.ujian.api.api_getessay");
});
