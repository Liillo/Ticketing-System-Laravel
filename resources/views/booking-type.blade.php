@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold text-purple display-5">Choose Your Booking Type</h1>
        <p class="lead text-purple">Select whether you're booking for yourself or a group</p>
    </div>

    <div class="row g-4 justify-content-center">

        <!-- Individual -->
        <div class="col-md-5">
            <div onclick="location.href='{{ route('booking.individual') }}'"
                 class="card h-100 shadow-lg border-2 booking-card text-center p-4">

                <div class="mx-auto mb-4 icon-circle">
                    <i class="bi bi-person fs-1 text-purple"></i>
                </div>

                <h3 class="fw-bold">Individual Booking</h3>
                <p class="text-muted">Book a ticket for yourself</p>

                <div class="bg-light-purple p-3 rounded mb-3">
                    <p class="mb-1 text-muted">Perfect for solo attendees</p>
                    <h2 class="text-purple fw-bold">KSh 5,000</h2>
                    <small>Single ticket</small>
                </div>

                <ul class="list-unstyled text-start small">
                    <li>✓ Quick and easy registration</li>
                    <li>✓ Instant ticket generation</li>
                    <li>✓ QR code for fast entry</li>
                </ul>
            </div>
        </div>

        <!-- Group -->
        <div class="col-md-5">
            <div onclick="location.href='{{ route('booking.group') }}'"
                 class="card h-100 shadow-lg border-2 booking-card text-center p-4">

                <div class="mx-auto mb-4 icon-circle">
                    <i class="bi bi-people fs-1 text-purple"></i>
                </div>

                <h3 class="fw-bold">Group Booking</h3>
                <p class="text-muted">Book for up to 8 people</p>

                <div class="bg-light-purple p-3 rounded mb-3">
                    <p class="mb-1 text-muted">Great for friends and family</p>
                    <h2 class="text-purple fw-bold">KSh 5,000</h2>
                    <small>per person (2–8 people)</small>
                </div>

                <ul class="list-unstyled text-start small">
                    <li>✓ Book for your entire group</li>
                    <li>✓ Individual tickets for each person</li>
                    <li>✓ One payment for all tickets</li>
                </ul>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="{{ url('/') }}" class="text-purple text-decoration-underline">
            ← Back to Event Details
        </a>
    </div>

</div>
@endsection
