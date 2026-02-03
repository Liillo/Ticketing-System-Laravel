<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ticketing App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif,
                         'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            background: #ffffff;
        }

        /* Purple theme */
        .text-purple { color: #6f42c1 !important; }
        .text-purple-700 { color: #6f42c1 !important; }
        .text-purple-900 { color: #4b0082 !important; }

        .btn-purple {
            background-color: #6f42c1;
            color: #fff;
            transition: all 0.3s ease;
        }
        .btn-purple:hover {
            background-color: #59339d;
            color: #fff;
        }

        .btn-outline-purple {
            color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-outline-purple:hover {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
        }

        .border-purple {
            border: 2px solid #6f42c1 !important;
        }

        .hero-img {
            position: relative;
            height: 24rem;
            overflow: hidden;
            border-radius: 1rem;
        }
        .hero-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            color: white;
            padding: 2rem;
        }

        /* Booking cards hover effect */
        .booking-card {
            cursor: pointer;
            transition: all 0.35s ease;
            border: 2px solid #e5d4f5;
        }
        .booking-card:hover {
            transform: translateY(-10px) scale(1.05);
            border-color: #6f42c1;
            box-shadow: 0 20px 40px rgba(111,66,193,0.25);
        }

        .rounded-3 { border-radius: 0.75rem !important; }
        .rounded-4 { border-radius: 1rem !important; }

        /* Purple card border for forms */
        .card-border-purple {
            border: 2px solid #6f42c1;
        }

        .payment-option {
    display: block;
    border: 2px solid #eee;
    border-radius: 16px;
    padding: 1rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all .3s ease;
}

.payment-option:hover {
    border-color: #6f42c1;
}

.payment-option.active {
    border-color: #a855f7;
    background: #faf5ff;
    box-shadow: 0 0 0 3px rgba(168,85,247,0.15);
}

.icon-circle {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #f3e8ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7c3aed;
    font-size: 20px;
}

    </style>

    {{-- Additional page-specific styles --}}
    @stack('styles')
</head>
<body>

    {{-- Optional Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-purple-900" href="{{ url('/') }}">Ticketing</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-purple-900" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-purple-900" href="{{ route('booking.type') }}">Booking</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Additional page-specific scripts --}}
    @stack('scripts')
</body>
</html>
