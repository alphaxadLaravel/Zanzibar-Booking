@extends('website.layouts.app')

@section('pages')

<!-- Page Banner -->
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
                    <strong>CONTACT US</strong>
                </h1>
            </div>
        </div>
    </div>

    <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; transform: rotate(180deg); z-index: 2;">
        <svg style="position: relative; display: block; width: calc(100% + 1.3px); height: 50px;" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" style="fill: #ffffff;"></path>
        </svg>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center g-4">
            <!-- Contact Form -->
            <div class="col-lg-5 col-md-6">
                <div class="card shadow-sm border-0 h-100" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4 fw-bold">Send us a Message</h3>
                        
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="gmz-loader" style="display: none;">
                                <div class="loader-inner">
                                    <div class="spinner-grow text-info align-self-center loader-lg"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="full-name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" id="full-name" placeholder="Enter your full name" value="{{ old('full_name') }}" required />
                                @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter your email address" value="{{ old('email') }}" required />
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" id="subject" placeholder="Subject" value="{{ old('subject') }}" required />
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="content" rows="5" class="form-control @error('content') is-invalid @enderror" id="content" placeholder="Type your message here..." required>{{ old('content') }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="gmz-message mb-3"></div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-5 col-md-6">
                <div class="card shadow-sm border-0 h-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <h3 class="mb-4 fw-bold">Get in Touch</h3>
                        <p class="text-muted mb-4">We're here to help and answer any question you might have. We look forward to hearing from you!</p>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt fa-lg text-primary me-3"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Address</h6>
                                    <p class="text-muted mb-0">{{ $systemSettings->address ?? 'Zanzibar, Tanzania' }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone-alt fa-lg text-primary me-3"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Phone</h6>
                                    <p class="text-muted mb-0">
                                        <a href="tel:{{ str_replace(' ', '', $systemSettings->phone ?? '+255774378835') }}" class="text-decoration-none text-muted">{{ $systemSettings->phone ?? '+255 774 378835' }}</a>
                                    </p>
                                    @if($systemSettings && $systemSettings->secondary_phone)
                                    <p class="text-muted mb-0">
                                        <a href="tel:{{ str_replace(' ', '', $systemSettings->secondary_phone) }}" class="text-decoration-none text-muted">{{ $systemSettings->secondary_phone }}</a>
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope fa-lg text-primary me-3"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Email</h6>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:{{ $systemSettings->email ?? 'info@zanzibarbookings.com' }}" class="text-decoration-none text-muted">{{ $systemSettings->email ?? 'info@zanzibarbookings.com' }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <h6 class="mb-3 fw-bold">Quick Contact</h6>
                            <a href="{{ $systemSettings->whatsapp_url ?? 'https://wa.me/message/JMDWFIGBWX5TI1' }}" target="_blank" class="btn btn-success w-100 py-3">
                                <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4" data-aos="fade-up">
                    <h3 class="fw-bold mb-2">Find Us Here</h3>
                    <p class="text-muted">Visit us at our location in Zanzibar, Tanzania</p>
                </div>
                <div class="card shadow-sm border-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-0">
                        <div style="height: 450px; border-radius: 8px; overflow: hidden;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5!2d39.2!3d-6.1659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMDknNTcuMiJTIDM5wrAxMicwMC4wIkU!5e0!3m2!1sen!2stz!4v1234567890123!5m2!1sen!2stz"
                                width="100%" 
                                height="450" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection