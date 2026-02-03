@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height: 100vh; background-color: #ffffff;">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Header --}}
            <div class="text-center mb-4">
                <div class="mx-auto mb-3 rounded-circle bg-purple-100 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                    <i class="bi bi-person-fill text-purple fs-3"></i>
                </div>
                <h1 class="fw-bold text-purple-900">Individual Booking</h1>
                <p class="text-muted">Please provide your details</p>
            </div>

            {{-- Form Card --}}
            <div class="card card-border-purple shadow-sm rounded-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('booking.submit.individual') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold text-purple-700">Full Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-purple-700">Email Address *</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="john@example.com" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold text-purple-700">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 (555) 123-4567" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Total Amount --}}
                        <div class="p-3 mb-3 border border-purple rounded-3">
                            <div class="d-flex justify-content-between fw-bold text-purple-700">
                                <span>Total Amount:</span>
                                <span>KSh 5,000</span>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('booking.type') }}" class="btn btn-outline-purple flex-fill rounded-3">Back</a>
                            <button type="submit" class="btn btn-purple flex-fill rounded-3">Continue to Confirmation</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
