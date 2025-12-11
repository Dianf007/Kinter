@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- hero start -->
<section class="hero-slider hero-style-1 section-notch">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="slide-inner slide-overlay slide-bg-image" data-background="{{ asset('assets/img/slider/slide-01.jpg') }}">
                    <div class="container">
                        <div data-swiper-parallax="200" class="slide-span">
                            <span>A New Approach to</span>
                        </div>
                        <div data-swiper-parallax="300" class="slide-title">
                            <h2>Kids Education</h2>
                        </div>
                        <div data-swiper-parallax="400" class="slide-text">
                            <p>The Universe is one great kindergarten for man. Everything that exists has brought with it its own peculiar lesson.</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="slider-btn">
                            <a data-swiper-parallax="500" class="thm-btn thm-btn-2" href="https://wa.me/628988888320?text=Hallo%20saya%20ingin%20mendaftarkan%20putra%20putri%20kami">Admission Now</a>
                            <a data-swiper-parallax="550" class="thm-btn" href="{{ route('classes.index') }}">Our Classes</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="slide-inner slide-overlay slide-bg-image" data-background="{{ asset('assets/img/slider/slide-02.jpg') }}">
                    <div class="container">
                        <div data-swiper-parallax="200" class="slide-span">
                            <span>A New Approach to</span>
                        </div>
                        <div data-swiper-parallax="300" class="slide-title">
                            <h2>Kids Education</h2>
                        </div>
                        <div data-swiper-parallax="400" class="slide-text">
                            <p>The Universe is one great kindergarten for man. Everything that exists has brought with it its own peculiar lesson.</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="slider-btn">
                            <a data-swiper-parallax="500" class="thm-btn thm-btn-2" href="https://wa.me/628988888320?text=Hallo%20saya%20ingin%20mendaftarkan%20putra%20putri%20kami">Admission Now</a>
                            <a data-swiper-parallax="550" class="thm-btn" href="{{ route('classes.index') }}">Our Classes</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="slide-inner slide-overlay slide-bg-image" data-background="{{ asset('assets/img/slider/slide-03.jpg') }}">
                    <div class="container">
                        <div data-swiper-parallax="200" class="slide-span">
                            <span>A New Approach to</span>
                        </div>
                        <div data-swiper-parallax="300" class="slide-title">
                            <h2>Kids Education</h2>
                        </div>
                        <div data-swiper-parallax="400" class="slide-text">
                            <p>The Universe is one great kindergarten for man. Everything that exists has brought with it its own peculiar lesson.</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="slider-btn">
                            <a data-swiper-parallax="500" class="thm-btn thm-btn-2" href="https://wa.me/628988888320?text=Hallo%20saya%20ingin%20mendaftarkan%20putra%20putri%20kami">Admission Now</a>
                            <a data-swiper-parallax="550" class="thm-btn" href="{{ route('classes.index') }}">Our Classes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- swipper controls -->
        <div class="container">
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
<!-- hero end -->

<!-- feature area start -->
<section class="feature-area pt-110 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="section-title text-center mb-60">
                    <h2 class="title">Welcome to Our Classes</h2>
                    <p>Here is what you can expect from a house cleaning from a Handy professional. Download the app
                        to share further cleaning details and instructions!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="feature-item text-center mb-30">
                    <div class="feature-shape">
                        <img src="{{ asset('assets/img/icon/f-icon-1.png') }}" alt="">
                    </div>
                    <div class="feature-content">
                        <div class="feature-title">
                            <h3>Active Learning</h3>
                        </div>
                        <p>Since have been visonary relable sofware engnern partne have been and visionary</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="feature-item text-center mb-30">
                    <div class="feature-shape feature-shape-2">
                        <img src="{{ asset('assets/img/icon/f-icon-2.png') }}" alt="">
                    </div>
                    <div class="feature-content">
                        <div class="feature-title feature-title-2">
                            <h3>Expert Teachers</h3>
                        </div>
                        <p>Since have been visonary relable sofware engnern partne have been and visionary</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="feature-item text-center mb-30">
                    <div class="feature-shape feature-shape-3">
                        <img src="{{ asset('assets/img/icon/f-icon-3.png') }}" alt="">
                    </div>
                    <div class="feature-content">
                        <div class="feature-title feature-title-3">
                            <h3>Parents Day</h3>
                        </div>
                        <p>Since have been visonary relable sofware engnern partne have been and visionary</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="feature-item text-center mb-30">
                    <div class="feature-shape feature-shape-4">
                        <img src="{{ asset('assets/img/icon/f-icon-4.png') }}" alt="">
                    </div>
                    <div class="feature-content">
                        <div class="feature-title feature-title-4">
                            <h3>Music Lessons</h3>
                        </div>
                        <p>Since have been visonary relable sofware engnern partne have been and visionary</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- feature area end -->

<!-- about area start -->
<section class="about-area section-bg-one section-notch">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 d-flex align-items-center">
                <div class="about-content pt-100 pb-100">
                    <div class="section-title section-title-white mb-30">
                        <h2 class="title">About Pondok Koding</h2>
                        <p>Discover coding skills at 'Pondok Koding' with personalized guidance, fostering creativity and problem-solving in a supportive learning environment. Join us today!</p>
                    </div>
                    <div class="about-btn">
                        <a class="thm-btn thm-btn-2" href="https://wa.me/628988888320?text=Hallo%20saya%20ingin%20mendaftarkan%20putra%20putri%20kami">admission now</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="about-img d-none d-lg-block f-right">
                    <img src="{{ asset('assets/img/about/about.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- about area end -->

