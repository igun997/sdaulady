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
        <div class="col-12">
          <button type="button" id="form" class="btn btn-default m-1">
            <i class="fa fa-plus"></i> Tambah Mata Pelajaran
          </button>
          <div class="col-12">
            <form class="" action="" id="formSubmit" method="post">
              <div class="form-group">
                <label>Nama Mata Pelajaran</label>
                <input type="text" class="form-control" name="nama" placeholder="" required>
              </div>
              <div class="form-group">
                <label>Kelas</label>
                <select class="form-control" name="kelas_id">
                  @foreach($kelas as $k => $v)
                  <option value="{{$v->id}}">
                    @if(isset($v->kela->nama))
                    {{$v->kela->nama}}_{{$v->nama}}
                    @else
                    {{$v->nama}}
                    @endif
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Guru Pengampu</label>
                <select class="form-control" name="nip">
                  <option value=""></option>
                  @foreach($guru as $k => $v)
                  <option value="{{$v->nip}}">{{$v->nip}} - {{$v->nama}}</option>
                  @endforeach
                </select>
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
                <th>Nama</th>
                <th>Kelas</th>
                <th>Pengampu</th>
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
    ajax:"{{route("admin.matpel.api.read")}}",
    createdRow:function(r,d,i){
      btn = '<button type="button" class="btn btn-warning ubah" data-id="'+d[4]+'">Ubah</button>';
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
    url = "{{route("admin.matpel.api.edit")}}"+"/"+$(this).data("id");
    console.log(url);
    $("#formSubmit button[type=submit]").html("Ubah");
    $.get("{{route("admin.matpel.api.show")}}/"+$(this).data("id"),function(s){
      if (s.status == 1) {
        $("#formSubmit input[name=nama]").val(s.data.nama);
        $("#formSubmit select[name=kelas_id]").val(s.data.kelas_id);
        $("#formSubmit select[name=nip]").val(s.data.nip);
        $("select").trigger('change');
      }else {
        toastr.warning("Data Mata Pelajaran Tidak Ditemukan");
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
      url = "{{route("admin.matpel.api.add")}}";
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
    // $("#formSubmit").html(temp);
    // $("#formSubmit").hide();
  });
</script>
@endsection
