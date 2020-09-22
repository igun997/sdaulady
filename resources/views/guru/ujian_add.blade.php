@extends('layout.app')
@section('title',$title)
@section('css')

@endsection
@section('url',session()->get("url"))
@section('konten')
<div class="row">
  <div class="col-8 offset-2">
    <div class="card">
      <div class="card-header">
        <h5 class="m-0">{{$title}}</h5>
      </div>
      <div class="card-body">
        <form  action="" id="form" method="post" onsubmit="return false">
          <div class="row">
            <div class="col-6">
              <h5>Info Ujian</h5>
              <div class="form-group">
                <label>Mata Pelajaran</label>
                <select class="form-control" id="matpel_id" name="matpel_id">
                    <option value="">== Pilih == </option>
                    @foreach($matpel as $k => $v)
                    <option value="{{$v->id}}">
                      @if(isset($v->kela->kela->nama))
                      {{$v->nama}} ({{$v->kela->kela->nama}}_{{$v->kela->nama}})
                      @else
                      {{$v->nama}} ({{$v->kela->nama}})
                      @endif
                    </option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Nama Ujian</label>
                <input type="text" class="form-control" name="nama_ujian" value="">
              </div>
              <div class="form-group">
                <label>Tgl. Dibuka</label>
                <input type="text" class="form-control date" name="tgl_dibuka" value="">
              </div>
              <div class="form-group">
                <label>Tgl. Ditutup</label>
                <input type="text" class="form-control date" name="tgl_ditutup" value="">
              </div>
              <div class="form-group">
                <label>Waktu</label>
                <input type="text" class="form-control" name="waktu" value="">
              </div>
              <div class="form-group">
                <label>PIN</label>
                <input type="text" class="form-control" name="pin" value="">
              </div>

            </div>
            <div class="col-6">
              <div class="row">
                <div class="col-12">
                  <h5>Tambah Soal</h5>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Judul Soal</label>
                    <select class="form-control" id="judul_soal">
                      <option value="">== Pilih == </option>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <label style="color:white">Judul Soal</label>
                  <button type="button" id="addToUjian" class="btn btn-success">
                    Tambah ke Ujian
                  </button>
                </div>
              </div>
              <div class="col-6">
                <button type="submit"  class="btn btn-primary btn-block">
                  Simpan Ujian
                </button>
              </div>
            </div>
            <div class="col-12">
              <div class="table-responsive">
                <table class="table table-bordered" id="dtable">
                  <thead>
                    <th>Judul Soal</th>
                    <th>Poin</th>
                    <th>Opsi</th>
                  </thead>
                  <tbody id="dtable_content">

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection

