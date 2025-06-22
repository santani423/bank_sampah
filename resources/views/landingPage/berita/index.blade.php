@extends('layouts.landingPage')

@section('title', 'Berita')

@section('content')
 <section class="page-header">
    <div class="page-header-bg"
        style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
    <div class="container">
        <div class="page-header__inner">
            <h2>Berita</h2>
        </div>
    </div>
</section>
     <!--News Sidebar Start-->
        <section class="news-sidebar">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="news-sidebar__left">
                            <div class="news-sidebar__content">
                                <!--News Sidebar Single-->
                                @foreach($news as $item)
                                <div class="news-sidebar__single">
                                    <div class="news-sidebar__img">
                                        <img src="{{ asset($item->thumbnail) }}" alt="">
                                        <div class="news-sidebar__date">
                                            <p>{{ \Carbon\Carbon::parse($item->published_at)->format('M d') }}</p>
                                        </div>
                                    </div>
                                    <div class="news-sidebar__content-box">
                                        <ul class="list-unstyled news-one__meta">
                                            <li><span class="fa fa-user"></span>By  {{ $item->author }} 
                                             
                                        </ul>
                                        <h3 class="news-sidebar__title"><a href="news-details.html">{{$item->title}}</a></h3>
                                        {{-- <p class="news-sidebar__text">There are many variations of passages of Lorem
                                            Ipsum available, but majority have suffered alteration in some form, by
                                            injected humour, or randomised words which don't look even slightly
                                            believable. If you are going to use a passage of Lorem Ipsum.</p>
                                        <div class="news-sidebar__bottom">
                                            <a href="news-details.html" class="news-sidebar__arrow"><span
                                                    class="fa fa-arrow-right"></span></a>
                                            <a href="news-details.html" class="news-sidebar__read-more">Read More</a>
                                        </div> --}}
                                    </div>
                                </div> 
                                @endforeach
                            </div>
                          
                          
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar">
                            
                        
                            <div class="sidebar__single sidebar__category">
                                <h3 class="sidebar__title">Categories</h3>
                                <ul class="sidebar__category-list list-unstyled">
                                    @foreach($kategoriNews as $category)
                                    <li><a href="{{ route('berita.kategori', $category->id) }}">{{ $category->name }} <span
                                                class="fa fa-arrow-right"></span></a></li>
                                    @endforeach
                                     
                                </ul>
                            </div>
                             
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--News Sidebar End-->
@endsection