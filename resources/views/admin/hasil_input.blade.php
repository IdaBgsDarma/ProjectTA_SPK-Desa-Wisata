@extends('admin.layouts.layouts')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Hasil SAW</h4>
                    <form class="forms-sample" action="{{ route('admin.hasil.index') }}" id="form-hasil" method="POST">
                        @csrf
                        @for ($i = 0; $i < count($kriteria); $i++)
                        <div class="form-group">
                            <label>Crips untuk {{ $kriteria[$i]->nama }} ( {{ $kriteria[$i]->bobot }} )</label>
                            <select name="kepentingan[{{$i}}][]" class="form-control js-select2" multiple="multiple">
                                @foreach ($crips as $data_crips)
                                    @if ($data_crips->id_kriteria == $kriteria[$i]->id)
                                        <option value="{{ $data_crips->id }}">{{ $data_crips->nama }} ( {{ $data_crips->bobot }} )</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="text-danger error-text {{ $kriteria[$i]->id }}_error"></span>
                        </div>
                        @endfor
                        <br>
                        <button type="submit" class="btn btn-primary mr-2 btn_submit">Submit</button>
                        <button type="button" class="btn btn-light" id="btn_cancelkriteria">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('.js-select2').select2({
            placeholder: 'Pilih Crips',
        });
    });
</script>

<script>
    $(function() {
        $('body').on('submit', '#form-hasil', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/input/hasil',
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
                        $(form).find('span.' + data.kriteria + '_error').text("Bobot terlalu banyak!");
                    } else {
                        $("#form-hasil").trigger("submit");
                        e.currentTarget.submit();
                    }
                }
            });
        });
    })
</script>
@endpush
@endsection
