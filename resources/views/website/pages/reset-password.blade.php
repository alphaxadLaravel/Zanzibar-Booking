@extends('website.layouts.app')

@section('pages')
<section class="container py-5" style="max-width: 520px;">
    <h1 class="h3 mb-2">Reset password</h1>
    <p class="text-muted mb-4">Choose a new password for your Zanzibar Bookings account.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="card card-body shadow-sm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">New password</label>
            <input id="password" type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="6">
        </div>
        <button type="submit" class="btn btn-primary w-100" data-loading-text="Updating...">Update password</button>
    </form>
</section>
@endsection