@section('js')
<script src="{{url("assets/plugins/ckeditor/ckeditor.js")}}" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(".date").datetimepicker({
      format:"YYYY-MM-DD HH:mm:ss"
    });
    // var a = $("#judul_soal").select2("destroy");
    var x = 1;
    $("#form").on('submit', function(event) {
      event.preventDefault();
      dform = $(this).serializeArray();
      console.log("Submitted");
      console.log(dform);
      urlFeed = "{{route("guru.ujian.api.add")}}";
      $.post(urlFeed,dform,function(r){
        if (r.status == 1) {
          toastr.success("Sukses simpan data ujian");
          setTimeout(function () {
            location.href = "{{route("guru.ujian")}}";
          }, 1000);
        }else {
          toastr.warning("Gagal simpan data ujian");
        }
      }).fail(function(){
        toastr.error("Anda terputus dari server");
      })
    });
    function tr(build) {
      spoin = "Diratakan";
      if (build.poin != null) {
        spoin = build.poin;
      }
      btn = "<button class='btn btn-primary view m-2' data-id='"+build.id+"'><li class='fa fa-eye'></li></button>";
      btn = btn+"<button class='btn btn-danger del m-2' data-id='"+build.id+"'><li class='fa fa-trash'></li></button>";
      hide = '<input name="banksoal_id[]" value="'+build.id+'" hidden/>';
      var s = [
        "<tr id='tr"+build.id+"'>",
        "<td>"+build.judul+"</td>",
        "<td>"+spoin+"</td>",
        "<td>"+btn+hide+"</td>",
        "</tr>",
      ]
      return s.join("");
    }
    $("#addToUjian").on('click', function(event) {
      event.preventDefault();
      id = $("#judul_soal").val();
      urlFinder = "{{route("guru.banksoal.api.view")}}/"+id;
      console.log(urlFinder);
      $.get(urlFinder,function(rs){
        console.log(rs);
        if (rs.status == 1) {
            append = tr(rs.data);
            $("#dtable #dtable_content").append(append);
        }else {
          toastr.warning("Data tidak ditemukan");
        }
      }).fail(function(){
        toastr.warning("Anda terputus dari server");
      })
    });
    $("#dtable #dtable_content").on('click', '.view', function(event) {
      event.preventDefault();
      console.log("View");
      d = $(this).data("id");
      console.log(d);
      u = "{{route("guru.banksoal.api.view")}}/"+d;
      $.get(u,function(rs){
          if (rs.status == 1) {
            var dialog = bootbox.dialog({
              title: 'Loading Data',
              message: '<p><center><i class="fa fa-spin fa-spinner"></i> Loading...</center></p>'
            });
            dialog.init(function() {
              setTimeout(function() {
                dialog.find(".modal-title").html("(MELIHAT) "+rs.data.judul);
                choice = null;
                jjenis = null;
                jawaban = null;
                poin = "(Diaratakan)";
                if (rs.data.poin != null) {
                  poin = rs.data.poin;
                }
                if (rs.data.jenis == "pg") {
                  jjenis = "PILIHAN GANDA";
                  jawaban = "Jawaban : "+rs.data.jawaban_pg;
                  choice = [
                    '<p>A. '+rs.data.pg_a+'</p>',
                    '<p>B. '+rs.data.pg_b+'</p>',
                    '<p>C. '+rs.data.pg_c+'</p>',
                    '<p>D. '+rs.data.pg_d+'</p>',
                    '<p>E. '+rs.data.pg_e+'</p>',
                  ];
                  choice = choice.join("");
                }else {
                  jjenis = "ESSAY";
                  jawaban = "Jawaban : "+rs.data.jawaban_es;
                }

                html = [
                  "<div class=row>",
                  "<div class='col-md-12 m-2'>",
                  '<h4>SOAL '+jjenis+'</h4>',
                  '<hr>',
                  rs.data.soal,
                  choice,
                  "</div>",
                  "<div class='col-md-12 m-2'>",
                  '<h4>JAWABAN '+jjenis+'</h4>',
                  '<hr>',
                  jawaban,
                  "</div>",
                  "<div class='col-md-12 m-2'>",
                  '<hr>',
                  '<h4>NILAI PER SOAL : '+poin+'</h4>',
                  "</div>",
                  "</div>",
                ]
                dialog.find(".bootbox-body").html(html.join(""));
              },200);
            });
          }else {
            toastr.warning("Data soal tidak ditemukan");
          }
      }).fail(function(){
        toastr.warning("Anda terputus dengan server");
      });
    });
    $("#dtable #dtable_content").on('click', '.del', function(event) {
      event.preventDefault();
      console.log("Del");
      v = $(this).data("id");
      $("#tr"+v).remove();
    });
    $("#matpel_id").on('change', function(event) {
      event.preventDefault();
      console.log("Changed");
      val = $(this).val();
      console.log(val);
      urlFeed = "{{route("guru.ujian.api.banksoal")}}";
      console.log(urlFeed);
      $.get(urlFeed+"/"+val,function(rs){
        if (rs.status == 1) {
          $("#judul_soal").select2("destroy")
          $("#judul_soal").html("");
          for (var i = 0; i < rs.data.length; i++) {
            $("#judul_soal").append('<option value="'+rs.data[i].id+'">'+rs.data[i].judul+'</option>');
          }
          $("#judul_soal").select2();
        }else {
          toastr.warning("Mata Pelajaran Tidak Ditemukan");
        }
      }).fail(function(){
        toastr.warning("Anda terputus dari sistem");
      })
    });
  });
</script>
@endsection
