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
        <h5 class="m-0">Bank Soal</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <button type="button" id="showForm" class="btn btn-primary mb-4">
              Tambah Soal
            </button>
          </div>
          <form class="" onsubmit="return false" style="width:100%" enctype="multipart/form-data" id="form" method="post">
            <div class="row" >
              <div class="col-12 mb-3">
                <div class="form-group">
                  <label>Soal</label>
                  <textarea name="soal" class="form-control" rows="4" cols="40"></textarea>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="form-group">
                  <label>Mata Pelajaran</label>
                  <select class="form-control" name="matpel_id">
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
                  <label>Judul Soal</label>
                  <input type="text" class="form-control" name="judul" value="">
                </div>
                <div class="form-group">
                  <label>Jenis Soal</label>
                  <select class="form-control" name="jenis">
                    <option value="">== Pilih == </option>
                    <option value="pg">Pilihan Ganda</option>
                    <option value="es">Essay</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Nilai per Butir</label>
                  <input type="text" class="form-control" name="poin" placeholder="Kosongkan Untuk Meratakan NILAI" value="">
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-success">
                    Simpan Soal
                  </button>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="form-group" id="pilihan">

                </div>
              </div>
            </div>
          </form>
          <div style="display:none" id="pg">
            <div class="form-group">
              <label>Jawaban A </label>
              <textarea name="pg_a" rows="2" class="form-control" cols="20"></textarea>
            </div>
            <div class="form-group">
              <label>Jawaban B </label>
              <textarea name="pg_b" rows="2" class="form-control" cols="20"></textarea>
            </div>
            <div class="form-group">
              <label>Jawaban C </label>
              <textarea name="pg_c" rows="2" class="form-control" cols="20"></textarea>
            </div>

            <div class="form-group">
              <label>Kunci Jawaban </label>
              <input type="text" name="jawaban_pg" class="form-control" >
            </div>

          </div>
          <div id="es" style="display:none">
            <div class="form-group">
              <label>Jawaban Essay</label>
              <textarea name="jawaban_es" rows="8" class="form-control" cols="80"></textarea>
            </div>
          </div>
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-bordered" id="dtable">
                <thead>
                  <th>No</th>
                  <th>Matpel</th>
                  <th>Judul</th>
                  <th>Jenis</th>
                  <th>Jawaban</th>
                  <th>Poin</th>
                  <th>Dibuat</th>
                  <th>Opsi</th>
                </thead>
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
    var mathElements = [
        'math',
        'maction',
        'maligngroup',
        'malignmark',
        'menclose',
        'merror',
        'mfenced',
        'mfrac',
        'mglyph',
        'mi',
        'mlabeledtr',
        'mlongdiv',
        'mmultiscripts',
        'mn',
        'mo',
        'mover',
        'mpadded',
        'mphantom',
        'mroot',
        'mrow',
        'ms',
        'mscarries',
        'mscarry',
        'msgroup',
        'msline',
        'mspace',
        'msqrt',
        'msrow',
        'mstack',
        'mstyle',
        'msub',
        'msup',
        'msubsup',
        'mtable',
        'mtd',
        'mtext',
        'mtr',
        'munder',
        'munderover',
        'semantics',
        'annotation',
        'annotation-xml'
      ];
    CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://ckeditor.com/docs/ckeditor4/4.13.0/examples/assets/plugins/ckeditor_wiris/', 'plugin.js');
    CKEDITOR.replace('soal', {
        extraPlugins: 'ckeditor_wiris',
        filebrowserUploadUrl: "{{route('guru.banksoal.api.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
       extraAllowedContent: mathElements.join(' ') + '(*)[*]{*};img[data-mathml,data-custom-editor,role](Wirisformula)'
    });

    var dtable = $("#dtable").DataTable({
        ajax:"{{route("guru.banksoal.api.read")}}",
        createdRow:function(r,d,i){
          if (d[3] == "pg") {
            $("td",r).eq(3).html("Pilihan Ganda");
          }else {
            $("td",r).eq(3).html("Essay");
          }
          btn = '<button type="button" class="btn btn-primary view m-2" data-id="'+d[7]+'"><i class="fa fa-eye"></i></button>';
          $("td",r).eq(7).html(btn);
          btn = '<button type="button" class="btn btn-warning edit m-2" data-id="'+d[7]+'"><i class="fa fa-edit"></i></button>';
          $("td",r).eq(7).append(btn);

        }
    });
    $("#form").hide();
    $("#showForm").on('click', function(event) {
      event.preventDefault();
      $("#form").show();
    });
    var urlSubmit = "{{route("guru.banksoal.api.add")}}";
    $("#form").on('submit',function(event) {
      event.preventDefault();
      dform = $(this).serializeArray();
      dform[dform.length] = {name:"soal",value:CKEDITOR.instances['soal'].getData()};
      p = $("#form select[name=jenis]").val();
      if (p == "es") {
        dform[dform.length] = {name:"jawaban_es",value:CKEDITOR.instances['jawaban_es'].getData()};
      }else {
        dform[dform.length] = {name:"pg_a",value:CKEDITOR.instances['pg_a'].getData()};
        dform[dform.length] = {name:"pg_b",value:CKEDITOR.instances['pg_b'].getData()};
        dform[dform.length] = {name:"pg_c",value:CKEDITOR.instances['pg_c'].getData()};
      }
      console.log(dform);
      $.ajax({
        url: urlSubmit,
        type: 'POST',
        dataType: 'JSON',
        data: dform
      })
      .done(function(a) {
        if (a.status == 1) {
          toastr.success("Sukses Simpan Bank Soal");
        }else {
          toastr.warning("Gagal Simpan Bank Soal");
        }
        $("#form").hide();
      })
      .fail(function(b) {
        toastr.warning("Anda Terputus Dari Server");
      })
      .always(function(b) {
        dtable.ajax.reload();
        obj = $("#form");
        obj.find("button[type=submit]").attr("class","btn btn-success");
        obj.find("button[type=submit]").html("Simpan");
        obj[0].reset();
        urlSubmit = "{{route("guru.banksoal.api.add")}}";
        obj.hide();
      });

    });
    $pg = null;
    $es = null;
    $("#form select[name=jenis]").on('change', function(event) {
      event.preventDefault();
      ch = $(this).val();
      if (ch == "" || ch == null) {
        $("#pilihan").html("");
      }else if (ch == "pg") {
        $("#pilihan").html("");
        html = $("#pg").html();
        if ($pg != null) {
          html = $pg;
        }
        $pg = html;
        $("#pg").html("")
        console.log($pg);
        $("#pilihan").html(html);
        jaw = ["a","b","c"];
        for (var i = 0; i < jaw.length; i++) {
          CKEDITOR.replace('pg_'+jaw[i], {
            extraPlugins: 'ckeditor_wiris',
            filebrowserUploadUrl: "{{route('guru.banksoal.api.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            extraAllowedContent: mathElements.join(' ') + '(*)[*]{*};img[data-mathml,data-custom-editor,role](Wirisformula)',
          });
        }
      }else if (ch == "es") {
        $("#pilihan").html("");
        html = $("#es").html();
        if ($es != null) {
          html = $es;
        }
        $es = html;
        $("#es").html("");
        console.log(html);
        $("#pilihan").html($es);
        CKEDITOR.replace('jawaban_es', {
            extraPlugins: 'ckeditor_wiris',
            filebrowserUploadUrl: "{{route('guru.banksoal.api.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            extraAllowedContent: mathElements.join(' ') + '(*)[*]{*};img[data-mathml,data-custom-editor,role](Wirisformula)',
        });
      }else {
        $("#pilihan").html("");
      }
    });
    $("#dtable").on('click', '.view', function(event) {
      event.preventDefault();
      console.log("View Detail Soal");
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
    $("#dtable").on('click', '.edit', function(event) {
      event.preventDefault();
      console.log("Ubah Soal");
      d = $(this).data("id");
      console.log(d);
      tmp = $("#form").html();
      obj = $("#form");
      u = "{{route("guru.banksoal.api.view")}}/"+d;
      urlSubmit = "{{route("guru.banksoal.api.edit")}}/"+d;
      $.get(u,function(rs){
        if (rs.status == 1) {
          obj.show();
          obj.find("button[type=submit]").attr("class","btn btn-warning");
          obj.find("button[type=submit]").html("Update");
          obj.find("select[name=matpel_id]").val(rs.data.matpel_id).trigger('change');
          obj.find("select[name=jenis]").val(rs.data.jenis).trigger('change');
          obj.find("input[name=judul]").val(rs.data.judul);
          obj.find("input[name=poin]").val(rs.data.poin);
          CKEDITOR.instances['soal'].setData(rs.data.soal);
          if (rs.data.jenis == "pg") {
            CKEDITOR.instances['pg_a'].setData(rs.data.pg_a);
            CKEDITOR.instances['pg_b'].setData(rs.data.pg_b);
            CKEDITOR.instances['pg_c'].setData(rs.data.pg_c);
            obj.find("input[name=jawaban_pg]").val(rs.data.jawaban_pg);
          }else {
            CKEDITOR.instances['jawaban_es'].setData(rs.data.jawaban_es);
          }
        }else {
          toastr.warning("Data soal tidak ditemukan");
        }
      }).fail(function(){
        toastr.warning("Anda terputus dengan server");
      });
    });
  });
</script>
@endsection
