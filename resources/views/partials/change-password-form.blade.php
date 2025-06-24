<form action="{{ route('dashboard.student.changePassword.submit') }}" method="POST">
    @csrf

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Old Password -->
    <div class="mb-3">
        <label for="old_password" class="form-label">Password Lama</label>
        <input type="password" name="old_password" id="old_password"
            class="form-control @error('old_password') is-invalid @enderror" required>
        @error('old_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- New Password -->
    <div class="mb-3">
        <label for="new_password" class="form-label">Password Baru</label>
        <input type="password" name="new_password" id="new_password"
            class="form-control @error('new_password') is-invalid @enderror" required>
        @error('new_password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm New Password -->
    <div class="mb-4">
        <label for="new_password_confirmation" class="form-label">Konfirmasi Password
            Baru</label>
        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
            class="form-control @error('new_password_confirmation') is-invalid @enderror" required>
        @error('new_password_confirmation')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            Ubah Password
        </button>
    </div>

    <!-- Info -->
    <div class="mt-3 text-muted small">
        <i class="bi bi-info-circle"></i>
        Jika kamu mengalami kesulitan, jangan ragu untuk menghubungi <strong>guru</strong> atau <strong>admin</strong> untuk
        mendapatkan bantuan lebih lanjut.
    </div>
</form>