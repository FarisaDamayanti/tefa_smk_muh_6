<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>MiSchool | Student</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('mobilelogo.png') }}" />
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/main.css') }}">
    <link id="themeColors" rel="stylesheet" href="{{ asset('admin_assets/dist/css/style.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .navbar-nav>.nav-item {
            margin-right: 12px;
        }

        .nav-link {
            color: #495057;
            font-weight: 500;
            border-radius: 6px;
            transition: color 0.2s ease;
        }


        .nav-menu {
            position: relative;
        }

        .nav-menu::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 6px;
            width: 0;
            height: 2px;
            background-color: #0d6efd;
            border-radius: 2px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }

        .nav-menu:hover::after,
        .nav-menu.active::after {
            width: 100%;
        }

        .nav-menu:hover,
        .nav-menu.active {
            color: #0d6efd !important;
        }


        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        @media (max-width: 991.98px) {
            .nav-link {
                padding: 0.75rem 1rem !important;
                margin: 0.1rem 0;
            }

            .nav-menu::after {
                display: none;
            }

            .app-header {
                position: sticky;
                top: 0;
                z-index: 1030;
            }
        }
    </style>
    @yield('style')
</head>

<body style="background-color: #FFF;">
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('assets/images/logo/logo-M.png') }}" style="width:100px" alt="loader" class="lds-ripple" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('assets/images/logo/logo-M.png') }}" style="width:100px" alt="loader" class="lds-ripple" />
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <div class="body-wrapper">
            @if (!request()->routeIs('student.exams.working'))
                @include('student.layouts.navbar-new')
            @endif

            <div class="px-2 px-md-4 pt-5">
                <div class="pt-4">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>



    <!--  Customizer -->

    <!--  Customizer -->
    <!--  Import Js Files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000,
        };

        function setButtonLoading(button, isLoading) {
            let originalTextButton = button.html();

            if (isLoading) {
                button.prop('disabled', true).addClass('disabled');
                button.html(`
                <span class="spinner-loader spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            ` + originalTextButton);
            } else {
                button.prop('disabled', false).removeClass('disabled');
                button.find('.spinner-loader').remove();
            }
        }
    </script>
    <script src="{{ asset('admin_assets/dist/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin_assets/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!--  core files -->
    <script src="{{ asset('admin_assets/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('admin_assets/dist/js/app.init.js') }}"></script>
    <script src="{{ asset('admin_assets/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('admin_assets/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('admin_assets/dist/js/custom.js') }}"></script>
    <!--  current page js files -->
    <script src="{{ asset('admin_assets/dist/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    @yield('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileSidebar = document.getElementById('mobileSidebar');

            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992 && mobileSidebar) {
                        const bsOffcanvas = bootstrap.Offcanvas.getInstance(mobileSidebar);
                        if (bsOffcanvas) {
                            bsOffcanvas.hide();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
