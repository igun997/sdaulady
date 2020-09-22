<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Admin, Banksoal, Guru, Jawaban, JawabanItem, Kela, Matpel, Rombel, Siswa, Ujian, UjianItem};
use App\Exports\{UnduhJawaban};
use Excel;

class SiswaControl extends Controller
{
    public function login(Request $req)
    {
        $s = Siswa::where($req->all());
        if ($s->count() > 0) {
            $nama = $s->first()->nama;
            $data = $req->all();
            $data["nama"] = $nama;
            return response()->json(["status" => 1, "data" => $data]);
        } else {
            return response()->json(["status" => 0]);
        }
    }
    public function unduh($nis,$id){

        return Excel::download(new UnduhJawaban($id,$nis), 'unduh_'.$nis.'.xlsx');
    }
    public function ujian($nis)
    {
        $data = [];
        $data["data"] = [];
        $c = Siswa::where(["nis" => $nis]);
        if ($c->count() > 0) {
            $d = $c->first();
            $jurusan = $d->kela;
            $kelas = $d->kela->kela;
            $matpel_jurusan = $jurusan->matpels;
            $matpel_kelas = $kelas->matpels;
            $getInMatpel = [];
            foreach ($matpel_jurusan as $key => $value) {
                $getInMatpel[] = $value->id;
            }
            foreach ($matpel_kelas as $key => $value) {
                $getInMatpel[] = $value->id;
            }
            $ujian = Ujian::whereIn("matpel_id", $getInMatpel)->get();
            foreach ($ujian as $key => $value) {
                $tutup = strtotime($value->tgl_ditutup);
                if($tutup < time()){
                    continue;
                }
                $sync = "Belum";
                $tutup = date("d-m-Y H:i:s", strtotime($value->tgl_ditutup));
                $isSync = Jawaban::where(["ujian_id" => $value->id, "nis" => $nis]);
                if ($isSync->count() > 0) {
                    $sync = "Sudah";
                }
                if ($value->tgl_ditutup == null) {
                    $tutup = "Tidak Ditentukan";
                }
                if ($isSync->count() > 0) {
                    $data["data"][] = [($key + 1), $value->matpel->nama."( ".$value->nama_ujian." )", "(" . date("d-m-Y H:i:s", strtotime($value->tgl_dibuka)) . ") -> (" . $tutup . ")", $value->waktu . " Menit", $sync, "<button class='button button_small selesai' data-id='$value->id' disabled>Dikerjakan</button>"];
                } else {

                     $data["data"][] = [($key + 1), $value->matpel->nama."( ".$value->nama_ujian." )", "(" . date("d-m-Y H:i:s", strtotime($value->tgl_dibuka)) . ") -> (" . $tutup . ")", $value->waktu . " Menit", $sync, "<button class='button button_small unduh' data-id='$value->id' disabled>Unduh</button>"];
                }
            }
        }
        return response()->json($data);
    }

    public function soal($ujian)
    {
        $soal = [];
        $ujian = ujian::where(["id" => $ujian]);
        if ($ujian->count() > 0) {
            $data = $ujian->first();
            $si = [];
            foreach ($data->ujian_items as $key => $value) {
                $soal_list = $value->banksoal->soal;

                if ($value->banksoal->jenis == "pg") {
                    $si[] = ["id" => $value->id, "tipe" => $value->banksoal->jenis, "soal" => $soal_list, "pg_a" => $value->banksoal->pg_a, "pg_b" => $value->banksoal->pg_b, "pg_c" => $value->banksoal->pg_c];
                } else {

                    $si[] = ["id" => $value->id, "tipe" => $value->banksoal->jenis, "soal" => $soal_list];
                }
            }
            shuffle($si);
            $soal[$data->id] = ["matpel" => $data->matpel->nama, "buka" => date("Y-m-d H:i:s", strtotime($data->tgl_dibuka)), "ditutup" => date("Y-m-d H:i:s", strtotime($data->tgl_ditutup)), "nama_ujian" => $data->nama_ujian, "waktu" => $data->waktu, "pin" => $data->pin, "soal" => $si];
            return response()->json(["status" => 1, "data" => $soal]);
        } else {
            return response()->json(["status" => 0]);
        }
    }

    public function jawaban(Request $req)
    {
        $data = $req->all();
        unset($data["nis_siswa"]);
        unset($data["password"]);
        $jawaban = [];
        $ins = Jawaban::create(["nis" => $data["nis"], "ujian_id" => $data["ujian_id"]]);
        if ($ins) {
            $lid = $ins->id;
            foreach ($data["jawaban"] as $key => $value) {
                $jawaban[] = ["ujian_item_id" => $key, "jawaban_id" => $lid, "jawaban" => $value];
            }
            $s = JawabanItem::insert($jawaban);
            if ($s) {
                return response()->json(["status" => 1]);
            } else {
                return response()->json(["status" => 0]);
            }
        } else {
            return response()->json(["status" => 0]);
        }
    }

}
