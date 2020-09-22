<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Admin,Banksoal,Guru,Jawaban,JawabanItem,Kela,Matpel,Rombel,Siswa,Ujian,UjianItem};
class GuruControl extends Controller
{
    public function index()
    {
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      return view("guru.home")->with(["title"=>"Beranda Guru"]);
    }
    public function upload(Request $request)
    {
        $request->validate([
          "upload"=>"mimes:jpg,png,gif,jpeg"
        ]);
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('upload'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = url('upload/'.$fileName);
            $msg = 'Gambar Berhasil di Upload';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }

    }
    public function ujian_rincian($id)
    {
      $c = Ujian::where(["id"=>$id]);
      if ($c->count() > 0) {
        $d = $c->first();
        return view("guru.ujian_view")->with(["title"=>"Rincian UJIAN ".$d->nama_ujian,"data"=>$d]);
      }else {
        return back();
      }
    }
    public function api_ujianessay(Request $req,$id)
    {
      $req->validate([
        "nilai"=>"required|max:100|min:1"
      ]);
      $cek = Jawaban::where(["id"=>$id]);
      if ($cek->count() > 0) {
        if ($req->nilai == 0) {
          return response()->json(["status"=>0]);
        }
        if ($req->nilai > 100) {
          return response()->json(["status"=>0]);
        }
        $up = $cek->update(["essay"=>$req->nilai]);
        if ($up) {
          return response()->json(["status"=>1]);
        }else {
          return response()->json(["status"=>0]);
        }
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_ujiannilairead($id = null)
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
        $btn = "<button class='btn btn-primary koreksi m-1' data-id='$value->id'>Koreksi</button>";
        $nilai = "<label class='badge badge-danger'>Perlu Koreksi Essay</label>";
        if (count($essay) == 0) {
          $btn = "<button class='btn btn-primary' disabled>Koreksi</button>";
          $tpg = 0;
          $totalPG = 0;
          foreach ($jawabanItem as $ke => $nilai_ex) {
            $totalPG++;
            if ($nilai_ex->ujian_item->banksoal->jenis == "pg") {
              if (strtoupper($nilai_ex->jawaban) == strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg)) {
                $tpg++;
              }
            }
          }
          if ($totalPG > 0) {
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
              $nilaiPg = 0;
              if ($tpg > 0 && $totalPG > 0){
                  $nilaiPg = ((($tpg*10)/($totalPG/10)))/2;
              }
            $nilai = "<span class='badge badge-primary m-1'>".number_format(((int) $value->essay)+($nilaiPg))."</span> <span class='badge badge-danger m-1'>".number_format($nilaiPg)."</span> <span class='badge badge-success m-1'>".number_format($value->essay)."</span>";
            $btn = "<button class='btn btn-primary' disabled>Koreksi</button>";

          }else {
            $tpg = 0;
            $totalPG = 0;
            foreach ($jawabanItem as $ke => $nilai_ex) {
              $totalPG++;
              if ($nilai_ex->ujian_item->banksoal->jenis == "pg") {
                if (strtoupper($nilai_ex->jawaban) == strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg)) {
                  $tpg++;
                }
              }
            }
            $nilai = "<span class='badge badge-primary m-1'>0</span> <span class='badge badge-danger m-1'>".number_format(($tpg*10)/($totalPG/10))."</span> <span class='badge badge-success m-1'>0</span>";
          }
        }

