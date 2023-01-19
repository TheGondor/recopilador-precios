<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="es" dir="ltr">

<head>
    <meta charset="UTF-8">
    @yield('title')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/generales.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/sweetalert2.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/modal.css') }}">
    <script src="{{ asset('js/sweetalert2.bundle.js') }}"></script>
    <script src="{{ asset('js/modal-loading.js') }}"></script>
    <script src="{{ asset('js/main.min.js') }}"></script>
    <script src="{{ asset('js/locales-all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/lubeck.css') }}">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="logo-details2">
        <i class='bx bx-menu' id="btn2"></i>
    </div>
    <div class="sidebar">
        <div class="logo-details">
            <div class="logo_name" onclick="location.href='/dashboard'" style="cursor: pointer"><i class='bx bx-left-arrow-alt'></i>Empresas</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list" style="height: 370px">
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'') }}" title="Inicio">
                    <i class='bx bx-home'></i>
                    <span class="links_name">Home Proveedor</span>
                </a>
                <span class="tooltip">Home Proveedor</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/buscador') }}" title="Buscador convenio ferretería">
                    <i class='bx bxs-search' ></i>
                    <span class="links_name">Buscador</span>
                </a>
                <span class="tooltip">Buscador</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/stock') }}" title="Productos con stock">
                    <i class='bx bxs-checkbox-checked' ></i>
                    <span class="links_name">Stock</span>
                </a>
                <span class="tooltip">Stock</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/sin_stock') }}" title="Productos sin stock">
                    <i class='bx bxs-x-square'></i>
                    <span class="links_name">Sin Stock</span>
                </a>
                <span class="tooltip">Sin Stock</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/stock_dispersion') }}" title="Productos con stock fuera de despersión">
                    <i class='bx bx-alarm'></i>
                    <span class="links_name">Stock Dispersión</span>
                </a>
                <span class="tooltip">Stock Dispersión</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/sin_stock_dispersion') }}" title="Productos sin stock fuera de despersión">
                    <i class='bx bx-alarm-off' ></i>
                    <span class="links_name">Sin Stock Dispersión</span>
                </a>
                <span class="tooltip">Sin Stock Dispersión</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/ofertas') }}" title="Productos en oferta">
                    <i class='bx bxs-offer'></i>
                    <span class="links_name">Ofertas</span>
                </a>
                <span class="tooltip">Ofertas</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/mas-buscados') }}" title="Terminos mas buscados">
                    <i class='bx bx-search-alt' ></i>
                    <span class="links_name">Mas buscados</span>
                </a>
                <span class="tooltip">Mas buscados</span>
            </li>
            <li>
                <a href="{{ asset('provider/'.$provider->id.'/'.$convenio.'/mas-visitas') }}" title="Producto con mas visitas">
                    <i class='bx bx-link-external'></i>
                    <span class="links_name">Mas visitados</span>
                </a>
                <span class="tooltip">Mas visitados</span>
            </li>
            <li class="close">
                <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" title="Logout">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Salir</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <div class="container-fluid p-0">
        <div class="h-100 w-100 d-flex overflow-auto">
            @yield('content')
        </div>
    </div>
</body>
<div id="modales"></div>
</html>
@yield('scripts')
<script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let closeBtn2 = document.querySelector("#btn2");
    window.onresize = function () {
        $('.nav-list').height($(window).height()-150);
    };

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        menuBtnChange(); //calling the function(optional)
    });

    closeBtn2.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        menuBtnChange(); //calling the function(optional)
    });



    // following are the code to change sidebar button(optional)
    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
            $(".logo-details2").hide();
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
            $(".logo-details2").show();
        }
    }

    $(document).ready(function(){
        $('.nav-list').height($(window).height()-150);
    })
</script>
