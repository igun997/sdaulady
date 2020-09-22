<?php

namespace App\Http\Controllers;
//s
use Illuminate\Http\Request;
use App\Models\{Admin,Banksoal,Guru,Jawaban,JawabanItem,Kela,Matpel,Rombel,Siswa,Ujian,UjianItem};
use App\Exports\{UnduhJawaban};
use Excel;
use Igun997\Utility\Debug;
use Igun997\Utility\Excel as ImporterExcel;
class AdminControl extends Controller
{
    public function index()
    {
      return view("admin.home")->with(["title"=>"Administrator"]);
    }
    public function unduh($id)
    {
      $nama = Ujian::where(["id"=>$id])->first()->nama_ujian;
      return Excel::download(new UnduhJawaban($id), str_replace("/","-",str_replace(" ","_",$nama)).'.xlsx');
    }
    public function api_homeread()
    {
      $data = [];
      $data["data"] = [];
      $all = Ujian::orderBy("dibuat","desc")->get();
      foreach ($all as $key => $value) {
        $nama = $value->matpel->nama;
        if (isset($value->matpel->kela->kela->id)) {
          $nama = $nama." (".$value->matpel->kela->kela->nama."_".$value->matpel->kela->nama.")";
        }else {
          $nama = $nama." (".$value->matpel->kela->nama.")";
        }
        $data["data"][] = [($key+1),$nama ,$value->matpel->guru->nama,date("d-m-Y H:i:s",strtotime($value->dibuat))];
      }
      return response()->json($data);
    }
    public function nilai()
    {
      return view("admin.nilai")->with(["title"=>"Data Nilai"]);
    }
    public function api_nilairead($id = null)
    {

    }
    public function rombel()
    {
      return view("admin.rombel")->with(["title"=>"Data Rombongan Belajar"]);
    }
    public function api_rombelread()
    {
      $data = [];
      $data["data"] = [];
      $d = Rombel::get();
      foreach ($d as $key => $value) {
        $data["data"][] =[($key+1),$value->nama,$value->kelas->count(),$value->id];
      }
      return response()->json($data);
    }
    public function api_rombeledit(Request $req,$id)
    {
      $req->validate([
        "nama"=>"required"
      ]);
      $ins = Rombel::where(["id"=>$id])->update($req->all());
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_rombeladd(Request $req)
    {
      $req->validate([
        "nama"=>"required|unique:rombel,nama"
      ]);
      $ins = Rombel::create($req->all());
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }

    public function kelas()
    {
      $kelas = Kela::where("kelas_id","=",null)->get();
      $rombel = Rombel::get();
      return view("admin.kelas")->with(["title"=>"Data Kelas","kelas"=>$kelas,"rombel"=>$rombel]);
    }
    public function api_kelasread()
    {
      $data = [];
      $data["data"] = [];
      $d = Kela::get();
      foreach ($d as $key => $value) {
        $data["data"][] =[($key+1),((isset($value->kela->nama))?$value->kela->nama." - ":"").$value->nama,$value->rombel->nama,$value->siswas->count(),$value->id];
      }
      return response()->json($data);
    }
    public function api_kelashow($id)
    {
      $d = Kela::where(["id"=>$id]);
      if ($d->count() > 0) {
        $ds = $d->first();
        return response()->json(["status"=>1,"data"=>$ds]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_kelasedit(Request $req,$id)
    {
      $req->validate([
        "nama"=>"required",
        "rombel_id"=>"required",
      ]);
      $ins = Kela::where(["id"=>$id])->update($req->all());
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_kelasadd(Request $req)
    {
      $req->validate([
        "nama"=>"required",
        "rombel_id"=>"required",
      ]);
      $ins = Kela::create($req->all());
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }

    public function siswa()
    {
      $kelas = Kela::where("kelas_id","!=",null)->get();
      $rombel = Rombel::get();
      return view("admin.siswa")->with(["title"=>"Data Kelas","kelas"=>$kelas]);
    }
    public function api_siswaread()
    {
      $data = [];
      $data["data"] = [];
      $d = Siswa::get();
      foreach ($d as $key => $value) {
        $data["data"][] =[($key+1),$value->nis,$value->nama,$value->alamat,$value->no_hp,(($value->jk == 0)?"Laki-Laki":"Perempuan"),(($value->foto != null)?url("upload/".$value->foto):null),$value->email,$value->kela->kela->nama." - ".$value->kela->nama,date("d-m-Y",strtotime($value->dibuat)),$value->nis];
      }
      return response()->json($data);
    }
    public function api_siswahow($id)
    {
      $d = Siswa::where(["nis"=>$id]);
      if ($d->count() > 0) {
        $ds = $d->first();
        return response()->json(["status"=>1,"data"=>$ds]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_siswaedit(Request $req,$id)
    {
      $req->validate([
        "nama"=>"required",
        "kelas_id"=>"required",
        "jk"=>"required",
        "foto"=>"mimes:jpeg,png,jpg,gif,svg|max:2048"
      ]);
      $d = $req->all();
      if ($req->has("foto")) {
        $imageName = time().'.'.$req->foto->getClientOriginalExtension();
        $sa = $req->foto->move(public_path('upload'), $imageName);
        $old = Siswa::where(["nis"=>$id])->first();
        @unlink(public_path('upload')."/".$old->foto);
        $d["foto"] = $imageName;
      }else {
        unset($d["foto"]);
      }
      if ($req->has("password")) {
        if ($d["password"] == "") {
          unset($d["password"]);
        }
      }
      $ins = Siswa::where(["nis"=>$id])->update($d);
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_siswaadd(Request $req)
    {
      $req->validate([
        "nis"=>"required",
        "nama"=>"required",
        "password"=>"required",
        "kelas_id"=>"required",
        "jk"=>"required",
        "foto"=>"mimes:jpeg,png,jpg,gif,svg|max:2048"
      ]);
      $d = $req->all();
      if ($req->has("foto")) {
        $imageName = time().'.'.$req->foto->getClientOriginalExtension();
        $sa = $req->foto->move(public_path('upload'), $imageName);
        if ($sa) {
          $d["foto"] = $imageName;
          $ins = Siswa::create($d);
          if ($ins) {
            return response()->json(["status"=>1]);
          }else {
            return response()->json(["status"=>0]);
          }
        }else {
          return response()->json(["status"=>0]);
        }
      }else {
        $d["foto"] = null;
        $ins = Siswa::create($d);
        if ($ins) {
          return response()->json(["status"=>1]);
        }else {
          return response()->json(["status"=>0]);
        }
      }
    }

    public function guru()
    {
      return view("admin.guru")->with(["title"=>"Data Guru"]);
    }
    public function api_gururead()
    {
      $data = [];
      $data["data"] = [];
      $d = Guru::get();
      foreach ($d as $key => $value) {
        $data["data"][] =[($key+1),$value->nip,$value->nama,$value->alamat,$value->no_hp,$value->email,$value->matpels->count() ." Matpel",$value->nip];
      }
      return response()->json($data);
    }
    public function api_gurushow($id)
    {
      $d = Guru::where(["nip"=>$id]);
      if ($d->count() > 0) {
        $ds = $d->first();
        return response()->json(["status"=>1,"data"=>$ds]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_guruedit(Request $req,$id)
    {
      $req->validate([
        "nama"=>"required",
        "no_hp"=>"required",
      ]);
      $d = $req->all();
      if ($d["password"] == "") {
        unset($d["password"]);
      }
      $ins = Guru::where(["nip"=>$id])->update($d);
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_guruadd(Request $req)
    {
      $req->validate([
        "nama"=>"required",
        "no_hp"=>"required",
        "password"=>"required",
        "nip"=>"required|unique:guru,nip",
      ]);
      $ins = Guru::create($req->all());
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }

    public function matpel()
    {
      $kelas = Kela::get();
      $guru = Guru::get();
      return view("admin.matpel")->with(["title"=>"Data Mata Pelajaran","kelas"=>$kelas,"guru"=>$guru]);
    }
    public function api_matpelread()
    {
      $data = [];
      $data["data"] = [];
      $d = Matpel::get();
      foreach ($d as $key => $value) {
        $nkelas = null;
        if (isset($value->kela->kela->nama)) {
          $nkelas = $value->kela->kela->nama."_".$value->kela->nama;
        }else {
          $nkelas = $value->kela->nama;
        }
        $data["data"][] =[($key+1),$value->nama,$nkelas,((isset($value->guru->nama))?$value->guru->nama:null),$value->id];
      }
      return response()->json($data);
    }
    public function api_matpelshow($id)
    {
      $d = Matpel::where(["id"=>$id]);
      if ($d->count() > 0) {
        $ds = $d->first();
        return response()->json(["status"=>1,"data"=>$ds]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_matpeledit(Request $req,$id)
    {
      $req->validate([
        "nama"=>"required",
        "kelas_id"=>"required",
      ]);
      $d = $req->all();
      $ins = Matpel::where(["id"=>$id])->update($d);
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_matpeladd(Request $req)
    {
      $req->validate([
        "nama"=>"required",
        "kelas_id"=>"required",
      ]);
      $ins = Matpel::create($req->all());
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function listmatpel($id)
    {
      return view("admin.matpel_detail")->with(["title"=>"Detail Ujian","id"=>$id]);
    }
    public function api_readujian($id)
    {
      $data = [];
      $data["data"] = [];
      $get = Matpel::where(["nip"=>$id]);
      foreach ($get->get() as $key => $value) {
        $kelas = $value->kela->nama;
        if (isset($value->kela->kela->nama)) {
          $kelas = $value->kela->kela->nama."_".$value->kela->nama;
        }
        $data["data"][] = [($key+1),$value->nama,$kelas,$value->ujians->count(),$value->id];
      }
      return response()->json($data);
    }
    public function api_readujiandetail($id)
    {
      $data = [];
      $data["data"] = [];
      $get = Matpel::where(["id"=>$id]);
      if ($get->count() > 0) {
        $row = $get->first();
        foreach ($row->ujians as $key => $value) {
          $data["data"][] = [($key+1),$value->nama_ujian,$value->pin,$value->id];
        }
        return response()->json($data);
      }else {
        return response()->json($data);
      }
    }
    public function api_readujiandetaildetail($id)
    {
      $data = [];
      $data["data"] = [];
      $d = Jawaban::where(["ujian_id"=>$id]);
      $nis = [];
      $counter = [];
      foreach ($d->get() as $key => $value) {
        $nis[] = $value->nis;
        $counter[] = ["nis"=>$value->nis,"id"=>$value->id];
      }
      $nis = array_unique($nis);
      $compact = [];
      foreach ($nis as $key => $value) {
        $t = [];
        foreach ($counter as $k => $v) {
          if ($value == $v["nis"]) {
            $t[] = $v["id"];
          }
        }
        $compact[$value] = $t;
      }
      $ids = [];
      foreach ($compact as $key => $value) {
        $ids[] = $value[(count($value)-1)];
      }
      $ned = Jawaban::whereIn("id",$ids);
      foreach ($ned->get() as $key => $value) {
        $ujianTotal = $value->ujian->ujian_items->count();
        $jawabanItem = $value->jawaban_items;
        $essay = [];
        foreach ($jawabanItem as $k => $v) {
          if ($v->ujian_item->banksoal->jenis == "es") {
            $essay[] = $v->id;
          }
        }
        $btn = "<button class='btn btn-primary resetessay m-1' data-id='$value->id'>Reset Essay</button>";
        $btn = $btn."<button class='btn btn-primary resetjawaban m-1' data-id='$value->id'>Reset Jawaban</button>";
        $nilai = null;
        if (count($essay) == 0) {
          $btn = "<button class='btn btn-primary' disabled>Koreksi</button>";
          $tpg = 0;
          $totalPG = 0;
          foreach ($jawabanItem as $ke => $nilai_ex) {
            if ($nilai_ex->ujian_item->banksoal->jenis == "pg") {
                if (strtoupper($nilai_ex->jawaban) == strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg)) {
                  $totalPG++;
                  $tpg++;
              }
            }
          }
          if ($totalPG > 0 & $tpg > 0) {
            $nilai = "<span class='badge badge-primary m-1'>".number_format(($tpg*10)/($totalPG/10))."</span> <span class='badge badge-danger m-1'>".number_format(($tpg*10)/($totalPG/10))."</span>";
          }else {
            $nilai = "<label class='badge badge-danger'>Perlu Dilakukan Ujian Susulan</label>";
          }
        }else {
          if ($value->essay != null) {
            $tpg = 0;
            $totalPG = 0;
            foreach ($jawabanItem as $ke => $nilai_ex) {
              if ($nilai_ex->ujian_item->banksoal->jenis == "pg") {
                $totalPG++;
                if (strtoupper($nilai_ex->jawaban) == strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg)) {
                  $tpg++;
                }
              }
            }
            $nilai = "<span class='badge badge-primary m-1'>".number_format((((int)$value->essay) + (($tpg*10)/($totalPG/10)))/2)."</span> <span class='badge badge-danger m-1'>".number_format(($tpg*10)/($totalPG/10))."</span> <span class='badge badge-success m-1'>".number_format($value->essay)."</span>";
          }else {
            $tpg = 0;
            $totalPG = 0;
            foreach ($jawabanItem as $ke => $nilai_ex) {
              if ($nilai_ex->ujian_item->banksoal->jenis == "pg") {
                $totalPG++;
                if (strtoupper($nilai_ex->jawaban) == strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg)) {
                  $tpg++;
                }
              }
            }
            $nilai = "<span class='badge badge-primary m-1'>0</span> <span class='badge badge-danger m-1'>".number_format(($tpg*10)/($totalPG/10))."</span> <span class='badge badge-success m-1'>0</span>";
          }
        }
        $data["data"][] = [($key+1),$value->nis,$value->siswa->nama,$value->siswa->kela->kela->nama."_".$value->siswa->kela->nama,$nilai,date("d-m-Y",strtotime($value->dibuat)),$btn];
      }
      return response()->json($data);

    }
    public function ressjawaban($id)
    {
      $c = Jawaban::where(["id"=>$id]);
      if ($c->count() > 0) {
        $d = JawabanItem::where(["jawaban_id"=>$id])->delete();
        if ($d) {
          return ["status"=>1];
        }else {
          return ["status"=>0];
        }
      }else {
        return ["status"=>0];
      }
    }
    public function ressessay($id)
    {
      $c = Jawaban::where(["id"=>$id]);
      $up = $c->update(["essay"=>null]);
      if ($up) {
        return ["status"=>1];
      }else {
        return ["status"=>0];
      }
    }

    public function api_siswaexport_template(Request $req)
    {
        $field_entry = [
              "A1"=>"nis",
              "B1"=>"nama",
              "C1"=>"password",
              "D1"=>"jk",
              "E1"=>"kelas",
        ];

        $field_helper = [
            "A1"=>"kelas_id",
            "B1"=>"nama_kelas"
        ];

        $data = Kela::where(["rombel_id"=>$req->id])->whereRaw("kelas_id IS NOT NULL")->get();
        foreach ($data as $index => $datum) {
            $field_helper["A".($index+2)] = $datum->id;
            $field_helper["B".($index+2)] = $datum->kela->nama." - ".$datum->nama;
        }
        $create = new ImporterExcel();
        $create->properties([
            "creator"=>"SMK Kesehatan",
            "title"=>"Template Siswa",
            "subject"=>"",
            "description"=>""
        ])->setSheet(0)->write($field_entry,"Entry Data",TRUE)->setSheet(1)->write($field_helper,"Data Kelas",TRUE)->output();

    }

    public function api_siswabulkpassword(Request $req){
        $req->validate([
            "excel"=>"mimes:xlsx|max:10000"
        ]);
        $d = $req->all();
        if ($req->has("excel")) {
            $imageName = time() . '.' . $req->excel->getClientOriginalExtension();
            $sa = $req->excel->move(public_path('upload'), $imageName);
            if ($sa) {
                $fullpath = public_path('upload')."/".$imageName;
                $excel = new ImporterExcel($fullpath);
                $op = [
                    "nis"=>"nis",
                    "password"=>"password",
                    "nama"=>"nama",
                    "jk"=>"jk",
                    "kelas"=>"kelas",
                ];
                $cb = [];
                $anon = function ($data){
                    $res = [];
                    foreach ($data as $index => $datum) {
                        if ($datum["nis"] != null && $datum["password"] != ""){
                            $res[] = $datum;
                        }
                    }
                    return $data;
                };
                $excel->type("array")->setLabel(1)->reformat($op)->operation($anon,$cb);
                $debug = [];
                $failed_insert = [];
                foreach ($cb as $index => $item) {
                    $find = Siswa::where(["nis"=>$item["nis"]]);
                    if($find->count() > 0){
                        $find->update(["password"=>$item["password"]]);
                        $debug[$item["nis"]] = $find;
                    }else{
                        if($item["nama"] == "-"){
                            continue;
                        }
                        $build = [
                            "nis"=>$item["nis"],
                            "nama"=>$item["nama"],
                            "alamat"=>"",
                            "no_hp"=>"",
                            "jk"=>(($item["jk"] == "L")?0:1),
                            "foto"=>"",
                            "email"=>"",
                            "password"=>$item["password"],
                            "kelas_id"=>$item["kelas"],
                            "dibuat"=>date("Y-m-d H:i:s"),
                        ];
                        $save = Siswa::create($build);
                        if (!$save){
                            $failed_insert[] = $item["nis"];
                        }
                    }
                }
                return response()->json(["status"=>1,"datas"=>$debug,"insert_failed"=>$failed_insert],200);
            }
        }else{
            return response()->json(["status"=>0],400);
        }
    }
}
