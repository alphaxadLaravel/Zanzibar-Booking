@extends('website.layouts.app')

@section('pages')
<section class="page-banner position-relative d-flex align-items-center" style="
        background: url('https://www.zanzibarbookings.com/storage/2025/02/19/zanzibarbookingscom1-1681820030-1920x768-large-1739955733-1920x768.jpg') center center/cover no-repeat;
        min-height: 280px;
        padding-top: 80px;
        padding-bottom: 60px;
    ">
    <div style="background: rgba(0,0,0,0.55); position: absolute; inset: 0; z-index: 1;"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bolder text-white mb-3" data-aos="fade-up" style="font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">
                    <strong>{{ strtoupper($page->page) }}</strong>
                </h1>
            </div>
        </div>
    </div>

    <div
        style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; transform: rotate(180deg); z-index: 2;">
        <svg style="position: relative; display: block; width: calc(100% + 1.3px); height: 50px;" data-name="Layer 1"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path
                d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                style="fill: #ffffff;"></path>
        </svg>
    </div>
</section>

<!-- Page Content -->
<section class="page-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0" data-aos="fade-up">
                    <div class="card-body p-5">
                        <div class="content-wrapper" style="line-height: 1.8; font-size: 1.05rem; color: #333;">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>

                @if($slug === 'about-us')
                <!-- Why Choose Us Section (Only for About Us) -->
                <div class="row mt-5 g-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="icon-box mb-3">
                                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Trusted & Safe</h5>
                                <p class="card-text text-muted">All our packages include insurance coverage and
                                    professional support for your peace of mind.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="icon-box mb-3">
                                    <i class="fas fa-user-friends fa-3x text-success"></i>
                                </div>
                                <h5 class="card-title fw-bold">Local Expertise</h5>
                                <p class="card-text text-muted">As a Zanzibar-based company, we know the hidden gems and
                                    cultural traditions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="icon-box mb-3">
                                    <i class="fas fa-heart fa-3x text-danger"></i>
                                </div>
                                <h5 class="card-title fw-bold">Personalized Service</h5>
                                <p class="card-text text-muted">Every traveler receives attentive care from planning to
                                    departure.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="icon-box mb-3">
                                    <i class="fas fa-globe fa-3x text-info"></i>
                                </div>
                                <h5 class="card-title fw-bold">Diverse Experiences</h5>
                                <p class="card-text text-muted">Luxury, adventure, culture, volunteering, or family fun
                                    - we have it all.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($slug === 'become-a-partner')
                <!-- Partnership Benefits (Only for Become a Partner) -->
                <div class="row mt-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-5 text-center">
                                <h3 class="fw-bold mb-4">Ready to Partner With Us?</h3>
                                <p class="lead mb-4 text-muted">Join hundreds of successful partners and grow your business with
                                    Zanzibar Bookings</p>
                                <a href="{{ route('contact-us') }}" class="btn btn-primary btn-lg px-5 py-3">
                                    <i class="fas fa-handshake me-2"></i>Get Started Today
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($slug !== 'become-a-partner')
                <!-- Contact CTA for all pages (except partner page) -->
                <div class="text-center mt-5 pt-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-4 bg-light rounded-3">
                        <h4 class="fw-bold mb-3">Have Questions?</h4>
                        <p class="text-muted mb-4">Our team is here to help you. Get in touch with us today!</p>
                        <a href="{{ route('contact-us') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .page-content .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .page-content .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }

    .content-wrapper {
        word-wrap: break-word;
    }

    .content-wrapper p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .content-wrapper strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .content-wrapper ul,
    .content-wrapper ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .content-wrapper li {
        margin-bottom: 0.5rem;
    }

    .content-wrapper h1,
    .content-wrapper h2,
    .content-wrapper h3,
    .content-wrapper h4,
    .content-wrapper h5,
    .content-wrapper h6 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.7);
    }

    .icon-box i {
        transition: transform 0.3s ease;
    }

    .card:hover .icon-box i {
        transform: scale(1.1);
    }
</style>
@endpush

@endsection