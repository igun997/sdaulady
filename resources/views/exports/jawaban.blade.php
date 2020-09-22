<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Exports Jawaban</title>
  </head>
  <body>
    <table>
      <thead>
        <tr>
          <th colspan="9">Kunci Jawaban</th>
          <th>{{((is_array($data) && count($data) > 0)?$data[0]["rincian_kunci"]:NULL)}}</th>
        </tr>
        <tr>
            <th colspan="10">* Jawaban Dengan Huruf "X" Menandakan Jawaban Kosong </th>
        </tr>
        <tr>
          <th rowspan="2">No</th>
          <th rowspan="2">Nama Siswa</th>
          <th rowspan="2">L/P</th>
          <th rowspan="2">Rincian Jawaban</th>
          <th colspan="2">Jumlah</th>
          <th rowspan="2">Nilai Essay</th>
          <th rowspan="2">Nilai PG</th>
          <th rowspan="2">Nilai Akhir</th>
          <th rowspan="2">Ket</th>
        </tr>
        <tr>
          <th>Benar</th>
          <th>Salah</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $k => $v)
        <tr>
          <td>{{$v["no"]}}</td>
          <td>{{$v["nama"]}}</td>
          <td>{{$v["jk"]}}</td>
          <td>{{$v["rician_jawaban"]}}</td>
          <td>{{$v["benar"]}}</td>
          <td>{{$v["salah"]}}</td>
          <td>{{$v["es"]}}</td>
          <td>{{$v["pg"]}}</td>
          <td>{{$v["nilai"]}}</td>
          <td>{{$v["ket"]}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>