        $btn .= "<button class='btn btn-danger unduh m-1' data-id='".$value->id."' type='button'>Unduh</button>";
        $data["data"][] = [($key+1),$value->nis,$value->siswa->nama,$value->siswa->kela->kela->nama."_".$value->siswa->kela->nama,$nilai,date("d-m-Y",strtotime($value->dibuat)),$btn];
      }
      return response()->json($data);
    }
    public function banksoal()
    {
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      return view("guru.banksoal")->with(["title"=>"Bank Soal","matpel"=>$matpel]);
    }
    public function api_banksoalread()
    {
      $data = [];
      $data["data"] = [];
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      $m = [];
      foreach ($matpel as $key => $value) {
        $m[] = $value->id;
      }
      $d = Banksoal::whereIn("matpel_id",$m)->orderBy("dibuat","DESC")->get();
      foreach ($d as $key => $value) {
        $jawaban = null;
        if ($value->jenis == "pg") {
          $jawaban = $value->jawaban_pg;
        }else {
          $jawaban = $value->jawaban_es;
        }
        $data["data"][] =[($key+1),$value->matpel->nama,$value->judul,$value->jenis,$jawaban,$value->poin,date("d-m-Y",strtotime($value->dibuat)),$value->id];
      }
      return response()->json($data);
    }
    public function api_banksoaladd(Request $req)
    {
      $req->validate([
        "matpel_id"=>"required",
        "judul"=>"required",
        "soal"=>"required",
        "jenis"=>"required",
      ]);
      $d = $req->all();

      if (((int)$d["poin"]) == 0) {
        $d["poin"] = null;
      }
      $ins = Banksoal::create($d);
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_banksoaledit(Request $req,$id)
    {
      $req->validate([
        "matpel_id"=>"required",
        "judul"=>"required",
        "soal"=>"required",
        "jenis"=>"required",
      ]);
      $d = $req->all();

      if (((int)$d["poin"]) == 0) {
        $d["poin"] = null;
      }
      $ins = Banksoal::where(["id"=>$id])->update($d);
      if ($ins) {
        return response()->json(["status"=>1]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_banksoalview($id)
    {
      $cek = Banksoal::where(["id"=>$id]);
      if ($cek->count() > 0) {
        $d = $cek->first();
        return response()->json(["status"=>1,"data"=>$d]);
      }else {
        return response()->json(["status"=>0]);
      }
    }

    public function ujian()
    {
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      return view("guru.ujian")->with(["title"=>"Data Ujian","matpel"=>$matpel]);
    }
    public function api_ujianedit(Request $req,$id)
    {
      $req->validate([
        "nama_ujian"=>"required",
        "matpel_id"=>"required",
        "tgl_dibuka"=>"required",
        "tgl_ditutup"=>"required",
        "waktu"=>"required",
      ]);
      $d = $req->all();
      if ($d["pin"] == "") {
        $d["pin"] = null;
      }
      if (count($d["banksoal_id"]) == 0) {
        return response()->json(["status"=>0]);
      }else {
        $bsoal = $d["banksoal_id"];
        unset($d["banksoal_id"]);
      }
      // return response()->json($d);
      $s = Ujian::where(["id"=>$id])->update($d);
      if ($s) {
        $lastid = $id;
        $newbsoal = [];
        foreach ($bsoal as $key => $value) {
            $newbsoal[] = ["ujian_id"=>$lastid,"banksoal_id"=>$value];
        }
        UjianItem::where(["ujian_id"=>$id])->whereIn("banksoal_id",$bsoal)->delete();
        $so = UjianItem::insert($newbsoal);
        if ($so) {
          return response()->json(["status"=>1]);
        }else {
          Ujian::find($lastid)->delete();
          return response()->json(["status"=>2]);
        }
      }else {
        return response()->json(["status"=>3]);
      }
    }
    public function ujian_edit($id)
    {
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      $selected = Ujian::where(["id"=>$id]);
      if ($selected->count() > 0) {
        $s = $selected->first();
        $s->ujian_items;
        return view("guru.ujian_edit")->with(["title"=>"Edit Data Ujian","selected"=>$s,"banksoal"=>$s->ujian_items,"matpel"=>$matpel]);
      }else {
        return back();
      }
    }
    public function api_ujianread()
    {
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      $d = [];
      foreach ($matpel as $key => $value) {
        $d[] = $value->id;
      }
      $getUjian = Ujian::whereIn("matpel_id",$d)->get();
      $data = [];
      $data["data"] = [];
      foreach ($getUjian as $key => $value) {
        $tutup = "-";
        if ($value->tgl_ditutup != null) {
          $tutup = date("d-m-Y H:i:m",strtotime($value->tgl_ditutup));
        }
        $data["data"][] = [($key+1),$value->nama_ujian,$value->matpel->nama,date("d-m-Y H:i:m",strtotime($value->tgl_dibuka)),$tutup,$value->waktu,$value->pin,$value->ujian_items->count(),date("d-m-Y",strtotime($value->dibuat)),$value->id];
      }
      return response()->json($data);
    }
    public function ujian_add()
    {
      $matpel = Matpel::where(["nip"=>session()->get("id")])->get();
      return view("guru.ujian_add")->with(["title"=>"Tambah Data Ujian","matpel"=>$matpel]);
    }
    public function api_ujiansoal($id)
    {
      $search = Matpel::where(["id"=>$id]);
      if ($search->count() > 0) {
        $datasoal = $search->first();
        $soal = $datasoal->banksoals;
        return response()->json(["status"=>1,"data"=>$soal]);
      }else {
        return response()->json(["status"=>0]);
      }
    }
    public function api_ujianadd(Request $req)
    {
      $req->validate([
        "nama_ujian"=>"required",
        "matpel_id"=>"required",
        "tgl_dibuka"=>"required",
        "tgl_ditutup"=>"required",
        "waktu"=>"required",
      ]);
      $d = $req->all();
      if ($d["pin"] == "") {
        $d["pin"] = null;
      }
      if (count($d["banksoal_id"]) == 0) {
        return response()->json(["status"=>0]);
      }else {
        $bsoal = $d["banksoal_id"];
        unset($d["banksoal_id"]);
      }
      // return response()->json($d);
      $s = Ujian::create($d);
      if ($s) {
        $lastid = $s->id;
        $newbsoal = [];
        foreach ($bsoal as $key => $value) {
            $newbsoal[] = ["ujian_id"=>$lastid,"banksoal_id"=>$value];
        }
        $so = UjianItem::insert($newbsoal);
        if ($so) {
          return response()->json(["status"=>1]);
        }else {
          Ujian::find($lastid)->delete();
          return response()->json(["status"=>0]);
        }
      }else {
        return response()->json(["status"=>0]);
      }
    }

    public function api_getessay(Request $req)
    {
        $req->validate([
            "id"=>"exists:jawaban,id"
        ]);

        $jawaban_id = $req->id;
        $jawaban = Jawaban::where(["id"=>$jawaban_id]);
        if ($jawaban->count() > 0){
            $row = $jawaban->first();
            $list_jawaban = $row->jawaban_items()->get();
            foreach ($list_jawaban as $item) {
                $item->ujian_item;
                $item->ujian_item->banksoal;
            }

            $serve = [];
            foreach ($list_jawaban as $list) {
                if ($list->ujian_item->banksoal->jenis === "es"){
                    $serve[] = [
                        "jawaban_siswa"=>$list->jawaban,
                        "kunci_jawaban"=>$list->ujian_item->banksoal->jawaban_es
                    ];
                }
            }

            $resp = [
                "id"=>$jawaban_id,
                "lists"=>$serve
            ];

            return  response()->json(["status"=>1,"data"=>$resp]);
        }
        return response()->json(["status"=>0]);
    }
}
