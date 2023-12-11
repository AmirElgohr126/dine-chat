<!doctype html>
<html lang="en">
    
<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="shortcut icon" href="http://127.0.0.1:8000/assets/images/defualt_logo/ligth_sm_logo.png">

    <link rel="stylesheet" href="http://127.0.0.1:8000/assets/css/preloader.min.css" type="text/css" />


    <link href="http://127.0.0.1:8000/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />

    <link href="http://127.0.0.1:8000/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="http://127.0.0.1:8000/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <link href="http://127.0.0.1:8000/assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet"
        type="text/css" />
    <link href="http://127.0.0.1:8000/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="http://127.0.0.1:8000/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />
    <link href="http://127.0.0.1:8000/assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet"
        type="text/css" />



    <link href="http://127.0.0.1:8000/assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet"
        type="text/css" />
    <link href="http://127.0.0.1:8000/assets/libs/alertifyjs/build/css/themes/default.min.css" rel="stylesheet"
        type="text/css" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



</head>


<body data-sidebar="ligth" data-layout-mode="ligth" data-topbar="ligth">

    <div id="layout-wrapper">


        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">

                    <div class="navbar-brand-box">
                        <a href="http://127.0.0.1:8000/home" class="logo logo-light">
                            <span class="logo-sm">
                                <img class="lazyload"
                                    data-src="http://127.0.0.1:8000/assets/images/defualt_logo/ligth_sm_logo.png"
                                    alt="" height="30">
                            </span>
                            <span class="logo-lg">
                                <img class="lazyload"
                                    data-src="http://127.0.0.1:8000/assets/images/defualt_logo/ligth_sm_logo.png"
                                    alt="" height="60">

                            </span>
                        </a>

                        <a href="http://127.0.0.1:8000/home" class="logo logo-dark">
                            <span class="logo-sm">
                                <img class="lazyload"
                                    data-src="http://127.0.0.1:8000/assets/images/defualt_logo/ligth_sm_logo.png"
                                    alt="" height="30">
                            </span>
                            <span class="logo-lg">
                                <img class="lazyload"
                                    data-src="http://127.0.0.1:8000/assets/images/defualt_logo/dark_sm_logo.png"
                                    alt="" height="60">

                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <button type="button"
                        class="btn btn-sm px-3 font-size-16 header-item font-weight-bold d-none d-sm-inline-block">
                        The Salad Life
                    </button>


                </div>

                <div class="d-flex">


                    <div class="dropdown ">
                        <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item" id="mode-setting-btn">
                                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                            </button>
                        </div>
                        <div class="dropdown d-none ">
                            <button type="button" class="btn header-item" data-bs-toggle="modal"
                                data-bs-target=".bs-example-modal-xl">
                                <i class="fa fa-search font-size-18"></i>
                            </button>
                        </div>
                        <div class="dropdown d-none ">
                            <button type="button" class="btn header-item" data-bs-toggle="modal"
                                data-bs-target=".bs-example1-modal-xl">
                                <i class="fa fa-keyboard font-size-18"></i>
                            </button>
                        </div>
                        <div class="dropdown d-none ms-1">
                            <button type="button" class="btn header-item select_store_top" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <h1 class="font-size-18 px-2 pt-2 header-item d-inline-block h-auto"><span
                                        class="fas fa-store-alt font-size-18"></span></h1>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <div class="p-2">

                                    <a class="dropdown-icon-item   bg-light-gray  disabled " title="Set as Default">
                                        <div class="row g-0">

                                            <div class="col-3">

                                                <h1
                                                    class="rounded-circle header-profile-user font-size-18 px-2 pt-2 text-white d-inline-block font-bold bg-primary">
                                                    T</h1>


                                            </div>
                                            <div class="col-9  text-start overflow-hidden">
                                                <span>The Salad Life</span>
                                            </div>


                                        </div>
                                    </a>


                                </div>
                            </div>
                        </div>
                        <div class="dropdown d-none ms-1">
                            <button type="button" class="btn header-item" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <h1 class="font-size-18 px-2 pt-2 header-item d-inline-block h-auto"><span
                                        class="fas fa-language font-size-18"></span></h1>
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                <div class="p-2">
                                    <a class="dropdown-icon-item   bg-light-gray  disabled " title="Set as Default">
                                        <div class="row g-0">
                                            <div class="col-12  text-start overflow-hidden">
                                                <h6 class="px-2">English</h6>
                                            </div>


                                        </div>
                                    </a>


                                </div>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-soft-light "
                                id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">

                                <img data-src="/storage/profile/17022168142354_laravellogo1.png" alt=""
                                    class="rounded-circle header-profile-user image-object-cover lazyload">

                                <span class="d-none d-xl-inline-block ms-1 fw-medium">Default Restaurant</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">

                                <a class="dropdown-item" href="http://127.0.0.1:8000/profile"><i
                                        class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile</a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" role="button"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').click();"><i
                                        class="mdi mdi-logout font-size-16 align-middle me-1"></i>
                                    Sign out</a>
                                <form autocomplete="off" action="http://127.0.0.1:8000/logout" method="POST"
                                    class="d-none data-confirm"
                                    data-confirm-message="Are you sure you want to logout?"
                                    data-confirm-title=" Sign out">
                                    <button id="logout-form" type="submit"></button>
                                    <input type="hidden" name="_token"
                                        value="eMY4uxuIhF4A2sA3lKEgsbVgvtnO9Xfr2HU5VVOn">
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
        </header>


        <div class="vertical-menu">

            <div data-simplebar class="h-100">


                <div id="sidebar-menu">


                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li>
                            <a href="http://127.0.0.1:8000/home" class="active">
                                <i class="fas fa-home"></i>

                                <span data-key="t-dashboard">Dashboard</span>
                            </a>
                        </li>


                        <li><a href="http://127.0.0.1:8000/food-categories"><i
                                    class="fas fa-list-alt font-size-18"></i> <span
                                    data-key="t-Categories">Categories</span></a></li>

                        <li><a href="http://127.0.0.1:8000/foods"> <i class="fas fa-hamburger font-size-18"></i> <span
                                    data-key="t-Foods">Foods</span></a></li>


                        <li><a href="http://127.0.0.1:8000/qr-image"> <i class="fas fa-qrcode font-size-18"></i> <span
                                    data-key="t-QR Code">QR Code</span></a></li>

                        <li><a href="http://127.0.0.1:8000/environment/setting"> <i
                                    class="fas fa-cog font-size-18"></i> <span
                                    data-key="t-Settings">Settings</span></a></li>



                    </ul>

                </div>

            </div>
        </div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">


                    <div class="row">
                        <div class="col-12">

                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>


                            </div>
                        </div>
                    </div>
                    <div class="row">



                        <div class="col-xl-3 col-md-6">
                            <div class="card card-h-100">
                                <div class="card-body">
                                    <a href="http://127.0.0.1:8000/food-categories">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Food
                                                    Categories</span>
                                                <h4 class="mb-3">
                                                    <span class="counter-value" data-target="1">0</span>
                                                </h4>

                                            </div>

                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">

                            <div class="card card-h-100">

                                <div class="card-body">
                                    <a href="http://127.0.0.1:8000/foods">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Total
                                                    Foods</span>
                                                <h4 class="mb-3">
                                                    <span class="counter-value" data-target="0">0</span>
                                                </h4>

                                            </div>

                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">




                        <div class="col-xl-6">
                            <div class="card">

                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Recent Categories</h4>

                                </div><!-- end card header -->

                                <div class="card-body px-0 pb-0 pt-2">
                                    <div class="table-responsive px-3" data-simplebar="init" style="height: 455px;">

                                        <table class="table align-middle table-nowrap">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 50px;">
                                                        <div class="avatar-md me-4">

                                                            <img data-src="/storage/category_image/17012743671334_screenshot20231107013402.png"
                                                                alt=""
                                                                class="avatar-md rounded-circle me-2 image-object-cover lazyload">
                                                        </div>
                                                    </td>

                                                    <td style="max-width:250px" class="">
                                                        <div class="text-dark">
                                                            <h5 class="font-size-15 text-truncate">
                                                                <a>laravel food</a>
                                                            </h5>

                                                        </div>
                                                    </td>


                                                    <td>
                                                        <div class="text-end">

                                                            <span class="text-muted">2023-11-29 16:12:27</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>

                        <div class="col-xl-6">
                            <div class="card">

                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Recent Foods</h4>

                                </div><!-- end card header -->

                                <div class="card-body px-0 pb-0 pt-2">
                                    <div class="table-responsive px-3" data-simplebar="init" style="height: 455px;">

                                        <table class="table align-middle table-nowrap">
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                                <!-- end card body -->
                            </div>
                        </div>

                    </div>



                </div>
            </div>



            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-0">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Copyright
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                <a href="http://127.0.0.1:8000/home">Laravel</a> | All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>





    <script src="http://127.0.0.1:8000/assets/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.3/mousetrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"
        integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
</body>

</html>
