@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height: 100vh; background-color: #ffffff;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Header --}}
            <div class="text-center mb-4">
                <div class="mx-auto mb-3 rounded-circle bg-purple-100 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                    <i class="bi bi-people-fill text-purple fs-3"></i>
                </div>
                <h1 class="fw-bold text-purple-900">Group Booking</h1>
                <p class="text-muted">Provide details for each attendee (2-8 people)</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('group-booking.submit') }}">
                @csrf

                @php
                    $attendees = old('attendees', [
                        ['name'=>'','email'=>'','phone'=>''],
                        ['name'=>'','email'=>'','phone'=>''],
                    ]);
                @endphp

                @foreach ($attendees as $index => $attendee)
                <div class="card mb-3 card-border-purple shadow-sm rounded-4 attendee-card" data-index="{{ $index }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-purple-700 attendee-title">Attendee {{ $index + 1 }}</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-attendee">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-purple-700" for="attendees-{{ $index }}-name">Full Name *</label>
                            <input type="text" name="attendees[{{ $index }}][name]" id="attendees-{{ $index }}-name" class="form-control @error("attendees.$index.name") is-invalid @enderror" value="{{ $attendee['name'] }}">
                            @error("attendees.$index.name")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-purple-700" for="attendees-{{ $index }}-email">Email Address *</label>
                            <input type="email" name="attendees[{{ $index }}][email]" id="attendees-{{ $index }}-email" class="form-control @error("attendees.$index.email") is-invalid @enderror" value="{{ $attendee['email'] }}">
                            @error("attendees.$index.email")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-purple-700" for="attendees-{{ $index }}-phone">Phone Number *</label>
                            <input type="tel" name="attendees[{{ $index }}][phone]" id="attendees-{{ $index }}-phone" class="form-control @error("attendees.$index.phone") is-invalid @enderror" value="{{ $attendee['phone'] }}">
                            @error("attendees.$index.phone")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Add Attendee Button --}}
                <div class="d-grid mb-3">
                    <button type="button" class="btn btn-outline-purple" id="add-attendee">
                        <i class="bi bi-plus me-2"></i> Add Another Attendee ({{ count($attendees) }}/8)
                    </button>
                </div>

                {{-- Total Amount --}}
                @php $totalAmount = count($attendees) * 5000; @endphp
                <div class="p-3 mb-3 border border-purple rounded-3">
                    <div class="d-flex justify-content-between fw-bold text-purple-700">
                        <span>Total Amount:</span>
                        <span>KSh {{ $totalAmount }}</span>
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

{{-- JS for dynamic add/remove attendees --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const maxAttendees = 8;
    const minAttendees = 2;
    const addBtn = document.getElementById('add-attendee');

    function updateAddButtonText(form) {
        const count = form.querySelectorAll('.attendee-card').length;
        addBtn.innerHTML = '<i class="bi bi-plus me-2"></i> Add Another Attendee (' + count + '/' + maxAttendees + ')';
        addBtn.disabled = count >= maxAttendees;
    }

    addBtn.addEventListener('click', function () {
        const form = this.closest('form');
        let cards = form.querySelectorAll('.attendee-card');
        if (cards.length >= maxAttendees) return;

        let lastCard = cards[cards.length - 1];
        let newCard = lastCard.cloneNode(true);

        newCard.querySelectorAll('input').forEach(input => input.value = '');

        const idx = cards.length;
        newCard.dataset.index = idx;
        newCard.querySelector('.attendee-title').textContent = 'Attendee ' + (idx + 1);

        newCard.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name').replace(/\d+/, idx);
            const id = input.getAttribute('id').replace(/\d+/, idx);
            input.setAttribute('name', name);
            input.setAttribute('id', id);
        });

        newCard.querySelector('.remove-attendee').classList.remove('d-none');
        form.insertBefore(newCard, addBtn.parentNode);
        updateAddButtonText(form);
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-attendee')) {
            const form = e.target.closest('form');
            let cards = form.querySelectorAll('.attendee-card');
            if (cards.length <= minAttendees) return;

            const card = e.target.closest('.attendee-card');
            card.remove();

            form.querySelectorAll('.attendee-card').forEach((c, i) => {
                c.dataset.index = i;
                c.querySelector('.attendee-title').textContent = 'Attendee ' + (i + 1);
                c.querySelectorAll('input').forEach(input => {
                    const name = input.getAttribute('name').replace(/\d+/, i);
                    const id = input.getAttribute('id').replace(/\d+/, i);
                    input.setAttribute('name', name);
                    input.setAttribute('id', id);
                });

                const btn = c.querySelector('.remove-attendee');
                if (form.querySelectorAll('.attendee-card').length <= minAttendees) {
                    btn.classList.add('d-none');
                } else {
                    btn.classList.remove('d-none');
                }
            });

            updateAddButtonText(form);
        }
    });

    // Initialize remove buttons visibility
    const form = addBtn.closest('form');
    form.querySelectorAll('.attendee-card').forEach((c, i) => {
        const btn = c.querySelector('.remove-attendee');
        if (i < minAttendees) btn.classList.add('d-none');
    });
    updateAddButtonText(form);
});
</script>
@endpush
@endsection
