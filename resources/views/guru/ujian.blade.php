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
          <div class="col-md-12">
            <button type="button" id="addUjian" class="btn btn-primary mb-4">
              Tambah Ujian
            </button>
          </div><div class="col-12">
            <div class="table-responsive">
              <table class="table table-bordered" id="dtable">
                <thead>
                  <th>No</th>
                  <th>Nama Ujian</th>
                  <th>Matpel</th>
                  <th>Tgl. Dibuka</th>
                  <th>Tgl. Ditutup</th>
                  <th>Waktu</th>
                  <th>PIN</th>
                  <th>Total Soal</th>
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
<script src="{{url("assets/plugins/ckeditor/ckeditor.js")}}" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var dtable = $("#dtable").DataTable({
        ajax:"{{route("guru.ujian.api.read")}}",
        createdRow:function(r,d,i){
          $("td",r).eq(5).html(d[5]+" Menit");
          btn = '<button type="button" class="btn btn-primary view m-1" data-id="'+d[9]+'"><i class="fa fa-eye"></i></button>';
          $("td",r).eq(9).html(btn);
          btn = '<button type="button" class="btn btn-warning edit m-1" data-id="'+d[9]+'"><i class="fa fa-edit"></i></button>';
          $("td",r).eq(9).append(btn);

        }
    });
    $("#addUjian").on('click',  function(event) {
      event.preventDefault();
      location.href='{{route("guru.ujian_add")}}';
    });
    $("#dtable").on('click', '.view', function(event) {
      event.preventDefault();
      console.log("Clicked");
      console.log($(this).data());
      location.href = "{{route("guru.ujian.rinci")}}/"+$(this).data("id");
    });
    $("#dtable").on('click', '.edit', function(event) {
      event.preventDefault();
      console.log("ClickedIt");
      location.href = "{{route("guru.ujian_edit")}}/"+$(this).data("id");
    });
  });
</script>
@endsection
