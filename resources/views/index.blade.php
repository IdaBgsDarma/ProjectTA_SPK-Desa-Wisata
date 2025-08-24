@extends('layouts.layouts')
@section('content')
    <!-- services section start -->
            {{-- <div class="services_section layout_padding">
                <div class="container">
                    <h1 class="services_taital">Services </h1>
                    <p class="services_text">There are many variations of passages of Lorem Ipsum available, but the majority
                        have
                        suffered alteration</p>
                    <div class="services_section_2">
                        <div class="row">
                            <div class="col-md-4">
                                <div><img src="/assets/images/img-1.png" class="services_img"></div>
                                <div class="btn_main"><a href="#">Rafting</a></div>
                            </div>
                            <div class="col-md-4">
                                <div><img src="/assets/images/img-2.png" class="services_img"></div>
                                <div class="btn_main active"><a href="#">Hiking</a></div>
                            </div>
                            <div class="col-md-4">
                                <div><img src="/assets/images/img-3.png" class="services_img"></div>
                                <div class="btn_main"><a href="#">Camping</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
    <div class="services_section layout_padding">
        <div class="container">
            <p class="services_text1 center-text">Desa</p>
            <h1 class="services_taital center-text">Top 10 Hasil Rekomendasi Desa Wisata</h1>
            <div class="services_section_2">
                <div class="row" id="data">
                    @foreach (array_slice($rekomendasi,0,10) as $data_rekomendasi)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    {{ $data_rekomendasi->nama }}
                                    <span class="badge" style="background-color: white;color:red">Rekomendasi</span>
                                </div>
                                <div class="card-body">
                                    <img src="/assets/images/desa/{{ $data_rekomendasi->foto }}" class="card-logo" style="width:300px;height:168px">
                                    <p class="card-text">Klik untuk melihat desa di Desa
                                    <h1></h1>
                                    </p>
                                    <a href="{{ route('show.desa', $data_rekomendasi->slug) }}" class="button-13"
                                        role="button">Buka</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style=" margin: auto;width: 50%; text-align: center;">
                    <a href="{{ route('rekomendasi') }}" class="button-13" role="button" style="width:200px">Lihat semua rekomendasi</a>
                </div>
            </div>
        </div>
    </div>
    <div class="services_section layout_padding">
        <div class="container">
            <p class="services_text1 center-text">Desa</p>
            <h1 class="services_taital center-text">Per Kabupaten</h1>
            <div class="services_section_2">
                <div class="row">
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
                </div>
            </div>
        </div>
    </div>
    <!-- services section end -->
    <!-- about section start -->

    {{-- <div class="about_section layout_padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="about_taital_main">
                        <h1 class="about_taital">About Us</h1>
                        <p class="about_text">There are many variations of passages of Lorem Ipsum available, but the
                            majority
                            have suffered alteration in some form, by injected humour, or randomised words which don't
                            look
                            even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be
                            sure
                            there isn't anything embarrassing hidden in the middle of text. All </p>
                        <div class="readmore_bt"><a href="#">Read More</a></div>
                    </div>
                </div>
                <div class="col-md-6 padding_right_0">
                    <div><img src="/assets/images/about-img.png" class="about_img"></div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- about section end -->
    <!-- blog section start -->

                {{-- <div class="blog_section layout_padding">
                    <div class="container">
                        <h1 class="blog_taital">See Our Video</h1>
                        <p class="blog_text">many variations of passages of Lorem Ipsum available, but the majority have suffered
                            alteration in some form, by injected humour, or randomised words which</p>
                        <div class="play_icon_main">
                            <div class="play_icon"><a href="#"><img src="/assets/images/play-icon.png"></a></div>
                        </div>
                    </div>
                </div> --}}

    <!-- blog section end -->
    <!-- client section start -->

            {{-- <div class="client_section layout_padding">
                <div class="container">
                    <h1 class="client_taital">Testimonial</h1>
                    <div class="client_section_2">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="client_main">
                                        <div class="box_left">
                                            <p class="lorem_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                                do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud
                                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                                irure
                                                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugia</p>
                                        </div>
                                        <div class="box_right">
                                            <div class="client_taital_left">
                                                <div class="client_img"><img src="/assets/images/client-img.png"></div>
                                                <div class="quick_icon"><img src="/assets/images/quick-icon.png"></div>
                                            </div>
                                            <div class="client_taital_right">
                                                <h4 class="client_name">Dame</h4>
                                                <p class="customer_text">Customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="client_main">
                                        <div class="box_left">
                                            <p class="lorem_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                                do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud
                                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                                irure
                                                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugia</p>
                                        </div>
                                        <div class="box_right">
                                            <div class="client_taital_left">
                                                <div class="client_img"><img src="/assets/images/client-img.png"></div>
                                                <div class="quick_icon"><img src="/assets/images/quick-icon.png"></div>
                                            </div>
                                            <div class="client_taital_right">
                                                <h4 class="client_name">Dame</h4>
                                                <p class="customer_text">Customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="client_main">
                                        <div class="box_left">
                                            <p class="lorem_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                                do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud
                                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                                irure
                                                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugia</p>
                                        </div>
                                        <div class="box_right">
                                            <div class="client_taital_left">
                                                <div class="client_img"><img src="/assets/images/client-img.png"></div>
                                                <div class="quick_icon"><img src="/assets/images/quick-icon.png"></div>
                                            </div>
                                            <div class="client_taital_right">
                                                <h4 class="client_name">Dame</h4>
                                                <p class="customer_text">Customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

    <!-- client section start -->
    <!-- choose section start -->

            {{-- <div class="choose_section layout_padding">
                <div class="container">
                    <h1 class="choose_taital">Why Choose Us</h1>
                    <p class="choose_text">There are many variations of passages of Lorem Ipsum available, but the majority have
                        suffered alteration in some form, by injected humour, or randomised words which don't look even slightly
                        believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything
                        embarrassing hidden in the middle of text. All </p>
                    <div class="read_bt_1"><a href="#">Read More</a></div>
                    <div class="newsletter_box">
                        <h1 class="let_text">Let Start Talk with Us</h1>
                        <div class="getquote_bt"><a href="#">Get A Quote</a></div>
                    </div>
                </div>
            </div> --}}

    <!-- choose section end -->
@endsection
