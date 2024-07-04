<?php
  // dd($kegiatan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ CRUDBooster::publicPath('gallery/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ CRUDBooster::publicPath('gallery/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ CRUDBooster::publicPath('gallery/css/templatemo-style.css') }}">
    <script async custom-element="amp-auto-ads"
        src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js">
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8343297609561201"
     crossorigin="anonymous"></script>
<!--
    
TemplateMo 556 Catalog-Z

https://templatemo.com/tm-556-catalog-z

-->
</head>
<body>
    <!-- Page Loader -->
    <div id="loader-wrapper">
        <div id="loader"></div>

        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-film mr-2"></i>
                {{ $title }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-1 active" aria-current="page" href="#">GRID</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-1" aria-current="page" href="{{ $back }}">MAP</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
    <?php var_dump()  ?>
    <div class="tm-hero d-flex justify-content-center align-items-center" data-parallax="scroll" data-image-src="{{ CRUDBOoster::publicPath('img/bg2.jpg') }}">
        <form class="d-flex tm-search-form" method='post' action="/public/cctv/gridcari">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input class="form-control tm-search-input" type="search" placeholder="Cari" aria-label="Cari" name="cari">
            <button class="btn btn-outline-success tm-search-btn" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    <div class="container-fluid tm-container-content tm-mt-60">
        <div class="row mb-4">
            <h2 class="col-6 tm-text-primary">
                Views ( total : {{ number_format($total_played) }} - today : {{ number_format($todays_played) }} )
            </h2>
            <div class="col-6 d-flex justify-content-end align-items-center">
                <form action="" class="tm-text-primary">
                    Page <input type="text" value="{{ $kegiatan->currentPage() }}" size="1" class="tm-input-paging tm-text-primary"> of {{ $kegiatan->lastPage() }}
                </form>
            </div>
        </div>
        <div class="row tm-mb-90 tm-gallery">
            @foreach($kegiatan as $keg)
                
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                    <figure class="effect-ming tm-video-item">
                        <iframe id="ifrm-{{ $keg->name }}" frameBorder="0" src="https://streaming.indera.id:4433?{{ $keg->name }}&{{ $keg->display_name }}&{{ $keg->owner }}" allowfullscreen allow="autoplay" scrolling="no" style="width:100%;height:300px"></iframe>

                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span> Views total : {{ number_format($keg->clicked) }} - today : {{ number_format($keg->harian) }}</span>
                    </div>
                </div>
            @endforeach
        </div> <!-- row -->
        <div class="d-flex">
            <div class="mx-auto">
                {{$kegiatan->links("pagination::bootstrap-4")}}
            </div>
        </div>
    </div> <!-- container-fluid, tm-container-content -->

    
    
    <script src="{{ CRUDBooster::publicPath('gallery/js/plugins.js') }}"></script>
    <script>
        $(window).on("load", function() {
            $('body').addClass('loaded');
        });
    </script>
</body>
<amp-auto-ads type="adsense"
        data-ad-client="ca-pub-8343297609561201">
</amp-auto-ads>
</html>