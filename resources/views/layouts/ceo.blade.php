<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul Default, akan digunakan jika config('app.name') tidak ada --}}
    <title>{{ config('app.name', 'BMP e-commerce') }}</title>

    {{-- Anda memiliki dua tag <title>. Browser biasanya akan menggunakan yang pertama. --}}
    {{-- Sebaiknya pilih salah satu saja. Saya akan mengomentari yang kedua. --}}
    {{-- <title>Black Mamba Paganite</title> --}}
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="surfside media" />

    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}"> {{-- Kemungkinan besar CSS tema utama dari SurfsideMedia --}}
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"> {{-- CSS kustom global Anda --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Di sinilah gaya spesifik halaman yang di-push dari Blade anak akan dimasukkan --}}
    {{-- PASTIKAN NAMA STACK SUDAH BENAR ('styles' bukan 'stayles') --}}
    @stack('styles')
</head>

<body class="body"> {{-- Kelas "body" pada tag body ini bisa digunakan untuk menargetkan CSS lebih spesifik jika perlu --}}
    <div id="wrapper">
        <div id="page" class=""> {{-- ID "page" juga bisa jadi target CSS spesifik --}}
            <div class="layout-wrap">

                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="{{ route('home.index') }}" id="site-logo-inner">
                            <img class="" id="logo_header_1" alt=""
                                src="{{ asset('images/logo/logo.png') }}"
                                data-light="{{ asset('images/logo/logo.png') }}"
                                data-dark="{{ asset('images/logo/logo.png') }}">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Main Home</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('admin.index') }}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Dashboard</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    {{-- Pastikan route 'users.index' aktif jika halaman saat ini adalah users.index --}}
                                    <a href="{{ route('users.index') }}"
                                        class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-user"></i></div>
                                        <div class="text">User</div>
                                    </a>
                                </li>
                                {{-- Tambahkan menu lain di sini jika ada --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="section-content-right">
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="{{ route('home.index') }}">
                                    <img class="" id="logo_header_mobile" alt=""
                                        src="{{ asset('images/logo/logo.png') }}"
                                        data-light="{{ asset('images/logo/logo.png') }}"
                                        data-dark="{{ asset('images/logo/logo.png') }}" data-width="154px"
                                        data-height="52px" data-retina="{{ asset('images/logo/logo.png') }}">
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>

                                <form class="form-search flex-grow">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Search here..." class="show-search"
                                            name="name" id="search-input" tabindex="2" value=""
                                            aria-required="true" required="" autocomplete="off">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button class="" type="submit"><i class="icon-search"></i></button>
                                    </div>
                                    <div class="box-content-search">
                                        <ul id="box-content-search">
                                        </ul>
                                    </div>
                                </form>
                            </div>

                            <div class="header-grid">
                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">1</span> {{-- Contoh notifikasi --}}
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Notifications</h6>
                                            </li>
                                            <li>
                                                <div class="message-item item-1">
                                                    <div class="image"><i class="icon-noti-1"></i></div>
                                                    <div>
                                                        <div class="body-title-2">Discount available</div>
                                                        <div class="text-tiny">Morbi sapien massa, ultricies at rhoncus
                                                            at, ullamcorper nec diam</div>
                                                    </div>
                                                </div>
                                            </li>
                                            {{-- ... item notifikasi lainnya ... --}}
                                            <li><a href="#" class="tf-button w-full">View all</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <img src="{{ auth()->user()->profilePhoto
                                                        ? Storage::url(auth()->user()->profilePhoto->photo_path)
                                                        : asset('images/avatar/user-1.png') }}"
                                                        alt="Foto Profil {{ auth()->user()->name }}">
                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                                                    <span class="text-tiny">
                                                        {{ Auth::user()->utype === 'ADM' ? 'Admin' : (Auth::user()->utype === 'CEO' ? 'CEO' : 'User') }}
                                                    </span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="{{ route('admin.profile') }}" class="user-item">
                                                    {{-- Sesuaikan route jika perlu --}}
                                                    <div class="icon"><i class="icon-user"></i></div>
                                                    <div class="body-title-2">Account</div>
                                                </a>
                                            </li>
                                            <li>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                                <a href="{{ route('logout') }}" class="user-item"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <div class="icon"><i class="icon-log-out"></i></div>
                                                    <div class="body-title-2">Log out</div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="main-content"> {{-- Wrapper untuk konten utama --}}
                        @yield('content') {{-- Di sinilah konten dari views/users/index.blade.php akan ditampilkan --}}

                        <div class="bottom-page">
                            <div class="body-text">Copyright © {{ date('Y') }} SurfsideMedia</div>
                            {{-- Menggunakan date('Y') untuk tahun dinamis --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script> {{-- Pastikan ini Bootstrap 5 jika dropdown menggunakan data-bs-toggle --}}
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    {{-- Script AJAX untuk pencarian --}}
    <script>
        $(function() {
            $("#search-input").on("keyup", function() {
                var searchQuery = $(this).val();
                // Sebaiknya periksa searchQuery.length bukan searchQuery.lenght
                if (searchQuery.length > 2) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.search') }}", // Pastikan route ini ada
                        data: {
                            query: searchQuery
                        },
                        dataType: 'json',
                        success: function(data) {
                            $("#box-content-search").html(''); // Kosongkan hasil sebelumnya
                            if (data && data.length > 0) {
                                $.each(data, function(index, item) {
                                    // Pastikan 'item.id', 'item.image', 'item.name' ada di response JSON
                                    var productImageUrl =
                                        "{{ asset('uploads/products/tumbnails') }}/" +
                                        (item.image ||
                                        'default.png'); // Fallback jika image null
                                    var productEditUrl =
                                        "{{ route('admin.product.edit', ['id' => ':id']) }}"
                                        .replace(':id', item.id);

                                    $("#box-content-search").append(`
                                        <li>
                                            <ul>
                                                <li class="product-item gap14 mb-10">
                                                    <div class="image no-bg">
                                                        <img src="${productImageUrl}" alt="${item.name || 'Product'}">
                                                    </div>
                                                    <div class="flex items-center justify-between gap20 flex-grow">
                                                        <div class="name">
                                                            <a href="${productEditUrl}" class="body-text">${item.name || 'Unnamed Product'}</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                ${ (index < data.length - 1) ? '<li class="mb-10"><div class="divider"></div></li>' : '' }
                                            </ul>
                                        </li>
                                    `);
                                });
                            } else {
                                $("#box-content-search").append(
                                    '<li><div class="body-text p-3">No products found.</div></li>'
                                    );
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Search AJAX error:", textStatus, errorThrown);
                            $("#box-content-search").html(
                                '<li><div class="body-text p-3 text-danger">Error during search.</div></li>'
                                );
                        }
                    });
                } else {
                    $("#box-content-search").html(''); // Kosongkan jika query pendek
                }
            });
        });
    </script>

    @stack('scripts') {{-- Di sinilah script spesifik halaman akan dimasukkan --}}
</body>

</html>
