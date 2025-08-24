@extends('admin.layouts.layouts')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Form Nilai</h5>
        <div class="card">
            <div class="card-body">
                <form id="form-addnilai">
                    <div class="mb-3">
                        <label>Alternatif</label>
                        <select name="id_alternatif" class="form-control js-select2" placeholder="Alternatif">
                            <option value="">Pilih Alternatif</option>
                            @foreach ($alternatif as $data_alternatif)
                                <option value="{{ $data_alternatif->id}}">{{ $data_alternatif->nama }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text id_alternatif_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Kriteria</label>
                        <select name="id_kriteria" class="form-control js-select2" placeholder="Kriteria">
                            <option value="">Pilih Kriteria</option>
                            @foreach ($kriteria as $data_kriteria)
                                <option value="{{ $data_kriteria->id}}">{{ $data_kriteria->nama }} ({{ $data_kriteria->bobot }})</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text id_kriteria_error"></span>
                    </div>
                    <div class="mb-3">
                        <label>Crips</label>
                        <select name="id_crips[]" class="form-control js-select2" placeholder="Crips" id="crips" multiple="multiple">
                        </select>
                        <span class="text-danger error-text id_crips_error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn_submit">Submit</button>
                    <button type="button" class="btn btn-danger" id="btn_cancelnilai">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Table Nilai</h5>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle" id="table-nilai">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Alternatif</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Kriteria</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Crips</h6>
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
        $('select[name="id_kriteria"]').change(function (e) {
            $('#crips').empty();
            var id_kriteria = this.value;
            $.get('/admin/nilai', {
                id_kriteria: id_kriteria
            }, function (data) {
                data.result.forEach(element => {
                    $('#crips').append('<option value="'+element.id+'">'+element.nama+' ('+element.bobot+')</option>')
                });
            }, 'json')
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>
    <script>
        $(function () {
            var table = $('#table-nilai').DataTable({
                processing: false,
                serverSide: false,
                ajax: "/admin/nilai",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'alternatif.nama',
                        name: 'alternatif.nama'
                    },
                    {
                        data: 'kriteria.nama',
                        name: 'kriteria.nama'
                    },
                    {
                        data: 'crips.nama',
                        name: 'crips.nama'
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
        $('#btn_cancelnilai').on('click', function () {
            $('.js-select2').val('').trigger('change');
            $("#form-editnilai").prop('id', 'form-addnilai');
            $('#form-addnilai').trigger("reset");
            $('#form-addnilai').find('input[name="id"]').remove();
        });
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-addnilai', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/nilai',
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
                        } else if(data.code == 2) {
                            $(form).find('span.' + 'id_crips_error').text("Bobot melebihi bobot kriteria!");
                            $('.btn_submit').prop('disabled', false);
                        } else {
                            $('#btn_cancelnilai').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil ditambahkan!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-nilai').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-nilai').on('click','#btn_edit',function(){
            var id = $(this).data('id');
            var url = '/admin/nilai';
            $("#form-addnilai").prop('id','form-editnilai');
            $("#form-editnilai :input").prop("disabled", true);
            $('#form-editnilai').find('input[name="id"]').remove();
            $('.btn_submit').prop('disabled', true);
            $.get(url, {
                id: id
            }, function (data) {
                $('#form-editnilai').append('<input name="id" hidden readonly>');
                $('body').find('form').find('input[name="id"]').val(data.result.id);
                $('select[name="id_alternatif"]').val(data.result.id_alternatif).trigger('change');
                $('select[name="id_kriteria"]').val(data.result.id_kriteria).trigger('change');
                $('select[name="id_crips"]').val(data.result.id_crips).trigger('change');
            }, 'json').done(function(){
                $("#form-editnilai :input").prop("disabled", false);
                $('.btn_submit').prop('disabled', false);
            })
        })
    </script>

    <script>
        $(function() {
            $('body').on('submit', '#form-editnilai', function(e) {
                e.preventDefault();
                $('.btn_submit').prop('disabled', true);

                var form = this;
                let formData = new FormData(form);
                formData.append('_method', 'PATCH');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/nilai',
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
                            $('#btn_cancelnilai').trigger("click");
                            Swal.fire(
                                'Success!',
                                'Data berhasil diubah!',
                                'success'
                            )
                            $('.btn_submit').prop('disabled', false);
                            $('#table-nilai').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            });
        })
    </script>

    <script>
        $('#table-nilai').on('click', '#btn_delete', function() {
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
                        url: '/admin/nilai',
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
                                $('#btn_cancelnilai').trigger('click');
                                $('#table-nilai').DataTable().ajax.reload(null, false);
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
