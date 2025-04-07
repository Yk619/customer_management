@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Verify Your Identity</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-4">We've sent a 4-digit verification code to your email. Please enter it below:</p>

                    <form method="POST" action="{{ route('verify-mfa') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="mfa_token" class="form-label">Verification Code</label>
                            <input id="mfa_token" type="text" class="form-control @error('mfa_token') is-invalid @enderror" 
                                   name="mfa_token" required autofocus>
                            @error('mfa_token')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Verify
                            </button>
                        </div>

                        <div class="mt-3 text-center">
                            Didn't receive code? <a href="{{ route('login') }}">Request again</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection