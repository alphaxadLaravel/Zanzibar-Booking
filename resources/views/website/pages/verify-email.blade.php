@extends('website.layouts.app')

@section('pages')
<section class="container py-5" style="max-width: 560px;">
    <h1 class="h3 mb-2">Verify your email</h1>
    <p class="text-muted mb-4">
        We sent a verification link to your inbox. Open that email and tap the link to activate your account.
    </p>

    @auth
        <div class="alert alert-info">
            Signed in as <strong>{{ Auth::user()->email }}</strong>
            @if(Auth::user()->email_verified_at)
                — already verified.
            @else
                — not verified yet.
            @endif
        </div>

        @if(!Auth::user()->email_verified_at)
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary" data-loading-text="Sending...">Resend verification email</button>
            </form>
        @endif
    @else
        <p class="mb-3">Sign in to resend the verification email.</p>
        <a href="{{ route('index') }}" class="btn btn-primary" onclick="switchToLogin(); return false;">Sign in</a>
    @endauth
</section>
@endsection
