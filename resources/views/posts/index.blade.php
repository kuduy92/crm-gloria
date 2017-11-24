<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.jpg') }}">

    <!-- CSFR token for ajax call -->
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>PT. GLORIA ANUGERAH SEJAHTERA</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    {{-- <link rel="styleeheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}

    <!-- icheck checkboxes -->
    <link rel="stylesheet" href="{{ asset('icheck/square/yellow.css') }}">
    {{-- <link rel="stylesheet" href="https://raw.githubusercontent.com/fronteed/icheck/1.x/skins/square/yellow.css"> --}}

    <!-- toastr notifications -->
    {{-- <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}"> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .panel-heading {
            padding: 0;
        }
        .panel-heading ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .panel-heading li {
            float: left;
            border-right:1px solid #bbb;
            display: block;
            padding: 14px 16px;
            text-align: center;
        }
        .panel-heading li:last-child:hover {
            background-color: #ccc;
        }
        .panel-heading li:last-child {
            border-right: none;
        }
        .panel-heading li a:hover {
            text-decoration: none;
        }

        .table.table-bordered tbody td {
            vertical-align: baseline;
        }
    </style>

</head>

<body>
    <div class="col-md-12 col-md-offset-0">
        <h2 class="text-center">Customer Relationship Management System</h2>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul>
                    <li><i class="fa fa-file-text-o"></i> Data Customer</li>
                    <a href="#" class="add-modal"><li>Tambah Customer</li></a>
                </ul>
            </div>

            <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="postTable" style="visibility: hidden;">
                        <thead>
                            <tr>
                                <th valign="middle">No.</th>
                                <th>Nama</th>
                                <th>No. Hp</th>
                                <th>Produk</th>
                                <th>Tanggal Beli</th>
                                <th>Konter</th>
                                <th>Ceklis</th>
                                <th>Terakhir Diupdate</th>
                                <th>Aksi</th>
                            </tr>
                            {{ csrf_field() }}
                        </thead>
                        <tbody>
                            @foreach($posts as $indexKey => $post)
                                <tr class="item{{$post->id}} @if($post->is_published) warning @endif">
                                    <td class="col1">{{ $indexKey+1 }}</td>
                                    <td>{{$post->nama}}</td>
                                    <td>
                                        {{App\Post::getExcerpt($post->no_hp)}}
                                    </td>
                                    <td>{{$post->produk}}</td>
                                    <td>{{$post->tgl_beli}}</td>
                                    <td>{{$post->konter}}</td>
                                    <td class="text-center"><input type="checkbox" class="published" id="" data-id="{{$post->id}}" @if ($post->is_published) checked @endif></td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $post->updated_at)->diffForHumans() }}</td>
                                    <td>
                                        <button class="show-modal btn btn-success" data-id="{{$post->id}}" data-nama="{{$post->nama}}" data-no_hp="{{$post->no_hp}}" data-produk="{{$post->produk}}" data-tgl_beli="{{$post->tgl_beli}}" data-konter="{{$post->konter}}">
                                        <span class="glyphicon glyphicon-eye-open"></span> Tampilkan</button>
                                        <button class="edit-modal btn btn-info" data-id="{{$post->id}}" data-nama="{{$post->nama}}" data-no_hp="{{$post->no_hp}}" data-produk="{{$post->produk}}" data-tgl_beli="{{$post->tgl_beli}}" data-konter="{{$post->konter}}">
                                        <span class="glyphicon glyphicon-edit"></span> Edit</button>
                                        <button class="delete-modal btn btn-danger" data-id="{{$post->id}}" data-nama="{{$post->nama}}" data-no_hp="{{$post->no_hp}}" data-produk="{{$post->produk}}" data-tgl_beli="{{$post->tgl_beli}}" data-konter="{{$post->konter}}">
                                        <span class="glyphicon glyphicon-trash"></span> Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel .xls</button></a>
                    <a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel .xlsx</button></a>
                    <a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download .csv</button></a>
            </div><!-- /.panel-body -->
        </div><!-- /.panel panel-default -->
        <form style="border: 1px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
        <input type="file" name="import_file" />
        <button class="btn btn-primary">Import File</button>
        </form>
    </div><!-- /.col-md-8 -->

    <!-- Modal form to add a post -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nama">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_add" autofocus>
                                <p class="errorNama text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="no_hp">No. Hp</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="no_hp_add" autofocus>
                                <p class="errorNoHp text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="produk">Produk</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="produk_add" autofocus>
                                <p class="errorProduk text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="tgl_beli">Tgl. Beli</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tgl_beli_add" autofocus>
                                <p class="errorTglBeli text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="konter">Konter</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="konter_add" autofocus>
                                <p class="errorKonter text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Tambah
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to show a post -->
    <div id="showModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="id">No.</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_show" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nama">Nama</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="nama_show" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="no_hp">No. Hp</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="no_hp_show" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="produk">Produk</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="produk_show" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="tgl_beli">Tgl. beli</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="tgl_beli_show" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="konter">Konter</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="konter_show" disabled>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to edit a form -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="id">No.</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_edit" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nama">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_edit" autofocus>
                                <p class="errorNama text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="no_hp">No. Hp</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="no_hp_edit" autofocus>
                                <p class="errorNoHp text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="produk">Produk</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="produk_edit" autofocus>
                                <p class="errorProduk text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="tgl_beli">Tgl. Beli</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tgl_beli_edit" autofocus>
                                <p class="errorTglBeli text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="konter">Konter</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="konter_edit" autofocus>
                                <p class="errorKonter text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Edit
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to delete a form -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menghapus data tersebut?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="id">No.</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_delete" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nama">Nama</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="nama_delete" disabled>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Hapus
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    {{-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <!-- Bootstrap JavaScript -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>

    <!-- toastr notifications -->
    {{-- <script type="text/javascript" src="{{ asset('toastr/toastr.min.js') }}"></script> --}}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- icheck checkboxes -->
    <script type="text/javascript" src="{{ asset('icheck/icheck.min.js') }}"></script>

    <!-- Delay table load until everything else is loaded -->
    <script>
        $(window).load(function(){
            $('#postTable').removeAttr('style');
        })
    </script>

    <script>
        $(document).ready(function(){
            $('.published').iCheck({
                checkboxClass: 'icheckbox_square-yellow',
                radioClass: 'iradio_square-yellow',
                increaseArea: '20%'
            });
            $('.published').on('ifClicked', function(event){
                id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: "{{ URL::route('changeStatus') }}",
                    data: {
                        '_token': $('input[name=_token]').val(),
                        'id': id
                    },
                    success: function(data) {
                        // empty
                    },
                });
            });
            $('.published').on('ifToggled', function(event) {
                $(this).closest('tr').toggleClass('warning');
            });
        });

    </script>

    <!-- AJAX CRUD operations -->
    <script type="text/javascript">
        // add a new post
        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Tambah Customer');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function() {
            $.ajax({
                type: 'POST',
                url: 'posts',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'nama': $('#nama_add').val(),
                    'no_hp': $('#no_hp_add').val(),
                    'produk': $('#produk_add').val(),
                    'tgl_beli': $('#tgl_beli_add').val(),
                    'konter': $('#konter_add').val()
                },
                success: function(data) {
                    $('.errorNama').addClass('hidden');
                    $('.errorNoHp').addClass('hidden');
                    $('.errorProduk').addClass('hidden');
                    $('.errorTglBeli').addClass('hidden');
                    $('.errorKonter').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.nama) {
                            $('.errorNama').removeClass('hidden');
                            $('.errorNama').text(data.errors.nama);
                        }
                        if (data.errors.no_hp) {
                            $('.errorNoHp').removeClass('hidden');
                            $('.errorNoHp').text(data.errors.no_hp);
                        }
                        if (data.errors.produk) {
                            $('.errorProduk').removeClass('hidden');
                            $('.errorProduk').text(data.errors.produk);
                        }
                        if (data.errors.tgl_beli) {
                            $('.errorTglBeli').removeClass('hidden');
                            $('.errorTglBeli').text(data.errors.tgl_beli);
                        }
                        if (data.errors.konter) {
                            $('.errorKonter').removeClass('hidden');
                            $('.errorKonter').text(data.errors.konter);
                        }
                    } else {
                        toastr.success('Berhasil menambahkan customer!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class='item" + data.id + "'><td class='col1'>" + data.id + "</td><td>" + data.nama + "</td><td>" + data.no_hp + "</td><td>" + data.produk + "</td><td>" + data.tgl_beli + "</td><td>" + data.konter + "</td><td class='text-center'><input type='checkbox' class='new_published' data-id='" + data.id + " '></td><td>Just now!</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-nama='" + data.nama + "' data-no_hp='" + data.no_hp + "' data-produk='" + data.produk + "' data-tgl_beli='" + data.tgl_beli + "' data-konter='" + data.konter + "'><span class='glyphicon glyphicon-eye-open'></span> Tampilkan</button> <button class='edit-modal btn btn-info' data-id='" + data.id + "' data-nama='" + data.nama + "' data-no_hp='" + data.no_hp + "' data-produk='" + data.produk + "' data-tgl_beli='" + data.tgl_beli + "' data-konter='" + data.konter + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-nama='" + data.nama + "' data-no_hp='" + data.no_hp + "' data-produk='" + data.produk + "' data-tgl_beli='" + data.tgl_beli + "' data-konter='" + data.konter + "'><span class='glyphicon glyphicon-trash'></span> Hapus</button></td></tr>");
                        $('.new_published').iCheck({
                            checkboxClass: 'icheckbox_square-yellow',
                            radioClass: 'iradio_square-yellow',
                            increaseArea: '20%'
                        });
                        $('.new_published').on('ifToggled', function(event){
                            $(this).closest('tr').toggleClass('warning');
                        });
                        $('.new_published').on('ifChanged', function(event){
                            id = $(this).data('id');
                            $.ajax({
                                type: 'POST',
                                url: "{{ URL::route('changeStatus') }}",
                                data: {
                                    '_token': $('input[name=_token]').val(),
                                    'id': id
                                },
                                success: function(data) {
                                    // empty
                                },
                            });
                        });
                        $('.col1').each(function (index) {
                            $(this).html(index+1);
                        });
                    }
                },
            });
        });

        // Show a post
        $(document).on('click', '.show-modal', function() {
            $('.modal-title').text('Tampilkan');
            $('#id_show').val($(this).data('id'));
            $('#nama_show').val($(this).data('nama'));
            $('#no_hp_show').val($(this).data('no_hp'));
            $('#produk_show').val($(this).data('produk'));
            $('#tgl_beli_show').val($(this).data('tgl_beli'));
            $('#konter_show').val($(this).data('konter'));
            $('#showModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Data Customer');
            $('#id_edit').val($(this).data('id'));
            $('#nama_edit').val($(this).data('nama'));
            $('#no_hp_edit').val($(this).data('no_hp'));
            $('#produk_edit').val($(this).data('produk'));
            $('#tgl_beli_edit').val($(this).data('tgl_beli'));
            $('#konter').val($(this).data('konter'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            $.ajax({
                type: 'PUT',
                url: 'posts/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'nama': $('#nama_edit').val(),
                    'no_hp': $('#no_hp_edit').val(),
                    'produk': $('#produk_edit').val(),
                    'tgl_beli': $('#tgl_beli_edit').val(),
                    'konter': $('#konter_edit').val()
                },
                success: function(data) {
                    $('.errorNama').addClass('hidden');
                    $('.errorNoHp').addClass('hidden');
                    $('.errorProduk').addClass('hidden');
                    $('.errorTglBeli').addClass('hidden');
                    $('.errorKonter').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.nama) {
                            $('.errorNama').removeClass('hidden');
                            $('.errorNama').text(data.errors.nama);
                        }
                        if (data.errors.no_hp) {
                            $('.errorNoHp').removeClass('hidden');
                            $('.errorNoHp').text(data.errors.no_hp);
                        }
                        if (data.errors.produk) {
                            $('.errorProduk').removeClass('hidden');
                            $('.errorProduk').text(data.errors.produk);
                        }
                        if (data.errors.tgl_beli) {
                            $('.errorTglBeli').removeClass('hidden');
                            $('.errorTglBeli').text(data.errors.tgl_beli);
                        }
                        if (data.errors.konter) {
                            $('.errorKonter').removeClass('hidden');
                            $('.errorKonter').text(data.errors.konter);
                        }
                    } else {
                        toastr.success('Berhasil meng-update data customer!', 'Success Alert', {timeOut: 5000});
                        $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td class='col1'>" + data.id + "</td><td>" + data.nama + "</td><td>" + data.no_hp + "</td><td>" + data.produk + "</td><td>" + data.tgl_beli + "</td><td>" + data.konter + "</td><td class='text-center'><input type='checkbox' class='edit_published' data-id='" + data.id + "'></td><td>Right now</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-nama='" + data.nama + "' data-no_hp='" + data.no_hp + "' data-produk='" + data.produk + "' data-tgl_beli='" + data.tgl_beli + "' data-konter='" + data.konter + "'><span class='glyphicon glyphicon-eye-open'></span> Tampilkan</button> <button class='edit-modal btn btn-info' data-id='" + data.id + "' data-nama='" + data.nama + "' data-no_hp='" + data.no_hp + "' data-produk='" + data.produk + "' data-tgl_beli='" + data.tgl_beli + "' data-konter='" + data.konter + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-nama='" + data.nama + "' data-no_hp='" + data.no_hp + "' data-produk='" + data.produk + "' data-Tgl_beli='" + data.tgl_beli + "' data-konter='" + data.konter + "'><span class='glyphicon glyphicon-trash'></span> Hapus</button></td></tr>");

                        if (data.is_published) {
                            $('.edit_published').prop('checked', true);
                            $('.edit_published').closest('tr').addClass('warning');
                        }
                        $('.edit_published').iCheck({
                            checkboxClass: 'icheckbox_square-yellow',
                            radioClass: 'iradio_square-yellow',
                            increaseArea: '20%'
                        });
                        $('.edit_published').on('ifToggled', function(event) {
                            $(this).closest('tr').toggleClass('warning');
                        });
                        $('.edit_published').on('ifChanged', function(event){
                            id = $(this).data('id');
                            $.ajax({
                                type: 'POST',
                                url: "{{ URL::route('changeStatus') }}",
                                data: {
                                    '_token': $('input[name=_token]').val(),
                                    'id': id
                                },
                                success: function(data) {
                                    // empty
                                },
                            });
                        });
                        $('.col1').each(function (index) {
                            $(this).html(index+1);
                        });
                    }
                }
            });
        });

        // delete a post
        $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Hapus Customer');
            $('#id_delete').val($(this).data('id'));
            $('#nama_delete').val($(this).data('nama'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function() {
            $.ajax({
                type: 'DELETE',
                url: 'posts/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function(data) {
                    toastr.success('Berhasil menghapus data customer!', 'Success Alert', {timeOut: 5000});
                    $('.item' + data['id']).remove();
                    $('.col1').each(function (index) {
                        $(this).html(index+1);
                    });
                }
            });
        });
    </script>

</body>
</html>
