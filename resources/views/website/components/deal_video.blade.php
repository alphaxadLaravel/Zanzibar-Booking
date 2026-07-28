{{-- Deal Video Component --}}
@props(['deal'])

@if($deal->video_link)
<section class="video-section">
    <h4 class="section-title">Video</h4>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="video-container"
                    style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000; border-radius: 8px; overflow: hidden;">
                    @php
                    $videoUrl = $deal->video_link;
                    $embedUrl = \App\Support\VideoEmbedHelper::embedUrl($videoUrl);
                    @endphp

                    @if($embedUrl)
                    <iframe src="{{ $embedUrl }}"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                        frameborder="0" allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                    @else
                    <div
                        style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; text-align: center;">
                        <div>
                            <i class="fas fa-play-circle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <p>Video preview not available</p>
                            <a href="{{ $videoUrl }}" target="_blank" class="btn btn-primary">Watch Video</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif
