@props(['galleries' => null])


<div id="carouselGallery" @class(['carousel slide', 'd-none' => is_null($galleries) || !count($galleries)]) data-ride="carousel">
    <div class="carousel-inner" id="carousel-gallery">
        @if ($galleries)     
            @foreach ($galleries as $galery)
                <div @class(['carousel-item', 'active' => $loop->first]) id="gallery-{{$galery->id}}">
                    <img class="d-block w-100" src="{{ $galery->photo_url }}">
                </div>
            @endforeach
        @endif
    </div>
    <a class="carousel-control-prev" href="#carouselGallery" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselGallery" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<style>
    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e");
    }
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e");
    }
    .carousel-indicators li {
        background-color: #000;
    }
</style>