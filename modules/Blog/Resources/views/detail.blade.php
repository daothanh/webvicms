@extends($themeName."::layouts.master")
@section('content')
    <main id="detail_article">
        <section id="carousel">
            <div id="carouselId_product" class="carousel slide carousel-fade " data-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active detail__article--box">
                        {!! blog_post_image($post, ['title' => $post->title, 'alt' => $post->title, 'class' => 'img-fluid']) !!}
                        <div class="detail__article--box__one">
                            <div class="row">
                                <div class="col-md-6 left d-none d-md-block">
                                    <h3>{{ $post->title }}</h3>
                                </div>
                                <div class="col-6 right d-flex d-md-block">
                                    <div class="">
                                        <h5>Category</h5>
                                        <p>{{ $post->categories->pluck('name')->join(', ') }}</p>
                                    </div>
                                    <div class="d-md-block pl-50px">
                                        <h5 class="pt-md-2">Share</h5>
                                        <div class="d-flex">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urldecode($post->getUrl()) }}"
                                               target="_blank">
                                                <svg width="11" height="20" viewBox="0 0 11 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path id="footer facebook"
                                                          d="M6.93536 19.2706V10.5988H9.71347L10.1672 6.74471H6.93536V4.86775C6.93536 3.87532 6.96211 2.89059 8.42547 2.89059H9.90766V0.134894C9.90766 0.0934623 8.63453 0 7.34653 0C4.6566 0 2.9723 1.59657 2.9723 4.52859V6.74471H0V10.5988H2.9723V19.2706H6.93536Z"
                                                          fill="#A6A6A6"/>
                                                </svg>
                                            </a>
                                            <a href="https://plus.google.com/share?url={{ urldecode($post->getUrl()) }}"
                                               target="_blank">
                                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path id="footer gg plus" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M14.8601 8.22034C14.8601 7.73503 14.8106 7.36089 14.7412 6.98675V6.98671H7.88512V9.53485H12.0067C11.8383 10.6168 10.7583 12.7301 7.88512 12.7301C5.40821 12.7301 3.38702 10.637 3.38702 8.04848C3.38702 5.45992 5.40821 3.36683 7.88512 3.36683C9.30191 3.36683 10.2431 3.98363 10.7781 4.50944L12.7498 2.57814C11.4816 1.36473 9.84685 0.636719 7.88512 0.636719C3.87254 0.636719 0.622803 3.95329 0.622803 8.04848C0.622803 12.1437 3.87254 15.4602 7.88512 15.4602C12.076 15.4602 14.8601 12.4571 14.8601 8.22034ZM21.7541 5.08378V7.23524H23.8622V9.39704H21.7541V11.5485H19.6359V9.39704H17.5278V7.23524H19.6359V5.08378H21.7541Z"
                                                          fill="#A6A6A6"/>
                                                </svg>
                                            </a>
                                            <a href="https://twitter.com/share?url={{ urldecode($post->getUrl()) }}&amp;text={{ $post->title }}&amp;hashtags=simplesharebuttons"
                                               target="_blank">
                                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path id="footer tweeter"
                                                          d="M17.2705 4.33101C17.2825 4.49313 17.2825 4.65529 17.2825 4.81742C17.2825 9.76243 13.3887 15.4602 6.27193 15.4602C4.0794 15.4602 2.04264 14.8464 0.329346 13.781C0.640863 13.8158 0.940358 13.8273 1.26386 13.8273C3.07297 13.8273 4.73835 13.2367 6.06825 12.2292C4.36694 12.1944 2.9412 11.1174 2.44997 9.63506C2.68961 9.66978 2.92921 9.69295 3.18084 9.69295C3.52827 9.69295 3.87575 9.64661 4.19922 9.56559C2.42603 9.21813 1.0961 7.71263 1.0961 5.89443V5.84813C1.61126 6.12607 2.21036 6.29978 2.84531 6.32292C1.80296 5.65121 1.12007 4.50472 1.12007 3.20765C1.12007 2.51281 1.31173 1.87586 1.64722 1.31997C3.55221 3.58982 6.41568 5.07215 9.62656 5.23431C9.56667 4.95637 9.53071 4.66688 9.53071 4.37735C9.53071 2.31593 11.256 0.636719 13.4006 0.636719C14.5148 0.636719 15.5212 1.08837 16.2281 1.81797C17.1027 1.65584 17.9414 1.34314 18.6842 0.914662C18.3966 1.78325 17.7856 2.51284 16.9829 2.97605C17.7617 2.89502 18.5165 2.68652 19.2114 2.39703C18.6843 3.13817 18.0253 3.79826 17.2705 4.33101Z"
                                                          fill="#A6A6A6"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="detail__article">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="detail__article--text">
                                @if($post->excerpt)
                                    <div class="text__detail-1">{!! $post->excerpt !!}</div>
                                @endif
                                {!! $post->body !!}
                                <hr>
                                <div class="d-flex align-content-center share" style="padding-left: 15px">
                                    <h5 class="pt-2">Share</h5>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urldecode($post->getUrl()) }}"
                                       target="_blank">
                                        <svg width="11" height="20" viewBox="0 0 11 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path id="footer facebook"
                                                  d="M6.93536 19.2706V10.5988H9.71347L10.1672 6.74471H6.93536V4.86775C6.93536 3.87532 6.96211 2.89059 8.42547 2.89059H9.90766V0.134894C9.90766 0.0934623 8.63453 0 7.34653 0C4.6566 0 2.9723 1.59657 2.9723 4.52859V6.74471H0V10.5988H2.9723V19.2706H6.93536Z"
                                                  fill="#A6A6A6"/>
                                        </svg>
                                    </a>
                                    <a href="https://plus.google.com/share?url={{ urldecode($post->getUrl()) }}"
                                       target="_blank">
                                        <svg width="24" height="16" viewBox="0 0 24 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path id="footer gg plus" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M14.8601 8.22034C14.8601 7.73503 14.8106 7.36089 14.7412 6.98675V6.98671H7.88512V9.53485H12.0067C11.8383 10.6168 10.7583 12.7301 7.88512 12.7301C5.40821 12.7301 3.38702 10.637 3.38702 8.04848C3.38702 5.45992 5.40821 3.36683 7.88512 3.36683C9.30191 3.36683 10.2431 3.98363 10.7781 4.50944L12.7498 2.57814C11.4816 1.36473 9.84685 0.636719 7.88512 0.636719C3.87254 0.636719 0.622803 3.95329 0.622803 8.04848C0.622803 12.1437 3.87254 15.4602 7.88512 15.4602C12.076 15.4602 14.8601 12.4571 14.8601 8.22034ZM21.7541 5.08378V7.23524H23.8622V9.39704H21.7541V11.5485H19.6359V9.39704H17.5278V7.23524H19.6359V5.08378H21.7541Z"
                                                  fill="#A6A6A6"/>
                                        </svg>
                                    </a>
                                    <a href="https://twitter.com/share?url={{ urldecode($post->getUrl()) }}&amp;text={{ $post->title }}&amp;hashtags=simplesharebuttons"
                                       target="_blank">
                                        <svg width="20" height="16" viewBox="0 0 20 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path id="footer tweeter"
                                                  d="M17.2705 4.33101C17.2825 4.49313 17.2825 4.65529 17.2825 4.81742C17.2825 9.76243 13.3887 15.4602 6.27193 15.4602C4.0794 15.4602 2.04264 14.8464 0.329346 13.781C0.640863 13.8158 0.940358 13.8273 1.26386 13.8273C3.07297 13.8273 4.73835 13.2367 6.06825 12.2292C4.36694 12.1944 2.9412 11.1174 2.44997 9.63506C2.68961 9.66978 2.92921 9.69295 3.18084 9.69295C3.52827 9.69295 3.87575 9.64661 4.19922 9.56559C2.42603 9.21813 1.0961 7.71263 1.0961 5.89443V5.84813C1.61126 6.12607 2.21036 6.29978 2.84531 6.32292C1.80296 5.65121 1.12007 4.50472 1.12007 3.20765C1.12007 2.51281 1.31173 1.87586 1.64722 1.31997C3.55221 3.58982 6.41568 5.07215 9.62656 5.23431C9.56667 4.95637 9.53071 4.66688 9.53071 4.37735C9.53071 2.31593 11.256 0.636719 13.4006 0.636719C14.5148 0.636719 15.5212 1.08837 16.2281 1.81797C17.1027 1.65584 17.9414 1.34314 18.6842 0.914662C18.3966 1.78325 17.7856 2.51284 16.9829 2.97605C17.7617 2.89502 18.5165 2.68652 19.2114 2.39703C18.6843 3.13817 18.0253 3.79826 17.2705 4.33101Z"
                                                  fill="#A6A6A6"/>
                                        </svg>
                                    </a>
                                </div>
                                @if($relatedPosts->isNotEmpty())
                                    <div class="bottom">
                                        <h5>Related articles</h5>
                                        <div class="row">
                                            @foreach($relatedPosts as $rPost)
                                                <div class="col-md-6 pr-lg-4">
                                                    {!! blog_post_image($rPost, ['title' => $rPost->title, 'alt' => $rPost->title, 'class' => 'w-100'], 'thumbnail') !!}
                                                    <p>{{ $rPost->title }}</p>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($post->quote)
                            <div class="col-md-5 d-none d-md-block">
                                <div class="blockquote">
                                    <svg width="60" height="35" viewBox="0 0 60 35" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g id="QUOTE ICON">
                                            <path id="Path" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M20.5819 0L0 35H14.4181L35 0H20.5819Z" fill="#998543"/>
                                            <path id="Path Copy" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M45.5819 0L25 35H39.4181L60 0H45.5819Z" fill="#998543"/>
                                        </g>
                                    </svg>
                                    <h2>{{ $post->quote }}</h2>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
