@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            @foreach ($classes as $class)
            <div class="col-xxl-6 col-md-6 mb-4">
                <div class="card text-white overflow-hidden border-0 rounded shadow" style="background-image: url('{{ asset('storage/' . $class->image) }}');
                        background-size: cover;
                        background-position: center;
                        position: relative; min-height: 200px;">

                    {{-- Gradient overlay --}}
                    <div style="position: absolute;
                            inset: 0;
                            background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.1));
                            z-index: 1;">
                    </div>

                    {{-- Content --}}
                    <div class="card-body d-flex justify-content-between align-items-center h-100 position-relative"
                        style="z-index: 2;">
                        <div>
                            <h5 class="card-title mb-2 text-white">{{ $class->name }}</h5>
                            <p class="mb-1"><strong>Guru:</strong> {{ $class->teacher->user->name ?? '-' }}</p>
                            <p class="mb-0">{{ Str::limit($class->description, 80) }}</p>
                        </div>
                        <div>
                            <a href="{{ route('dashboard.student.class.show', $class->id) }}" class="btn btn-m btn-primary shadow">
                                Masuk Kelas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

@endsection