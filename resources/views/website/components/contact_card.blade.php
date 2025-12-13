<div class="card my-4 contact-card rounded"
style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
<div class="card-header" style="background: #f8f9fa; padding: 15px;">
    <h5 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333;">
        <i class="mdi mdi-phone me-2"></i>Need Help?
    </h5>
</div>
<div class="card-body p-3">
    <p class="mb-3" style="color: #666; font-size: 14px;">
        Contact us for more information!.
    </p>
    <div class="contact-info">
        <ul class="list-group list-group-flush" style="border-radius: 8px; overflow: hidden;">
            <li class="list-group-item d-flex align-items-center" style="border: none; padding-left: 0;">
                <i class="mdi mdi-phone me-2" style="color: #2e8b57; width: 20px;"></i>
                <span style="font-size: 14px; color: #333;">
                    <a href="tel:{{ str_replace(' ', '', $systemSettings->phone ?? '+255774378835') }}" class="text-decoration-none" style="color: #333;">
                        {{ $systemSettings->phone ?? '+255 774 378835' }}
                    </a>
                </span>
            </li>
            @if($systemSettings && $systemSettings->secondary_phone)
            <li class="list-group-item d-flex align-items-center" style="border: none; padding-left: 0;">
                <i class="mdi mdi-phone me-2" style="color: #2e8b57; width: 20px;"></i>
                <span style="font-size: 14px; color: #333;">
                    <a href="tel:{{ str_replace(' ', '', $systemSettings->secondary_phone) }}" class="text-decoration-none" style="color: #333;">
                        {{ $systemSettings->secondary_phone }}
                    </a>
                </span>
            </li>
            @endif
            <li class="list-group-item d-flex align-items-center" style="border: none; padding-left: 0;">
                <i class="mdi mdi-email me-2" style="color: #2e8b57; width: 20px;"></i>
                <span style="font-size: 14px; color: #333;">
                    <a href="mailto:{{ $systemSettings->email ?? 'info@zanzibarbookings.com' }}" class="text-decoration-none" style="color: #333;">
                        {{ $systemSettings->email ?? 'info@zanzibarbookings.com' }}
                    </a>
                </span>
            </li>
            <li class="list-group-item d-flex align-items-center" style="border: none; padding-left: 0;">
                <i class="mdi mdi-clock me-2" style="color: #2e8b57; width: 20px;"></i>
                <span style="font-size: 14px; color: #333;">24/7 Support</span>
            </li>
        </ul>
    </div>
</div>
</div>