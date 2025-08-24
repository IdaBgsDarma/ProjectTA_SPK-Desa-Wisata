@extends('admin.layouts.layouts')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Form Desa</h5>
        <div class="card">
            <div class="card-body">
                <form id="form-adddesa">
                    <div class="mb-3">
                        <label>Kabupaten</label>
                        <select name="id_kabupaten" class="form-control js-select2" placeholder="Kabupaten">
                            <option value="">Pilih Kabupaten</option>
                            @foreach ($kabupaten as $data_kabupaten)
                                <option value="{{ $data_kabupaten->id}}">{{ $data_kabupaten->nama }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text id_kabupaten_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Nama Desa</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Desa">
                        <span class="text-danger error-text nama_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="foto" id="foto">
                        <img id="preview-foto" style="width:200px;height:150px;display:none">
                        <br>
                        <small>*Ekstensi yang diperbolehkan adalah png.</small>
                        <br>
                        <small>*Ukuran foto maksimal 128kb</small>
                        <br>
                        <span class="text-danger error-text foto_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Lokasi">
                        <span class="text-danger error-text lokasi_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn_submit">Submit</button>
                    <button type="button" class="btn btn-danger" id="btn_canceldesa">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Table Desa</h5>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle" id="table-desa">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Kabupaten</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama Desa</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Lokasi</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Status</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Aksi</h6>
                                </th>
                            </tr>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>

    <script>
        $(document).ready(function (e) {
            $('body').on('change', '#foto', function(e) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-foto').show();
                    $('#preview-foto').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

        });
    </script>

    <script>
        $(function () {
            var table = $('#table-desa').DataTable({
                processing: false,
                serverSide: false,
                ajax: "/admin/desa",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'kabupaten.nama',
                        name: 'kabupaten.nama'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

    <script>
        $('#btn_canceldesa').on('click', function () {
            $('#preview-foto').hide();
            $('.js-select2').val('').trigger('change');
            $("#form-editdesa").prop('id', 'form-adddesa');
            $('#form-adddesa').trigger("reset");
            $('#form-adddesa').find('input[name="id"]').remove();
        });
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-adddesa', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/desa',
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },

                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                                $('.btn_submit').prop('disabled', false);
                            });
                        } else {
                            $('#btn_canceldesa').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil ditambahkan!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-desa').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-desa').on('click','#btn_edit',function(){
            var id = $(this).data('id');
            var url = '/admin/desa';
            $("#form-adddesa").prop('id','form-editdesa');
            $("#form-editdesa :input").prop("disabled", true);
            $('#form-editdesa').find('input[name="id"]').remove();
            $('.btn_submit').prop('disabled', true);
            $.get(url, {
                id: id
            }, function (data) {
                $('#form-editdesa').append('<input name="id" hidden readonly>');
                $('body').find('form').find('input[name="id"]').val(data.result.id);
                $('select[name="id_kabupaten"]').val(data.result.id_kabupaten).trigger('change');
                $('body').find('form').find('input[name="nama"]').val(data.result.nama);
                $('body').find('form').find('input[name="lokasi"]').val(data.result.lokasi);
                $('select[name="status"]').val(data.result.status).trigger('change');
                $('#preview-foto').show();
                $('#preview-foto').attr('src','/assets/images/desa/'+data.result.foto);
            }, 'json').done(function(){
                $("#form-editdesa :input").prop("disabled", false);
                $('.btn_submit').prop('disabled', false);
            })
        })
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-editdesa', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                let formData = new FormData(form);
                formData.append('_method', 'PATCH');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/desa',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },

                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                                $('.btn_submit').prop('disabled', false);
                            });
                        } else {
                            $('#btn_canceldesa').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil diubah!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-desa').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-desa').on('click', '#btn_delete', function() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak akan bisa kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).data('id');
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "DELETE",
                        url: '/admin/desa',
                        data: {
                            id: id
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data.code == 1) {
                                Swal.fire(
                                    'Deleted!',
                                    'Data berhasil dihapus!',
                                    'success'
                                )
                                $('#btn_canceldesa').trigger('click');
                                $('#table-desa').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan!',
                                    'error'
                                )
                            }
                        }
                    });
                }
            })
        });
    </script>
@endpush
