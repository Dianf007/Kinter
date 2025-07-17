@extends('layouts.app')

@section('title', 'Blog')

@section('content')
    <!-- blog start -->
    <section class="blog-content-area pt-120 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-wrapper mb-30">
                        {{-- Dynamic posts loop --}}
                        @foreach($posts as $post)
                        <article class="post-item format-{{ $post->post_type }} mb-30">
                            <div class="post-thumb">
                                @if($post->post_type == 'video')
                                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" />
                                    <a class="popup-video video-icon" href="{{ $post->video_link }}">
                                        <i class="far fa-play"></i>
                                    </a>
                                @else
                                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" />
                                @endif
                            </div>
                            <div class="post-content">
                                <div class="post-meta mb-20">
                                    <span><a href="#"><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('d M Y') }}</a></span>
                                    <span><a href="#"><i class="far fa-user"></i> {{ $post->author->name ?? 'Admin' }}</a></span>
                                    <span><a href="#"><i class="far fa-comments"></i> {{ $post->comment_count }} Comments</a></span>
                                </div>
                                <h4 class="post-title">
                                    <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                                </h4>
                                <div class="post-text">
                                    <p>{{ Str::limit($post->excerpt ?? strip_tags($post->content), 150) }}</p>
                                </div>
                                <div class="read-more mt-20">
                                    <a class="thm-btn" href="{{ route('blog.show', $post) }}">Read More</a>
                                </div>
                            </div>
                        </article>
                        @endforeach

                        {{-- Pagination links --}}
                        <div class="pagination pt-20 mb-30">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        {{-- Search widget --}}
                        <div class="widget mb-30">
                            <div class="search-form">
                                <form action="{{ route('blog.index') }}" method="GET">
                                    <input type="text" name="q" placeholder="Search Here..." />
                                    <button type="submit"><i class="far fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        {{-- Categories widget --}}
                        <div class="widget mb-30">
                            <h2 class="widget-header">Post Category</h2>
                            <ul class="widget-wrapper">
                                @foreach($categories as $category)
                                <li>
                                    <a class="d-flex justify-content-between" href="{{ route('blog.index', ['category' => $category->slug]) }}">
                                        <span><i class="far fa-angle-double-right"></i> {{ $category->name }}</span>
                                        <span>{{ $category->posts->count() }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Popular Posts widget --}}
                        <div class="widget mb-30">
                            <h2 class="widget-header">Most Popular Posts</h2>
                            <ul class="widget-wrapper widget-post">
                                @foreach($popularPosts as $p)
                                <li class="d-flex flex-wrap justify-content-between">
                                    <div class="widget-post-thumb">
                                        <a href="{{ route('blog.show', $p) }}"><img src="{{ asset($p->image) }}" alt="{{ $p->title }}"></a>
                                    </div>
                                    <div class="widget-post-content">
                                        <h5><a href="{{ route('blog.show', $p) }}">{{ $p->title }}</a></h5>
                                        <span>{{ $p->created_at->format('d M Y') }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Tags widget --}}
                        <div class="widget mb-30">
                            <h2 class="widget-header">Popular Tags</h2>
                            <div class="tagcloud">
                                @foreach($tags as $tag)
                                <a class="sidebar-tag" href="{{ route('blog.index', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog end -->
@endsection
