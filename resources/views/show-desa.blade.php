@extends('layouts.layouts')
@section('content')
    <div class="about_section layout_padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="about_taital_main">
                        <h1 class="about_taital">{{ $desa->nama }}</h1>
                        <p class="about_text">{{ $desa->lokasi }}</p>
                    </div>
                </div>
                <div class="col-md-6 padding_right_0">
                    <div><img src="/assets/images/desa/{{ $desa->foto }}" height="500" width="500"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
