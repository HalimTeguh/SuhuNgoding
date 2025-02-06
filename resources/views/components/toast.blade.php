<!-- resources/views/components/toast.blade.php -->
<div class="toast-container position-relative">
    @foreach ($toasts as $toast)
        <div class="bs-toast toast fade show bg-{{ $toast['type'] }}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-medium">{{ $toast['title'] }}</div>
                <small>{{ $toast['time'] }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $toast['message'] }}
            </div>
        </div>
    @endforeach
</div>
