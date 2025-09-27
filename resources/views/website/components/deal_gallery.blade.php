{{-- Deal Gallery Component --}}
@props(['deal'])

<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $deal->photos->count() }}">
        @forelse($deal->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img src="{{ asset('storage/' . $photo->photo) }}" alt="{{ $deal->title }}" class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                loading="lazy" />
        </a>
        @empty
        <a
            href="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}"
                alt="{{ $deal->title }}" class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                loading="lazy" />
        </a>
        @endforelse
    </div>
</section>
<script>
    // Prevent burst/flash on page opening, fade in after page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.gallery-img').forEach(function(img) {
                img.style.opacity = '1';
            });
        }, 100); // slight delay to ensure page is ready
    });
</script>
