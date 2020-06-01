@extends($themeName."::layouts.master")
@section('content')
    <main id="articles">
        <?php $latestPost = blog_latest_post(); ?>
        @if($latestPost)
            <section>
                <div class="articles">
                    <div class="container-fluid" style="background-color: #F5F5F5">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="articles__left">
                                        <p><a href="{{ page(1)->getUrl() }}">{{ page(1)->title }}</a> / <a
                                                href="{{ page(3)->getUrl() }}">{{ page(3)->title }}</a></p>
                                        <img src="{{ blog_post_image($latestPost) }}" class="img-fluid"
                                             alt="{{ $latestPost->title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="articles__right">
                                        @foreach($latestPost->categories as $latestPostCategory)
                                            <a href="{{ $latestPostCategory->getUrl() }}">{{ $latestPostCategory->name }}</a>
                                        @endforeach
                                        <h3>{{ $latestPost->title }}</h3>
                                        <p class="text">{{ $latestPost->excerpt }}</p>
                                        <a href="{{ $latestPost->getUrl() }}" class="btn-stonex">
                                            <span>Chi tiết</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <section>
            <div class="outproject">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $categories = blog_post_categories();
                            ?>
                            <ul class="nav justify-content-center d-none d-lg-flex">
                                <li class="nav-item">
                                    <a href="{{ page(3)->getUrl() }}" class="nav-link">Tất cả</a>
                                </li>
                                @foreach($categories as $c)
                                    <li class="nav-item @if($c->id === $category->id) active @endif">
                                        <a href="{{ $c->getUrl() }}" class="nav-link">{{ $c->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <?php // $posts = blog_posts(); ?>
                            <div class="row">
                                @foreach($posts as $post)
                                    <div class="col-md-6 article__link">
                                        <a href="{{ $post->getUrl() }}" class="post-{{ $post->id }}">
                                            <div class="outproject__img text-center">
                                                <div class="img">
                                                    <img src="{{ blog_post_image($post) }}" class="img-fluid"
                                                         alt="{{ $post->title }}">
                                                </div>
                                                <p>{{ $post->created_at->format("d J Y") }}</p>
                                                <h4>{{ $post->title }}</h4>
                                                <span class="view_detail">Chi tiết</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                                <div class="col-12 justify-content-center text-center">
                                    {{ $posts->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('simple::partials/newsletter')
    </main>
@endsection
