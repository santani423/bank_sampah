@extends('layouts.landingPage')

@section('title', 'Kegiatan')

@section('content')
<section class="page-header">
    <div class="page-header-bg"
        style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
    <div class="container">
        <div class="page-header__inner">
            <h2>Kegiatan</h2>
        </div>
    </div>
</section>

<!--Gallery Page Start-->
<section class="gallery-page">
    <div class="container">
        <div class="row masonary-layout">
            @foreach($kegiatan as $item)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <!--Gallery Page Single-->
                <div class="gallery-page__single">
                    <div class="gallery-page__img">
                        <img src="{{ asset($item->foto_kegiatan) }}" alt="Deskripsi gambar" style="cursor:pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#imageModal"
                            data-img="{{ asset($item->foto_kegiatan) }}"
                            data-desc="{{$item->deskripsi}}">
                        <div class="gallery-page__icon">
                            <a class="img-popup" href="javascript:void(0);"
                                data-bs-toggle="modal"
                                data-bs-target="#imageModal"
                                data-img="{{ asset($item->foto_kegiatan) }}"
                                data-desc="{{$item->deskripsi}}">
                                <span class="icon-plus-symbol"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>  
            @endforeach
        </div>
    </div>
</section>
<!--Gallery Page End-->

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="" class="img-fluid mb-3">
        <p id="modalDesc"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function (event) {
        var trigger = event.relatedTarget;
        var img = trigger.getAttribute('data-img');
        var desc = trigger.getAttribute('data-desc');
        document.getElementById('modalImage').src = img;
        document.getElementById('modalDesc').textContent = desc;
    });
});
</script>
@endsection
@endsection