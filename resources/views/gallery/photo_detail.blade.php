<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="{{ CRUDBooster::publicPath('gallery/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ CRUDBooster::publicPath('gallery/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ CRUDBooster::publicPath('gallery/css/templatemo-style.css') }}">
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
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-film mr-2"></i>
                DETAIL KEGIATAN
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-1 active" aria-current="page" href="index.html">Photos</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="tm-hero d-flex justify-content-center align-items-center" data-parallax="scroll" data-image-src="{{ CRUDBOoster::publicPath('img/bg2.jpg') }}">
        <form class="d-flex tm-search-form" method='post' action="{{ URL::to('intel/gallery') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input class="form-control tm-search-input" type="search" placeholder="Cari" aria-label="Cari" name="cari">
            <button class="btn btn-outline-success tm-search-btn" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="container-fluid tm-container-content tm-mt-60">
        <div class="row mb-4">
            <h2 class="col-12 tm-text-primary">{{ $detail->judul }}</h2>
        </div>
        <div class="row tm-mb-90">            
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12">
                <img src="https://drive.google.com/uc?export=view&id={{ $detail->photo }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                <div class="tm-bg-gray tm-video-details">
                    <p class="mb-4">
                        Deskripsi
                    </p>
                    
                    {{ $detail->deskripsi }}
                    <br>
                    <div class="mb-4 d-flex flex-wrap">
                        <div>
                            <span class="tm-text-gray-dark">Tanggal: </span><span class="tm-text-primary">{{ date('d M Y', strtotime($detail->tanggal)) }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="text-center mb-5">
                        <a href="#" class="btn btn-primary tm-btn-big">Download</a>
                    </div>                    

                </div>
            </div>
        </div>
        <div class="row mb-4">
            <h2 class="col-12 tm-text-primary">
                Kegiatan Terkait
            </h2>
        </div>
        <div class="row mb-3 tm-gallery">
            @foreach($kegiatan as $keg)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                    <figure class="effect-ming tm-video-item">
                        <img src="https://drive.google.com/uc?export=view&id={{ $keg->photo }}" alt="Image" class="img-fluid">
                        <figcaption class="d-flex align-items-center justify-content-center">
                            <h2>{{ $keg->judul }}</h2>
                            <a href="{{ $keg->id }}">View more</a>
                        </figcaption>                    
                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span class="tm-text-gray-light">{{ date('d M Y', strtotime($keg->tanggal)) }}</span>
                        <span>{{ $keg->clicked }} views</span>
                    </div>
                </div>
            @endforeach
        </div> <!-- row -->
    </div> <!-- container-fluid, tm-container-content -->


    
    <script src="{{ CRUDBooster::publicPath('gallery/js/plugins.js') }}"></script>
    <script>
        $(window).on("load", function() {
            $('body').addClass('loaded');
        });
    </script>
</body>
</html>