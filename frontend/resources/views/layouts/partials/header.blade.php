@php
    use App\Facades\Cart;
@endphp
<!-- header start -->
<header class="header-area">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="left">
                        <li><span><i class="far fa-clock"></i></span> 10:30am - 18:00pm Sat</li>
                        <li><span><i class="far fa-phone"></i></span> +6289-888-88-320</li>
                        <li><span><i class="far fa-map-marker-alt"></i></span> D'Garden City Blok N.05</li>
                    </ul>
                    <ul class="right">
                        <li><a href="#!"><i class="fab fa-facebook-messenger"></i></a></li>
                        <li><a href="#!"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#!"><i class="fab fa-vimeo-v"></i></a></li>
                        <li><a href="#!"><i class="fab fa-skype"></i></a></li>
                        <li><a href="#!"><i class="fas fa-rss"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom-area" data-uk-sticky="top: 250; animation: uk-animation-slide-top;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-2 col-6">
                    <div class="logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo/logo-t2.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 d-none d-lg-block">
                    <div class="main-menu-wrap">
                        <!-- <nav id="mobile-menu" class="main-menu">
                            <ul class="main-menu-list ul_li">
                                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                                    <a href="{{ route('about') }}">About</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#!">Pages</a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('teachers.index') }}">Teachers</a></li>
                                        <li><a href="{{ route('teachers.show', 1) }}">Teacher Details</a></li>
                                        <li><a href="{{ route('classes.index') }}">Classes</a></li>
                                        <li><a href="{{ route('classes.show', 1) }}">Class Details</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="{{ route('blog.index') }}">Blog</a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                        <li><a href="{{ route('blog.show', 1) }}">Blog Details</a></li>
                                    </ul>
                                </li>
                                <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                                    <a href="https://wa.me/628988888320?text=Hallo%20saya%20ingin%20mendaftarkan%20putra%20putri%20kami">Contact</a>
                                </li>
                            </ul>
                        </nav> -->
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-6">
                    <div class="header-right">
                        <div class="header-btn">
                            <a class="thm-btn" href="https://wa.me/628988888320?text=Hallo%20saya%20ingin%20mendaftarkan%20putra%20putri%20kami">Admission Now</a>
                        </div>
                        <div class="side-mobile-menu">
                            <button class="side-info-close"><i class="far fa-times"></i></button>
                            <div class="mobile-menu"></div>
                        </div>
                        <div class="side-menu-icon d-lg-none">
                            <button class="side-toggle"><i class="far fa-bars"></i></button>
                        </div>
                        <div class="offcanvas-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header end -->
