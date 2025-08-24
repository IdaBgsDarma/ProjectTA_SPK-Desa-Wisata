@extends('layouts.layouts')
@section('content')
    <div class="services_section layout_padding">
        <div class="container">
            <p class="services_text1 center-text">Desa</p>
            <div class="services_section_2">
                <div class="row" id="data">
                    @foreach ($desa as $data_desa)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    {{ $data_desa->nama }}
                                </div>
                                <div class="card-body">
                                    <img src="/assets/images/desa/{{ $data_desa->foto }}" class="card-logo" style="width:300px;height:168px">
                                    <p class="card-text">Klik untuk melihat desa di Desa
                                    <h1></h1>
                                    </p>
                                    <a href="{{ route('show.desa', $data_desa->slug) }}" class="button-13"
                                        role="button">Buka</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
