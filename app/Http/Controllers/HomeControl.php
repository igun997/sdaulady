<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Admin,Banksoal,Guru,Jawaban,JawabanItem,Kela,Matpel,Rombel,Siswa,Ujian,UjianItem};
class HomeControl extends Controller
{
  public function login()
  {
    if (session()->get("url") != null) {
      redirect(session()->get("url"));
    }
    return view("login");
  }
  public function login_action(Request $req)
  {
    $data = $req->all();
    unset($data["_token"]);
    $guru = Guru::where($data);
    if ($guru->count() > 0) {
      $sess = [];
      $sess["level"] = "guru";
      $sess["id"] = $data["nip"];
      $sess["url"] = route("guru.home");
      session($sess);
      return redirect($sess["url"]);
    }else {
      $data["username"] = $data["nip"];
      unset($data["nip"]);
      $admin = Admin::where($data);
      if ($admin->count() > 0) {
        $sess = [];
        $sess["level"] = "admin";
        $sess["id"] = $data["username"];
        $sess["url"] = route("admin.home");
        session($sess);
        return redirect($sess["url"]);
      }else {
        return back()->withErrors(["msg"=>"Username dan Password Tidak Ditemukan"]);
      }
    }
  }
}