<!-- class area start -->
<!-- latest project area start -->
<section class="project-area pt-110 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="section-title text-center mb-60">
                    <h2 class="title">Our Latest Project</h2>
                    <p>Discover our newest initiatives and creative projects designed to inspire and empower our students. Stay tuned for updates and highlights!</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($latestProjects ?? [] as $project)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="project-item mb-30">
                    <div class="project-img">
                        <img src="{{ asset($project->image) }}" alt="{{ $project->title }}">
                    </div>
                    <div class="project-content">
                        <h4 class="title"><a href="{{ $project->link ?? '#' }}">{{ $project->title }}</a></h4>
                        <p>{{ $project->description }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- latest project area end -->
<section class="class-area pt-110 pb-110">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="section-title text-center mb-60">
                    <h2 class="title">Our Popular Classes</h2>
                    <p>Here is what you can expect from a house cleaning from a Handy professional. Download the app to share further cleaning details and instructions!</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($classes as $class)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="class-item mb-30">
                    <div class="class-img">
                        <img src="{{ asset($class->image) }}" alt="class image">
                    </div>
                    <div class="class-content">
                        <h4 class="title"><a href="{{ route('classes.show', $class) }}">{{ $class->title }}</a></h4>
                        <p>Class Time : {{ $class->time }}</p>
                        <p>{{ $class->description }}</p>
                    </div>
                    <ul class="schedule">
                        <li>
                            <span>Class Size</span>
                            <span class="class-size">{{ $class->size }}</span>
                        </li>
                        <li>
                            <span>Years Old</span>
                            <span class="class-size class-size-2">{{ $class->age_range }}</span>
                        </li>
                        <li>
                            <span>Tuition Fee</span>
                            <span class="class-size">${{ number_format($class->fee, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="view-class text-center mt-30">
                    <a class="thm-btn" href="{{ route('classes.index') }}">See More Classes</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- class area end -->

<!-- counter area start -->
<section class="counter-area section-bg-two section-notch pt-130 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="single-counter text-center pb-30">
                    <img src="{{ asset('assets/img/icon/c-icon1.png') }}" alt="">
                    <h3><span class="odometer" data-count="2500">00</span><span class="plus">+</span></h3>
                    <p>Students Enrolled</p>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="single-counter text-center pb-30">
                    <img src="{{ asset('assets/img/icon/c-icon2.png') }}" alt="">
                    <h3><span class="odometer" data-count="912">00</span><span class="plus">+</span></h3>
                    <p>Best Awards Won</p>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="single-counter text-center pb-30">
                    <img src="{{ asset('assets/img/icon/c-icon3.png') }}" alt="">
                    <h3><span class="odometer" data-count="370">00</span><span class="plus">+</span></h3>
                    <p>Classes Completed</p>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="single-counter text-center pb-30">
                    <img src="{{ asset('assets/img/icon/c-icon4.png') }}" alt="">
                    <h3><span class="odometer" data-count="648">00</span><span class="plus">+</span></h3>
                    <p>Our Total Courses</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- counter area end -->

<!-- portfolio area start -->
<div class="portfolio-area pt-110 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="section-title text-center mb-50">
                    <h2 class="title">Our School Gallery</h2>
                    <p>Here is what you can expect from a house cleaning from a Handy professional. Download the app
                        to share further cleaning details and instructions!</p>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <ul class="portfolio-menu">
                    <li class="active" data-filter="*">see all</li>
                    <li data-filter=".cat1">Branding</li>
                    <li data-filter=".cat2">Creative</li>
                    <li data-filter=".cat3">Illustration</li>
                    <li data-filter=".cat4">Photoshop</li>
                </ul>
            </div>
        </div>
        <div class="row grid text-center">
            @foreach($portfolioItems as $item)
            <div class="col-xl-4 col-lg-4 col-md-6 grid-item mb-30 {{ $item->categories }}">
                <div class="portfolio-item">
                    <div class="fortfolio-thumb">
                        <img src="{{ asset($item->image) }}" alt="{{ $item->title }}">
                    </div>
                    <div class="portfolio-content">
                        <div class="content-view">
                            <a href="{{ $item->link }}"></a>
                            <span>By: {{ $item->author }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- portfolio area end -->

<!-- blog area start -->
<section class="blog-area section-bg-three section-notch pt-120 pb-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="section-title section-title-white text-center mb-55">
                    <h2 class="title">Our Latest News</h2>
                    <p>Here is what you can expect from a house cleaning from a Handy professional. Download the
                        app to share further cleaning details and instructions!</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($latestPosts as $post)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="blog-item mb-30">
                    <div class="blog-image">
                        <!-- <a href="{{ route('blog.show', $post) }}"> -->
                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                        <!-- </a> -->
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                        </h4>
                        <a class="blog-btn" href="{{ route('blog.show', $post) }}">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- blog area end -->

<!-- brand area start -->
<section class="brand-area pt-110 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="brand-active owl-carousel">
                    @foreach($brands as $brand)
                    <div class="single-brand">
                        <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- brand area end -->
@endsection
