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
                            <i class="fa fa-plus"></i> Tambah Siswa
                        </button>
                        <a href="{{route("admin.siswa.api.download")}}?id=1" class="btn btn-primary m-1"><li class="fa fa-download"> Download Template Siswa</li></a>
                        <button type="button" id="formPassword" class="btn btn-default m-1">
                            <i class="fa fa-upload"></i> Upload Siswa
                        </button>
                        <div class="col-12">
                            <form class="" action="" id="formSubmit" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>NIS</label>
                                    <input type="text" class="form-control" name="nis" placeholder="NIS" required>
                                </div>
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="nama" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" class="form-control" name="no_hp" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" class="form-control-file" name="foto" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="8" cols="80"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select class="form-control" name="jk">
                                        <option value="0"> Laki-Laki</option>
                                        <option value="1"> Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select class="form-control" name="kelas_id">
                                        @foreach($kelas as $k => $v)
                                            <option value="{{$v->id}}">{{$v->kela->nama}} - {{$v->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                            <form class="" action="" id="bulk_excel" onsubmit="return false" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>File</label>
                                    <input type="file" class="form-control-file" name="excel" placeholder="">
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
            ajax: "{{route("admin.siswa.api.read")}}",
            createdRow: function (r, d, i) {
                btn = '<button type="button" class="btn btn-warning ubah" data-id="' + d[1] + '">Ubah</button>';
                $("td", r).eq(9).html(btn);
                img = null;
                if (d[6] != null) {
                    img = "<img class=img-fluid src='" + d[6] + "' />";
                }
                $("td", r).eq(6).html(img);
            }
        });
        var toggle = true;
        var toggle1 = true;
        temp = $("#formSubmit").html();
        temp1 = $("#bulk_excel").html();
        console.log(temp);
        $("#formSubmit").hide();
        $("#bulk_excel").hide();
        $("#form").on('click', function (event) {
            event.preventDefault();
            if (toggle) {
                $("#formSubmit").show();
                toggle = false;
            } else {
                $("#formSubmit").hide();
                toggle = true;
            }
        });
        var url = null;
        $("#dtable").on('click', '.ubah', function (event) {
            event.preventDefault();
            url = "{{route("admin.siswa.api.edit")}}" + "/" + $(this).data("id");
            console.log(url);
            $("#formSubmit button[type=submit]").html("Ubah");
            $.get("{{route("admin.siswa.api.show")}}/" + $(this).data("id"), function (s) {
                if (s.status == 1) {
                    $("#formSubmit input[name=nama]").val(s.data.nama);
                    $("#formSubmit input[name=nis]").val(s.data.nis);
                    $("#formSubmit input[name=nis]").attr("disabled", true);
                    $("#formSubmit input[name=no_hp]").val(s.data.no_hp);
                    $("#formSubmit input[name=email]").val(s.data.email);
                    $("#formSubmit input[name=password]").removeAttr('required');
                    $("#formSubmit textarea[name=alamat]").val(s.data.alamat);
                    $("#formSubmit select[name=jk]").val(s.data.jk);
                    if (s.data.kelas_id != null) {
                        $("#formSubmit select[name=kelas_id]").val(s.data.kelas_id);
                    }
                    $("#formSubmit select[name=jk]").trigger('change');
                    $("#formSubmit select[name=kelas_id]").trigger('change');
                } else {
                    toastr.warning("Data Kelas Tidak Ditemukan");
                }
            }).fail(function () {
                toastr.warning("Anda Terputus Dengan Server");
            });
            $("#formSubmit button[type=submit]").attr("class", "btn btn-warning");
            $("#formSubmit").show();
        });
        $("#formSubmit").on('submit', function (event) {
            event.preventDefault();
            var form = $(this)[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
            data = formData;
            if (url == null) {
                url = "{{route("admin.siswa.api.add")}}";
            }
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data
            })
                .done(function (r) {
                    if (r.status == 1) {
                        toastr.success("Data Tersimpan")
                    } else {
                        toastr.warning("Data Gagal Di Simpan")
                    }
                })
                .fail(function () {
                    toastr.warning("Anda Terputus Dengan Server");
                })
                .always(function () {
                    $("#formSubmit")[0].reset();
                    $("#formSubmit").hide();
                    dtable.ajax.reload();
                    url = null;
                    $("#formSubmit").html(temp);
                    $("#formSubmit").hide();
                });

        });

        $("#formPassword").on("click", function () {
            if (toggle1) {
                $("#bulk_excel").show();
                toggle1 = false;
            } else {
                $("#bulk_excel").hide();
                toggle1 = true;
            }
        });
        $("#bulk_excel").on("submit", function () {
            var form = $(this)[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
            data = formData;
            if (url == null) {
                url = "{{route("admin.siswa.api.bulkpassword")}}";
            }
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data
            })
                .done(function (r) {
                    if (r.status == 1) {
                        toastr.success("Data Tersimpan")
                    } else {
                        toastr.warning("Data Gagal Di Simpan")
                    }
                })
                .fail(function () {
                    toastr.warning("Anda Terputus Dengan Server");
                })
                .always(function () {
                    $("#bulk_excel")[0].reset();
                    $("#bulk_excel").hide();
                    dtable.ajax.reload();
                    url = null;
                    $("#bulk_excel").html(temp1);
                    $("#bulk_excel").hide();
                });
        });
    </script>
@endsection
