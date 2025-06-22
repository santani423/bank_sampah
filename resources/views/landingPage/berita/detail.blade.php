@extends('layouts.landingPage')

@section('title', $berita->judul ?? 'Detail Berita')

@section('content')
  
     <!--News Sidebar Start-->
        <section class="news-sidebar">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="news-sidebar__left">
                            <div class="news-sidebar__content">
                                <!--News Sidebar Single-->
                               
                                <div class="news-sidebar__single">
                                    <div class="news-sidebar__img">
                                        <img src="{{ asset($berita->thumbnail) }}" alt="">
                                        <div class="news-sidebar__date">
                                            <p>{{ \Carbon\Carbon::parse($berita->published_at)->format('M d') }}</p>
                                        </div>
                                    </div>
                                    <div class="news-sidebar__content-box">
                                        <ul class="list-unstyled news-one__meta">
                                            <li><span class="fa fa-user"></span>By  {{ $berita->author }} 
                                             
                                        </ul>
                                        <h3 class="news-sidebar__title"><a href="{{ route('berita.detail', ['id' => $berita->id]) }}">{{$berita->title}}</a></h3>
                                        <p class="news-sidebar__text">{!!$berita->content!!}</p>
                                         
                                    </div>
                                </div>  
                            </div>
                          
                          
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar">
                            
                        
                            <div class="sidebar__single sidebar__category">
                                <h3 class="sidebar__title">Categories</h3>
                                <ul class="sidebar__category-list list-unstyled">
                                    @foreach($kategoriNews as $category)
                                    <li><a href="{{ route('berita.kategori', ['id' => $category->id]) }}">{{ $category->name }} <span class="fa fa-arrow-right"></span></a></li>
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