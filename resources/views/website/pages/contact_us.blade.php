@extends('website.layouts.app')

@section('pages')

<section 
    class="partner-form position-relative d-flex align-items-center"
    style="
        background: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center center/cover no-repeat;
        min-height: 700px;
        padding-top: 60px;
        padding-bottom: 60px;
    "
>
    <div style="background: rgba(0,0,0,0.45); position: absolute; inset: 0; z-index: 1;"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center align-items-center" style="min-height: 500px;">
            <div class="col-lg-6 mb-4">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h2 class="card-title mb-4 text-primary">Contact Us</h2>
                        <form class="gmz-form-action" action="https://www.zanzibarbookings.com/contact-us" method="POST" autocomplete="off">
                            <div class="gmz-loader" style="display: none;">
                                <div class="loader-inner">
                                    <div class="spinner-grow text-info align-self-center loader-lg"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label for="full-name" class="form-label">Full Name <span class="required text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control gmz-validation"
                                        data-validation="required" id="full-name" placeholder="Enter your full name" required />
                                </div>
                                <div class="form-group col-12 mb-3">
                                    <label for="email" class="form-label">Email <span class="required text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control gmz-validation"
                                        data-validation="required" id="email" placeholder="Enter your email address" required />
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="subject" class="form-label">Subject <span class="required text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control gmz-validation"
                                    data-validation="required" id="subject" placeholder="Subject" required />
                            </div>
                            <div class="form-group mb-3">
                                <label for="content" class="form-label">Message <span class="required text-danger">*</span></label>
                                <textarea name="content" rows="4" class="form-control" id="content" placeholder="Type your message here..." required></textarea>
                            </div>
                            <div class="gmz-message mb-3"></div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                SUBMIT REQUEST
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center">
                <div class="become-intro w-100 text-white px-4 py-5" style="background: rgba(0,0,0,0.25); border-radius: 12px;">
                    <h3 class="mb-3">Stay in touch with us</h3>
                    <p class="description mb-4">
                        Please contact us at any time. We are here to serve you.
                    </p>
                    <ul class="list-unstyled fs-5 mb-0">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <span>Address: Zanzibar, Tanzania</span></li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> <span>Phone: <a href="tel:+255774378835" class="text-white text-decoration-underline">+255 774 378835</a></span></li>
                        <li><i class="fas fa-envelope me-2"></i> <span>Email: <a href="mailto:info@zanzibarbookings.com" class="text-white text-decoration-underline">info@zanzibarbookings.com</a></span></li>
                    </ul>
                    <div class="mt-4">
                        <a href="https://wa.me/message/JMDWFIGBWX5TI1" target="_blank" class="btn btn-success px-4 py-2">
                            <i class="fab fa-whatsapp me-2"></i> Chat with us on WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="w-100" style="min-height: 400px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5!2d39.2!3d-6.1659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMDknNTcuMiJTIDM5wrAxMicwMC4wIkU!5e0!3m2!1sen!2stz!4v1234567890123!5m2!1sen!2stz"
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius: 12px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endsection