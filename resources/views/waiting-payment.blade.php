@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center" style="background: #f5f3ff; padding: 3rem 1rem;">
    <div class="text-center">
        <h1 class="mb-4 text-purple">Waiting for Payment...</h1>
        <p class="mb-3 text-muted">We have sent an M-Pesa payment request to <strong>{{ $tickets[0]->phone }}</strong>.</p>
        <p class="mb-4 text-muted">Please complete the payment on your phone.</p>

        <div class="spinner-border text-purple mb-4" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

        <p class="text-muted">This page will automatically update once payment is confirmed.</p>
    </div>
</div>

<script>
    // Poll backend every 5 seconds to check payment status
    setInterval(async () => {
        try {
            const response = await fetch("{{ route('booking.ticket') }}?check_payment=true", {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if(data.paid) {
                window.location.href = "{{ route('booking.ticket') }}";
            }
        } catch (err) {
            console.error('Error checking payment:', err);
        }
    }, 5000);
</script>
@endsection
