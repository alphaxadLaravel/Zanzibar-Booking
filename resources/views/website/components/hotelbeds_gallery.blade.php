{{-- Hotelbeds gallery — same carousel pattern as deal_gallery --}}
@props(['images' => [], 'title' => 'Hotel'])

<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ count($images) }}">
        @foreach($images as $imageUrl)
            <a href="{{ $imageUrl }}">
                <img src="{{ $imageUrl }}" alt="{{ $title }}" class="gallery-img"
                    style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                    loading="lazy" />
            </a>
        @endforeach
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            document.querySelectorAll('.gallery-img').forEach(function (img) {
                img.style.opacity = '1';
            });
        }, 100);
    });
</script>
