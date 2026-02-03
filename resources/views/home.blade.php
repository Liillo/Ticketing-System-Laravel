@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 text-purple">Music Festival 2026</h1>
        <p class="lead text-purple">The Ultimate Summer Experience</p>
    </div>

    <!-- Hero Image -->
    <div class="hero-img mb-5">
        <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&h=600&fit=crop" alt="Music Festival">
        <div class="hero-text">
            <h2 class="h3 fw-bold mb-2">Join Us for an Unforgettable Night</h2>
            <p>Live performances, amazing food, and incredible vibes</p>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row g-4 mb-5">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">📅 Event Date</h5>
                    <p class="card-text">Saturday, August 15, 2026</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">⏰ Event Time</h5>
                    <p class="card-text">6:00 PM - 11:00 PM</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">📍 Location</h5>
                    <p class="card-text mb-1">Mwalimu Towers</p>
                    <small class="text-muted">Hill Ln, Upperhill, Nairobi</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">👥 Ticket Price</h5>
                    <p class="h3 text-purple">KSh 5,000</p>
                    <small class="text-muted">per person</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Event Details -->
    <div class="card shadow-sm border-purple mb-5">
        <div class="card-body">
            <h4 class="card-title">Event Details</h4>
            <p class="text-muted">What to expect at the party</p>

            <h5 class="mt-3">Lineup:</h5>
            <ul>
                <li>Main Speaker</li>
                <li>Special Guest: </li>
            </ul>

            <h5 class="mt-3">Amenities:</h5>
            <ul>
                <li>Food and beverage stations</li>
                <li>lounge area</li>
                <li>Free parking available</li>
            </ul>
        </div>
    </div>

    <!-- Booking Button -->
    <div class="text-center">
        <a href="{{ route('booking.type') }}" class="btn btn-purple btn-lg px-5 py-3 fw-bold">
            Book Your Tickets Now
        </a>
    </div>

</div>
@endsection
