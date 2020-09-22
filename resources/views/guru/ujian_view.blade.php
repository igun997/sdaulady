@extends('layout.app')
@section('title',$title)
@section('css')

@endsection
@section('url',session()->get("url"))
@section('konten')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="m-0">{{$title}}</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
          <table class="table table-bordered" id="dtable">
            <thead>
              <th>No</th>
              <th>NIS</th>
              <th>Nama</th>
              <th>Kelas</th>
              <th>Nilai</th>
              <th>Dibuat</th>
              <th>Opsi</th>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    var dt = $("#dtable").DataTable({
      ajax:"{{route("guru.ujian.haisl.api.read",$data->id)}}"
    });
    function submitNilai(c,id){
        c = parseFloat(c);
        dform = {nilai:c};
        $.post("{{route("guru.ujian.hasil.api.update")}}/"+id,dform,function(r){
            if (r.status == 1) {
                toastr.success("Sukses Input Nilai");
                dt.ajax.reload();
            }else {
                toastr.warning("Input Nilai Gagal");
            }
        }).fail(function(){
            toastr.error("Anda terputus dari server");
        });
    }
    $("#dtable").on('click', '.koreksi', function(event) {
      event.preventDefault();
      let endpoint = "{{route("guru.ujian.api.api_getessay")}}";
      id = $(this).data("id");
      console.log(id);
      console.log(endpoint);

        var dialog = bootbox.dialog({
            title: 'Koreksi Essay',
            message: '<p align="center"><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });

        dialog.init(function(){
            setTimeout(function(){

                $.get(endpoint+"?id="+id,function (res) {
                    let butir = [];
                    if(res.status == 1){
                        const items = res.data;
                        $.each(items.lists,function (i,v) {
                            let temp = [
                                "<tr>",
                                "<td>"+(i+1)+"</td>",
                                "<td>"+v.jawaban_siswa+"</td>",
                                "<td>"+v.kunci_jawaban+"</td>",
                                "<td><input class='form-control' name='nilai' type='number' min=0 max=100 placeholder='Masukan Nilai dari 0 - 100' required /> </td>",
                                "</tr>",
                            ];
                            butir.push(temp.join(""));
                        })
                        let table = [
                            "<form onsubmit='return false' method='post'>",
                            "<table class='table table-bordered'>",
                            "<thead>",
                            "<th>No</th>",
                            "<th>Jawaban</th>",
                            "<th>Kunci</th>",
                            "<th>Nilai</th>",
                            "</thead>",
                            "<tbody>",
                            butir.join(""),
                            "</tbody>",
                            "<foot>",
                            "<th colspan='4'><button class='btn btn-primary btn-block'>Simpan Data</button></th>",
                            "</foot>",
                            "</table>",
                            "</form>",
                        ];
                        dialog.find('.bootbox-body').html(table.join(""));
                        dialog.find("form").on("submit",function () {
                            dform = $(this).serializeArray();
                            let nilai = 0;
                            $.each(dform,function (i,v) {
                                nilai += parseFloat(v.value);
                            })
                            submitNilai(nilai,id);
                        })
                    }else{
                        toastr.error("Data Jawaban Tidak Ditemukan");
                    }

                });
            }, 100);
        });
    });
  });
</script>
@endsection
