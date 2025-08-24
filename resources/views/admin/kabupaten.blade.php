@extends('admin.layouts.layouts')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Form Kabupaten</h5>
        <div class="card">
            <div class="card-body">
                <form id="form-addkabupaten">
                    <div class="mb-3">
                        <label>Nama Kabupaten</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Kabupaten">
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
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn_submit">Submit</button>
                    <button type="button" class="btn btn-danger" id="btn_cancelkabupaten">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Table Kabupaten</h5>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle" id="table-kabupaten">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama Kabupaten</h6>
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
            var table = $('#table-kabupaten').DataTable({
                processing: false,
                serverSide: false,
                ajax: "/admin/kabupaten",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
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
        $('#btn_cancelkabupaten').on('click', function () {
            $('#preview-foto').hide();
            $("#form-editkabupaten").prop('id', 'form-addkabupaten');
            $('#form-addkabupaten').trigger("reset");
            $('#form-addkabupaten').find('input[name="id"]').remove();
        });
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-addkabupaten', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/kabupaten',
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
                            $('#btn_cancelkabupaten').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil ditambahkan!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-kabupaten').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-kabupaten').on('click','#btn_edit',function(){
            var id = $(this).data('id');
            var url = '/admin/kabupaten';
            $("#form-addkabupaten").prop('id','form-editkabupaten');
            $("#form-editkabupaten :input").prop("disabled", true);
            $('#form-editkabupaten').find('input[name="id"]').remove();
            $('.btn_submit').prop('disabled', true);
            $.get(url, {
                id: id
            }, function (data) {
                $('#form-editkabupaten').append('<input name="id" hidden readonly>');
                $('body').find('form').find('input[name="id"]').val(data.result.id);
                $('body').find('form').find('input[name="nama"]').val(data.result.nama);
                $('select[name="status"]').val(data.result.status).trigger('change');
                $('#preview-foto').show();
                $('#preview-foto').attr('src','/assets/images/kabupaten/'+data.result.foto);
            }, 'json').done(function(){
                $("#form-editkabupaten :input").prop("disabled", false);
                $('.btn_submit').prop('disabled', false);
            })
        })
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-editkabupaten', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                let formData = new FormData(form);
                formData.append('_method', 'PATCH');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/kabupaten',
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
                            $('#btn_cancelkabupaten').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil diubah!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-kabupaten').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-kabupaten').on('click', '#btn_delete', function() {
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
                        url: '/admin/kabupaten',
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
                                $('#btn_cancelkabupaten').trigger('click');
                                $('#table-kabupaten').DataTable().ajax.reload(null, false);
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
