@extends('layouts.landingPage')

@section('title', 'About Us')
@section('description', 'Learn more about our company, our mission, and the team behind our success.')

@section('content')
    <!--Page Header Start-->
    <section class="page-header">
        <div class="page-header-bg"
            style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
        <div class="container">
            <div class="page-header__inner">
                <h2>About Us</h2>
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>About</li>
                </ul>
            </div>
        </div>
    </section>
    <!--Page Header End-->

    <!--Always Working Start-->
    <section class="always-working">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="always-working__left">
                        <div class="section-title text-left">
                            <h2 class="section-title__title">Visi</h2>
                        </div>
                        <p class="always-working__text">
                            Menjadi pelopor dalam pengelolaan limbah botol plastik yang inovatif dan berkelanjutan,
                            menciptakan dunia yang bebas dari polusi plastik dan berkelanjutan bagi generasi mendatang
                        </p>
                        <div class="section-title text-left">
                            <h2 class="section-title__title">Misi</h2>
                        </div>
                        <ul class="list-unstyled always-working__points">
                            <li>
                                <div class="icon"><span class="fa fa-check"></span></div>
                                <div class="text">
                                    <p>Mengumpulkan dan mendaur ulang limbah botol plastik secara efisien dan ramah
                                        lingkungan.</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><span class="fa fa-check"></span></div>
                                <div class="text">
                                    <p>Mendorong partisipasi aktif masyarakat dalam kegiatan daur ulang melalui program
                                        insentif dan edukasi.</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><span class="fa fa-check"></span></div>
                                <div class="text">
                                    <p>Membangun sistem yang transparan dan berbasis data dalam pelaporan dan monitoring
                                        hasil daur ulang.</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><span class="fa fa-check"></span></div>
                                <div class="text">
                                    <p>Terus berinovasi dalam teknologi daur ulang untuk meningkatkan kualitas dan nilai
                                        produk daur ulang.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="always-working__right">
                        <div class="always-working__img wow slideInRight" data-wow-delay="100ms" data-wow-duration="2500ms">
                            <img src="https://mitraplastiknusantara.com/wp-content/uploads/2024/11/foto1.png"
                                alt="Visi Misi" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Always Working End-->

    <!--Testimonial One Start-->
    <section class="testimonial-one">
        <div class="testimonial-one-bg"
            style="background-image: url({{ asset('assets/images/backgrounds/testimonial-one-bg.jpg') }});"></div>
        <div class="container">
            <div class="row">
                @php
                    $testimonials = [
                        [
                            'title' => 'Kenapa Harus Multi Layer Plastik',
                            'points' => [
                                '<b>Polusi Plastik :</b> Limbah plastik multilayer sulit terurai di alam dan dapat mencemari tanah, air, serta udara jika dibiarkan begitu saja atau dibakar tanpa pengolahan yang benar. Proses biodegradasi plastik ini bisa memakan waktu hingga ratusan tahun.',
                                '<b>Pencemaran Mikroplastik:</b> Plastik multilayer yang terdegradasi menjadi mikroplastik dapat masuk ke dalam rantai makanan dan mencemari ekosistem, membahayakan kehidupan hewan dan manusia.',
                                '<b>Konservasi Ruang di TPA:</b> Limbah plastik multilayer yang tidak diolah mengisi tempat pembuangan akhir (TPA) dan mempercepat penuhnya kapasitas TPA.',
                            ],
                        ],
                        [
                            'title' => 'Pengurangan Penggunaan Bahan Baru',
                            'points' => [
                                '<b>Efisiensi Sumber Daya:</b> Daur ulang limbah multilayer membantu mengurangi kebutuhan bahan baku plastik baru, yang sebagian besar berasal dari minyak bumi. Ini mendukung konservasi sumber daya alam yang tidak terbarukan.',
                                '<b>Ekonomi Sirkular:</b> Mengolah limbah plastik menjadi produk baru, seperti tali rafia, membantu mendukung model ekonomi sirkular di mana limbah digunakan kembali dalam siklus produksi.',
                            ],
                        ],
                        [
                            'title' => 'Pengurangan Emisi Karbon',
                            'points' => [
                                '<b>Pengurangan Jejak Karbon:</b> Mengolah limbah plastik menghasilkan lebih sedikit emisi gas rumah kaca dibandingkan memproduksi plastik baru dari bahan mentah.',
                                '<b>Ekonomi Sirkular:</b> Mengolah limbah plastik menjadi produk baru, seperti tali rafia, membantu mendukung model ekonomi sirkular di mana limbah digunakan kembali dalam siklus produksi.',
                                '<b>Pencegahan Emisi Beracun:</b> Pembakaran limbah plastik multilayer tanpa pengolahan dapat menghasilkan zat beracun seperti dioksin, yang berbahaya bagi lingkungan dan kesehatan manusia.',
                            ],
                        ],
                    ];
                @endphp

                @for ($i = 0; $i < 2; $i++)
                    <div class="col-xl-6 col-lg-6">
                        <div class="testimonial-one__right">
                            <div class="owl-carousel owl-theme thm-owl__carousel testimonial-one__carousel"
                                data-owl-options='{
                                "loop": true,
                                "items": 1,
                                "autoplay": true,
                                "margin": 30,
                                "nav": false,
                                "dots": false,
                                "smartSpeed": 500,
                                "autoplayTimeout": 10000,
                                "navText": ["<span class=\"fa fa-angle-left\"></span>","<span class=\"fa fa-angle-right\"></span>"],
                                "responsive": {
                                    "0": {"items": 1},
                                    "768": {"items": 1},
                                    "1200": {"items": 1}
                                }
                            }'>
                                @foreach ($testimonials as $testimonial)
                                    <div class="testimonial-one__single">
                                        <div class="testimonial-one__feedback-box">
                                            <h3 class="testimonial-one__title">{{ $testimonial['title'] }}</h3>
                                        </div>
                                        <ul class="testimonial-one__text">
                                            @foreach ($testimonial['points'] as $point)
                                                <li>{!! $point !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endfor

            </div>
        </div>
    </section>
    <!--Testimonial One End-->
    <!-- Google Map Start -->
    <section class="google-map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d273367.46585342725!2d106.30319369453126!3d-6.1791918999999975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fed8e67cb0ad%3A0x2888b42a70065bd2!2sGlobal%20Institute%20%7C%20Institut%20Teknologi%20dan%20Bisnis%20Bina%20Sarana%20Global!5e1!3m2!1sid!2sid!4v1750514460075!5m2!1sid!2sid"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
@endsection

@section('scripts')
@endsection

@section('styles')
<style>
    .google-map {
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        position: relative;
        left: 50%;
        right: 50%;
        transform: translateX(-50%);
    }
    .google-map iframe {
        width: 100vw !important;
        height: 50vh !important;
        display: block;
        border: 0;
        margin: 0 auto;
        max-width: 100%;
    }
    @media (max-width: 768px) {
        .google-map iframe {
            height: 300px !important;
        }
    }
</style>
@endsection
