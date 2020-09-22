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
        <div class="col-12">
          <button type="button" id="form" class="btn btn-default m-1">
            <i class="fa fa-plus"></i> Tambah Kelas
          </button>
          <div class="col-12">
            <form class="" action="" id="formSubmit" method="post">
              <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Kelas" required>
              </div>
              <div class="form-group">
                <label>Rombel</label>
                <select class="form-control" name="rombel_id">
                  @foreach($rombel as $k => $v)
                  <option value="{{$v->id}}">{{$v->nama}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Kelas Induk</label>
                <select class="form-control" name="kelas_id">
                  <option value=""></option>
                  @foreach($kelas as $k => $v)
                  <option value="{{$v->id}}">{{$v->nama}}</option>
                  @endforeach
                </select>
                <p>* Seperti kelas X mempunyai 2 kelas pecahan X A , X B dst</p>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">
                  Simpan
                </button>
              </div>
            </form>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered" id="dtable">
              <thead>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Rombel</th>
                <th>Total Siswa</th>
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
@endsection

@section('js')
<script type="text/javascript">
  var dtable = $("#dtable").DataTable({
    ajax:"{{route("admin.kelas.api.read")}}",
    createdRow:function(r,d,i){
      btn = '<button type="button" class="btn btn-warning ubah" data-id="'+d[4]+'" data-nama="'+d[1]+'">Ubah</button>';
      $("td",r).eq(4).html(btn);
    }
  });
  var toggle = true;
  temp = $("#formSubmit").html();
  console.log(temp);
  $("#formSubmit").hide();
  $("#form").on('click', function(event) {
    event.preventDefault();
    if (toggle) {
      $("#formSubmit").show();
      toggle = false;
    }else {
      $("#formSubmit").hide();
      toggle = true;
    }
  });
  var url = null;
  $("#dtable").on('click','.ubah', function(event) {
    event.preventDefault();
    url = "{{route("admin.kelas.api.edit")}}"+"/"+$(this).data("id");
    console.log(url);
    $("#formSubmit button[type=submit]").html("Ubah");
    $.get("{{route("admin.kelas.api.show")}}/"+$(this).data("id"),function(s){
      if (s.status == 1) {
        $("#formSubmit input[name=nama]").val(s.data.nama);
        $("#formSubmit select[name=rombel_id]").val(s.data.rombel_id);
        if (s.data.kelas_id != null) {
          $("#formSubmit select[name=kelas_id]").val(s.data.kelas_id);
        }
        $("#formSubmit select[name=rombel_id]").trigger('change');
        $("#formSubmit select[name=kelas_id]").trigger('change');
      }else {
        toastr.warning("Data Kelas Tidak Ditemukan");
      }
    }).fail(function(){
      toastr.warning("Anda Terputus Dengan Server");
    });
    $("#formSubmit button[type=submit]").attr("class","btn btn-warning");
    $("#formSubmit").show();
  });
  $("#formSubmit").on('submit',function(event) {
    event.preventDefault();
    data = $(this).serializeArray();
    if (url == null) {
      url = "{{route("admin.kelas.api.add")}}";
    }
    $.post(url,data,function(r){
      if (r.status == 1) {
        toastr.success("Data Tersimpan")
      }else {
        toastr.warning("Data Gagal Di Simpan")
      }
      $("#formSubmit")[0].reset();
      $("#formSubmit").hide();
      dtable.ajax.reload();
    }).fail(function(){
      toastr.warning("Anda Terputus Dengan Server");
    })
    url = null;
    $("#formSubmit").html(temp);
    $("#formSubmit").hide();
  });
</script>
@endsection
