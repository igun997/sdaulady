@extends('layout.app')
@section('title',"Masuk ke Sistem")
@section('css')

@endsection

@section('url',"")
@section('js')
<script type="text/javascript">
  $("#dtable").DataTable({
    ajax:"{{route("login.api.read")}}"
  });
</script>
@endsection
@section('konten')
<div class="row">
  <div class="col-6 offset-3">
    <div class="card">
      <div class="card-header">
        <h5 class="m-0">Login</h5>
      </div>
      <div class="card-body">
        <div class="col-12">
          @if(session()->has("msg"))
          <div class="alert alert-success">
            <center>{{session()->get("msg")}}</center>
          </div>
          @endif
          @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p><center>{{ $error }}</center></p>
                @endforeach
            </div>
           @endif
        </div>
        <div class="col-12">
          <form class="" action="{{route("login_action")}}" method="post">
            @csrf
          <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" placeholder="NIP / Username" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-default btn-block">
              Login
            </button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="m-0">Data Ujian Terupload</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
      <table class="table table-striped" id="dtable">
        <thead>
          <th>No</th>
          <th>Matpel</th>
          <th>Guru</th>
          <th>Tgl</th>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
      </div>
    </div>
  </div>
</div>
@endsection
