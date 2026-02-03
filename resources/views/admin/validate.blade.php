@extends('admin.login')

@section('title', 'Validate Ticket')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow rounded-4 w-100 border-purple" style="max-width: 500px;">
        <div class="card-body p-5">

            <!-- Header -->
            <h2 class="fw-bold mb-3 text-center text-purple-900">Validate Ticket</h2>
            <p class="text-center text-muted mb-4">Scan QR code or enter ticket code manually</p>

            <!-- Tabs -->
            <div class="d-flex justify-content-center mb-4 gap-2">
                <button type="button" onclick="showScanner()" id="tabScan" class="btn btn-purple-tab active-tab rounded-pill px-4 py-2">QR Scanner</button>
                <button type="button" onclick="showManual()" id="tabManual" class="btn btn-purple-tab inactive-tab rounded-pill px-4 py-2">Manual Entry</button>
            </div>

            <!-- QR Scanner Panel -->
            <div id="scanner-panel" class="text-center mb-3">
                <div class="d-flex flex-column align-items-center justify-content-center p-3 border border-light rounded-4 shadow-sm mb-3">
                    <div id="qr-reader" class="w-100 mb-3" style="height: 250px; border-radius: 1rem; background-color: #f3e8ff;"></div>
                    <div id="camera-alert" class="alert alert-warning w-100 d-none mb-3" role="alert">
                        No camera detected. Please use manual ticket entry.
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2">
                        <p class="text-muted mb-0">Ready to Scan</p>
                        <p class="text-muted small mb-2">Click the button below to activate the camera</p>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="startQR()" class="btn btn-purple px-3">Start Scanner</button>
                            <button type="button" onclick="stopQR()" class="btn btn-outline-purple px-3">Stop Scanner</button>
                        </div>
                    </div>
                </div>
                <p id="scanner-error" class="text-danger small d-none"></p>
            </div>

            <!-- Manual Entry Panel -->
            <div id="manual-panel" style="display:none;">
                <label for="manualCode" class="form-label">Ticket Code</label>
                <input type="text" id="manualCode" class="form-control mb-2 rounded-3" placeholder="Paste or type the full ticket code">
                <p class="text-muted small mb-3">Enter the complete ticket code from the customer's ticket</p>
                <button type="button" onclick="submitCode(document.getElementById('manualCode').value)" class="btn btn-purple w-100 mb-2">Validate Ticket</button>
                <div class="p-2 bg-light text-purple small rounded-3 border border-purple">
                    <b>Expected format:</b> TICKET:TKT-XXXXX-X|NAME:...|EMAIL:...|EVENT:...|DATE:...
                </div>
            </div>

            <!-- Hidden Form -->
            <form id="ticketForm" method="POST" action="{{ route('admin.validate.submit') }}">
                @csrf
                <input type="hidden" id="ticket_code" name="code">
            </form>

            <!-- Validation Result -->
            @if(session('result'))
                @php
                    $success = session('result.success');
                    $alreadyUsed = session('result.alreadyScanned') ?? false;
                    $alertClass = $success ? 'alert-success' : ($alreadyUsed ? 'alert-danger' : 'alert-warning');
                    $ticketData = session('result.ticketData') ?? null;
                @endphp
                <div class="alert {{ $alertClass }} mt-3" role="alert">
                    <p class="fw-bold mb-1">{{ session('result.message') }}</p>
                    @if($ticketData)
                        <div class="small text-muted">
                            <p><b>Name:</b> {{ $ticketData['name'] ?? $ticketData->name }}</p>
                            <p><b>Email:</b> {{ $ticketData['email'] ?? $ticketData->email }}</p>
                            <p><b>Event:</b> {{ $ticketData['event'] ?? $ticketData->event }}</p>
                            <p><b>Date:</b> {{ $ticketData['date'] ?? $ticketData->date }}</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.btn-purple-tab {
    border: 2px solid #6f42c1;
    color: #6f42c1;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-purple-tab:hover {
    background-color: #6f42c1;
    color: #fff;
}
.active-tab {
    background-color: #6f42c1;
    color: #fff;
}
.inactive-tab {
    background-color: #fff;
}
#qr-reader {
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 1rem;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner;

// Tabs
function showScanner() {
    document.getElementById('scanner-panel').style.display = 'block';
    document.getElementById('manual-panel').style.display = 'none';
    document.getElementById('tabScan').classList.add('active-tab');
    document.getElementById('tabScan').classList.remove('inactive-tab');
    document.getElementById('tabManual').classList.remove('active-tab');
    document.getElementById('tabManual').classList.add('inactive-tab');
}

function showManual() {
    document.getElementById('manual-panel').style.display = 'block';
    document.getElementById('scanner-panel').style.display = 'none';
    document.getElementById('tabManual').classList.add('active-tab');
    document.getElementById('tabManual').classList.remove('inactive-tab');
    document.getElementById('tabScan').classList.remove('active-tab');
    document.getElementById('tabScan').classList.add('inactive-tab');
}

// Submit code
function submitCode(code) {
    if (!code || code.trim() === '') return;
    document.getElementById('ticket_code').value = code.trim();
    if (scanner) {
        scanner.stop().catch(err => console.log(err));
    }
    document.getElementById('ticketForm').submit();
}

// Start QR scanner
function startQR() {
    const scannerError = document.getElementById('scanner-error');
    const cameraAlert = document.getElementById('camera-alert');
    scannerError.classList.add('d-none');
    scannerError.textContent = '';
    cameraAlert.classList.add('d-none');

    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            const cameraId = cameras[0].id;
            scanner = new Html5Qrcode("qr-reader");
            scanner.start(cameraId, { fps: 10, qrbox: 250 }, (decodedText) => {
                submitCode(decodedText);
            }).catch(err => {
                scannerError.textContent = "Unable to start QR scanner: " + err;
                scannerError.classList.remove('d-none');
            });
        } else {
            cameraAlert.classList.remove('d-none');
        }
    }).catch(err => {
        cameraAlert.classList.remove('d-none');
        console.error(err);
    });
}

// Stop QR scanner
function stopQR() {
    const scannerError = document.getElementById('scanner-error');
    if (scanner) {
        scanner.stop().then(() => {
            scannerError.textContent = "Scanner stopped.";
            scannerError.classList.remove('d-none');
        }).catch(err => {
            scannerError.textContent = "Error stopping scanner: " + err;
            scannerError.classList.remove('d-none');
        });
    }
}

// Start scanner by default
document.addEventListener('DOMContentLoaded', () => {
    showScanner();
});
</script>
@endpush
