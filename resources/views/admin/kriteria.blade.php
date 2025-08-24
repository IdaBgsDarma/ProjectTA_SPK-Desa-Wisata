@extends('admin.layouts.layouts')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Form Kriteria</h5>
        <div class="card">
            <div class="card-body">
                <form id="form-addkriteria">
                    <div class="mb-3">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Kriteria">
                        <span class="text-danger error-text nama_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Bobot</label>
                        <input type="text" name="bobot" class="form-control" placeholder="Bobot">
                        <span class="text-danger error-text bobot_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Cost / Benefit</label>
                        <select name="cost_benefit" class="form-control" placeholder="Cost / Benefit">
                            <option value="">Pilih cost / benefit</option>
                            <option value="Cost">Cost</option>
                            <option value="Benefit">Benefit</option>
                        </select>
                        <span class="text-danger error-text cost_benefit_error"></span>
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
                    <button type="button" class="btn btn-danger" id="btn_cancelkriteria">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Table Kriteria</h5>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle" id="table-kriteria">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama Kriteria</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Bobot</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Cost / Benefit</h6>
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
        $(function () {
            var table = $('#table-kriteria').DataTable({
                processing: false,
                serverSide: false,
                ajax: "/admin/kriteria",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'bobot',
                        name: 'bobot'
                    },
                    {
                        data: 'cost_benefit',
                        name: 'cost_benefit'
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
        $('#btn_cancelkriteria').on('click', function () {
            $("#form-editkriteria").prop('id', 'form-addkriteria');
            $('#form-addkriteria').trigger("reset");
            $('#form-addkriteria').find('input[name="id"]').remove();
        });
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-addkriteria', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/kriteria',
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
                            $('#btn_cancelkriteria').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil ditambahkan!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-kriteria').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-kriteria').on('click','#btn_edit',function(){
            var id = $(this).data('id');
            var url = '/admin/kriteria';
            $("#form-addkriteria").prop('id','form-editkriteria');
            $("#form-editkriteria :input").prop("disabled", true);
            $('#form-editkriteria').find('input[name="id"]').remove();
            $('.btn_submit').prop('disabled', true);
            $.get(url, {
                id: id
            }, function (data) {
                $('#form-editkriteria').append('<input name="id" hidden readonly>');
                $('body').find('form').find('input[name="id"]').val(data.result.id);
                $('body').find('form').find('input[name="nama"]').val(data.result.nama);
                $('body').find('form').find('input[name="bobot"]').val(data.result.bobot);
                $('select[name="cost_benefit"]').val(data.result.cost_benefit).trigger('change');
                $('select[name="status"]').val(data.result.status).trigger('change');
            }, 'json').done(function(){
                $("#form-editkriteria :input").prop("disabled", false);
                $('.btn_submit').prop('disabled', false);
            })
        })
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-editkriteria', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                let formData = new FormData(form);
                formData.append('_method', 'PATCH');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/kriteria',
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
                            $('#btn_cancelkriteria').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil diubah!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-kriteria').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-kriteria').on('click', '#btn_delete', function() {
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
                        url: '/admin/kriteria',
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
                                $('#btn_cancelkriteria').trigger('click');
                                $('#table-kriteria').DataTable().ajax.reload(null, false);
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
