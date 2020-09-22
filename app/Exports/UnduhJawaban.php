<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\{Admin,Banksoal,Guru,Jawaban,JawabanItem,Kela,Matpel,Rombel,Siswa,Ujian,UjianItem};
class UnduhJawaban implements FromView
{
    public $id;
    public $nis;
    public function __construct($id,$nis = NULL)
    {
        $this->id = $id;
        $this->nis = $nis;
    }
    public function view(): View
    {
        $data = [];
        if ($this->nis){
            $d = Jawaban::where(["ujian_id"=>$this->id,"nis"=>$this->nis]);
        }else{
            $d = Jawaban::where(["ujian_id"=>$this->id]);
        }
        $nilai_essay = [];
        $nilai_essay[] = 0;

        $nis = [];
        $counter = [];
        foreach ($d->get() as $key => $value) {
          if ($value->essay > 0){
                $nilai_essay[] = $value->essay;
          }
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
          $nilai = null;
          $totalPG = 0;
          $tpg = 0;
          $fpg = 0;
          $itemJawab = [];
          $itemKunci = [];
          foreach ($jawabanItem as $ke => $nilai_ex) {
            if ($nilai_ex->ujian_item->banksoal->jenis == "pg") {
              $totalPG++;
              $itemJawab[] = $nilai_ex->jawaban;
              $itemKunci[] = strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg);
              if (strtoupper($nilai_ex->jawaban) == strtoupper($nilai_ex->ujian_item->banksoal->jawaban_pg)) {
                $tpg++;
              }
            }
          }
          $fpg = ($totalPG - $tpg);
          $itemJawab = implode("",$itemJawab);
          $itemKunci = implode("",$itemKunci);
          // $itemJawab = count($itemJawab);
          // $itemKunci = count($itemKunci);
          $data[] = ["no"=>($key+1),"nama"=>$value->siswa->nama,"jk"=>(($value->siswa->jk == 0)?"Laki - Laki":"Perempuan"),"rician_jawaban"=>$itemJawab,"rincian_kunci"=>$itemKunci,"benar"=>$tpg,"salah"=>$fpg,"skor"=>$tpg,"pg"=>(($tpg*10)/($totalPG/10)),"es"=>array_sum($nilai_essay),"nilai"=>((($tpg*10)/($totalPG/10))*0.5)+(array_sum($nilai_essay)*0.5),"ket"=>""];
        }
        return view('exports.jawaban', [
            'data' => $data
        ]);
    }
}
