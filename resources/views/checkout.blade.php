@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height:100vh;background:#fff;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Header --}}
            <div class="text-center mb-4">
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:64px;height:64px;background:#f3e8ff;">
                    <i class="bi bi-credit-card text-purple fs-3"></i>
                </div>
                <h1 class="fw-bold text-purple">Checkout & Payment</h1>
                <p class="text-muted">Confirm your booking and complete payment</p>
            </div>

            {{-- Booking Summary --}}
            <div class="card border-purple rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold text-purple mb-3">Booking Summary</h5>

                    <ul class="list-group mb-3">
                        @foreach($bookingData['attendees'] as $a)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $a['name'] }}</span>
                                <span>{{ $a['email'] }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between fw-bold text-purple fs-5">
                        <span>Total Amount:</span>
                        <span>KSh {{ number_format($bookingData['totalAmount']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Display General Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Payment Form --}}
            <form method="POST" action="{{ route('booking.checkout.process') }}">
                @csrf

                <div class="card shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-1">Select Payment Method</h5>
                    <p class="text-muted mb-4">Choose your preferred payment option</p>

                    {{-- METHOD: CARD --}}
                    <label class="payment-option active" data-method="card">
                        <input type="radio" name="payment_method" value="card" checked hidden>
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <div>
                                <strong>Credit/Debit Card</strong><br>
                                <small>Pay with Visa, Mastercard, or Amex</small>
                            </div>
                        </div>
                    </label>

                    {{-- METHOD: MOBILE --}}
                    <label class="payment-option" data-method="mobile">
                        <input type="radio" name="payment_method" value="mobile" hidden>
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div>
                                <strong>Mobile Money</strong><br>
                                <small>M-Pesa, Airtel Money</small>
                            </div>
                        </div>
                    </label>

                    {{-- METHOD: ONLINE --}}
                    <label class="payment-option" data-method="online">
                        <input type="radio" name="payment_method" value="online" hidden>
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle">
                                <i class="bi bi-globe"></i>
                            </div>
                            <div>
                                <strong>Online Payment</strong><br>
                                <small>PayPal, Stripe, or other services</small>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- CARD FORM --}}
                <div id="card-form" class="payment-form">
                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" name="card_number" value="{{ old('card_number') }}" 
                               class="form-control @error('card_number') is-invalid @enderror" 
                               placeholder="1234 5678 9012 3456">
                        @error('card_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cardholder Name</label>
                        <input type="text" name="card_name" value="{{ old('card_name') }}" 
                               class="form-control @error('card_name') is-invalid @enderror" 
                               placeholder="JOHN DOE">
                        @error('card_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Expiry</label>
                            <input type="text" name="card_expiry" value="{{ old('card_expiry') }}" 
                                   class="form-control @error('card_expiry') is-invalid @enderror" 
                                   placeholder="MM/YY">
                            @error('card_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label class="form-label">CVV</label>
                            <input type="password" name="card_cvv" 
                                   class="form-control @error('card_cvv') is-invalid @enderror" 
                                   placeholder="123">
                            @error('card_cvv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- MOBILE --}}
                <div id="mobile-section" class="payment-form d-none">
                    <div class="mb-3">
                        <label for="provider" class="form-label fw-semibold">Mobile Money Provider</label>
                        <select id="provider" name="provider" class="form-select form-select-lg rounded-3 @error('provider') is-invalid @enderror" required>
                            <option value="mpesa" {{ old('provider') == 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                            <option value="airtel" {{ old('provider') == 'airtel' ? 'selected' : '' }}>Airtel Money</option>
                        </select>
                        @error('provider')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mobilePhone" class="form-label fw-semibold">Phone Number</label>
                        <input type="tel" id="mobilePhone" name="phone" value="{{ old('phone') }}" 
                               class="form-control form-control-lg rounded-3 bg-light @error('phone') is-invalid @enderror" 
                               placeholder="0712 345 678" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-primary rounded-3">
                        You will receive a payment prompt on your phone.  
                        Please complete the transaction within 5 minutes.
                    </div>
                </div>

                {{-- ONLINE --}}
                <div id="online-form" class="payment-form d-none">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="online_email" value="{{ old('online_email') }}" 
                               class="form-control @error('online_email') is-invalid @enderror" 
                               placeholder="john@example.com">
                        @error('online_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('booking.type') }}" class="btn btn-outline-secondary w-50">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-purple w-50">
                        Pay KSh {{ number_format($bookingData['totalAmount']) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.payment-option').forEach(option => {
    option.addEventListener('click', () => {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
        option.classList.add('active');

        const method = option.dataset.method;
        document.querySelectorAll('.payment-form').forEach(f => f.classList.add('d-none'));
       
        if (method === "mobile") {
            document.getElementById("mobile-section").classList.remove("d-none");
        } else {
            document.getElementById(method + "-form").classList.remove("d-none");
        }
    });
});
</script>
@endpush
@endsection
