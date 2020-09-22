@extends('layout.app')
@section('title',$title)
@section('css')
<link rel="stylesheet" href="{{url("assets/plugins/chart.js/Chart.min.css")}}">
@endsection
@section('url',session()->get("url"))
@section('konten')
<div class="row">
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h5 class="m-0">Statistik Pengerjaan Ujian</h5>
      </div>
      <div class="card-body">

      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h5 class="m-0">Selamat Datang Guru</h5>
      </div>
      <div class="card-body">

      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{url("assets/plugins/chart.js/Chart.bundle.min.js")}}" charset="utf-8"></script>
<script type="text/javascript">

</script>
@endsection
