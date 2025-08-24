@foreach ($kabupaten as $data_kabupaten)
<div class="col-md-4">
    <div class="card">
        <div class="card-header text-center">
            {{ $data_kabupaten->nama }}
        </div>
        <div class="card-body">
            <img src="/assets/images/kabupaten/{{ $data_kabupaten->foto }}" class="card-logo">
            <p class="card-text">Klik untuk melihat desa di Kabupaten <h1></h1></p>
            <a href="{{ route('show.kabupaten',$data_kabupaten->slug) }}" class="button-13" role="button">Buka</a>
        </div>
    </div>
</div>
@endforeach
