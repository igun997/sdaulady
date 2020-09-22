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
          <div class="table-responsive">
            <table class="table table-bordered" id="dtable">
              <thead>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>JK</th>
                <th>Foto</th>
                <th>Email</th>
                <th>Kelas</th>
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

  });
</script>
@endsection
