<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Dashboard</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="/assets/images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="/assets/css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700|Righteous&display=swap" rel="stylesheet">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <!-- header section start -->
    <div class="header_section">
        <div class="header_main">
            <div class="mobile_menu">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="logo_mobile"><a href="{{ route('index') }}"><img src="/assets/images/logo1.png"></a></div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('index') }}">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <li><a href="{{ route('kabupaten') }}">Kabupaten</a></li>
                            </li>
                            <li class="nav-item">
                                <li><a href="{{ route('rekomendasi') }}">Rekomendasi</a></li>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="container-fluid">
                <div class="logo"><a href="{{ route('index') }}"><img src="/assets/images/logo1.png"></a></div>
                <div class="menu_main">
                    <ul>
                        <li><a href="{{ route('index') }}">Beranda</a></li>
                        <li><a href="{{ route('kabupaten') }}">Kabupaten</a></li>
                        <li><a href="{{ route('rekomendasi') }}">Rekomendasi</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- banner section start -->
        @if (Request::routeIs('index'))
        <div class="banner_section layout_padding">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="container">
                            <h1 class="banner_taital">Desa Wisata</h1>
                            <p class="banner_text">Desa wisata adalah sebuah konsep pengembangan wilayah pedesaan 
                                yang mengoptimalkan potensi lokal untuk menarik wisatawan</p>
                            <div class="read_bt"><a href="#">Jelajahi</a></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="container">
                            <h1 class="banner_taital">Desa Wisata</h1>
                            <p class="banner_text">Desa wisata adalah sebuah konsep pengembangan wilayah pedesaan 
                                yang mengoptimalkan potensi lokal untuk menarik wisatawan</p>
                            <div class="read_bt"><a href="#">Jelajahi</a></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="container">
                            <h1 class="banner_taital">Desa Wisata</h1>
                            <p class="banner_text">Desa wisata adalah sebuah konsep pengembangan wilayah pedesaan 
                                yang mengoptimalkan potensi lokal untuk menarik wisatawan</p>
                            <div class="read_bt"><a href="#">Jelajahi</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- banner section end -->
    </div>
    <!-- header section end -->
    @yield('content')
    <!-- footer section start -->
    <div class="footer_section layout_padding">
        <div class="container">
            {{-- <div class="input_btn_main">
                <input type="text" class="mail_text" placeholder="Enter your email" name="Enter your email">
                <div class="subscribe_bt"><a href="#">Subscribe</a></div>
            </div> --}}
            <div class="location_main">
                <div class="call_text"><img src="/assets/images/call-icon.png"></div>
                <div class="call_text"><a href="#">Call +01 1234567890</a></div>
                <div class="call_text"><img src="/assets/images/mail-icon.png"></div>
                <div class="call_text"><a href="#">desapotensi@gmail.com</a></div>
            </div>
            <div class="social_icon">
                <ul>
                    <li><a href="#"><img src="/assets/images/fb-icon.png"></a></li>
                    <li><a href="#"><img src="/assets/images/twitter-icon.png"></a></li>
                    <li><a href="#"><img src="/assets/images/linkedin-icon.png"></a></li>
                    <li><a href="#"><img src="/assets/images/instagram-icon.png"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- footer section end -->
    <!-- copyright section start -->
                {{-- <div class="copyright_section">
                    <div class="container">
                        <p class="copyright_text">2020 All Rights Reserved. Design by <a href="https://html.design">Free html
                                Templates</a> Distributed by <a href="https://themewagon.com">ThemeWagon</a></p>
                    </div>
                </div> --}}
    <!-- copyright section end -->
    <!-- Javascript files-->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery-3.0.0.min.js"></script>
    <script src="/assets/js/plugin.js"></script>
    <!-- sidebar -->
    <script src="/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/assets/js/custom.js"></script>
    <!-- javascript -->
    <script src="/assets/js/owl.carousel.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>

</html>
